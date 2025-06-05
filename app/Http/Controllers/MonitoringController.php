<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MonitoringController extends Controller
{
    private const API_ENDPOINTS = [
        'aktivs' => '/api/aktivs',
        'auction' => 'https://projects.toshkentinvest.uz/api/markersing',
        'sold' => 'https://projects.toshkentinvest.uz/api/sotilgan',
        'jsonData' => '/assets/data/443_output.json',
        'geoJsonStrategy' => '/tashkent_master_plan.geojson',
    ];

    // Cache duration in minutes
    private const CACHE_DURATION = 15;

    // Memory optimization settings
    private const MAX_MEMORY_USAGE = 100 * 1024 * 1024; // 100MB limit
    private const CHUNK_SIZE = 1000; // Process data in chunks

    public function index()
    {
        try {
            // Increase memory limit if possible
            $currentLimit = ini_get('memory_limit');
            if ($currentLimit !== '-1') {
                ini_set('memory_limit', '256M');
            }

            // Enable garbage collection
            gc_enable();

            Log::info('Starting monitoring dashboard - Memory usage: ' . $this->formatBytes(memory_get_usage(true)));

            $data = [
                'aktivs' => $this->fetchAktivsDataOptimized(),
                'auctions' => $this->fetchAuctionDataOptimized(),
                'sold' => $this->fetchSoldDataOptimized(),
                'jsonData' => $this->fetchJsonDataOptimized(),
                'geoJsonStrategy' => $this->fetchGeoJsonDataOptimized(),
            ];

            // Force garbage collection after each data fetch
            gc_collect_cycles();

            // Calculate summary after getting all data
            $data['summary'] = $this->calculateSummary($data);

            Log::info('Completed data fetching - Memory usage: ' . $this->formatBytes(memory_get_usage(true)));

            return view('monitoring.dashboard', $data);

        } catch (\Exception $e) {
            Log::error('MonitoringController index error: ' . $e->getMessage());
            Log::error('Memory usage at error: ' . $this->formatBytes(memory_get_usage(true)));

            // Clear any loaded data to free memory
            if (isset($data)) {
                unset($data);
            }
            gc_collect_cycles();

            // Return minimal error response
            return response()->view('errors.500', [
                'message' => 'Memory limit exceeded. Please contact administrator.'
            ], 500);
        }
    }

    /**
     * Memory-optimized file reading with streaming
     */
    private function readFileStreamOptimized($filePath)
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return null;
        }

        $fileSize = filesize($filePath);

        // If file is too large, return error
        if ($fileSize > 50 * 1024 * 1024) { // 50MB limit
            Log::warning("File too large: {$filePath} ({$this->formatBytes($fileSize)})");
            return null;
        }

        // Check available memory before reading
        $memoryUsage = memory_get_usage(true);
        if ($memoryUsage > self::MAX_MEMORY_USAGE) {
            Log::warning("Memory usage too high before reading file: " . $this->formatBytes($memoryUsage));
            return null;
        }

        try {
            $content = file_get_contents($filePath);
            if ($content === false) {
                return null;
            }

            $data = json_decode($content, true);

            // Free the content variable immediately
            unset($content);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("JSON decode error for {$filePath}: " . json_last_error_msg());
                return null;
            }

            return $data;

        } catch (\Exception $e) {
            Log::error("Error reading file {$filePath}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Process data in chunks to save memory
     */
    private function processDataInChunks($data, $processor)
    {
        if (!is_array($data)) {
            return [];
        }

        $result = [];
        $chunks = array_chunk($data, self::CHUNK_SIZE);

        foreach ($chunks as $chunk) {
            $chunkResult = $processor($chunk);
            $result = array_merge_recursive($result, $chunkResult);

            // Free chunk memory
            unset($chunk, $chunkResult);

            // Check memory usage
            if (memory_get_usage(true) > self::MAX_MEMORY_USAGE) {
                gc_collect_cycles();

                if (memory_get_usage(true) > self::MAX_MEMORY_USAGE) {
                    Log::warning('Memory limit reached during chunk processing');
                    break;
                }
            }
        }

        unset($chunks);
        return $result;
    }

    private function fetchAktivsDataOptimized()
    {
        $cacheKey = 'aktivs_data_optimized';

        try {
            // Check cache first
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            // Try simple database query first (most memory efficient)
            try {
                if (class_exists('App\\Models\\Lot')) {
                    $count = \App\Models\Lot::count();
                    $result = [
                        'total' => $count,
                        'with_coordinates' => 0,
                        'with_images' => 0,
                        'with_documents' => 0,
                        'total_area' => 0,
                        'investors' => [],
                        'districts' => [],
                        'by_floors' => [],
                        'avg_area' => 0,
                        'with_kmz' => 0,
                        'status' => 'success',
                        'last_updated' => now()->format('Y-m-d H:i:s'),
                        'source' => 'database_count_only'
                    ];

                    Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_DURATION));
                    return $result;
                }
            } catch (\Exception $e) {
                Log::info('Database count failed: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('Aktivs Data Error: ' . $e->getMessage());
        }

        return [
            'status' => 'error',
            'total' => 0,
            'error' => 'Failed to fetch aktivs data',
            'source' => 'error',
            'last_updated' => now()->format('Y-m-d H:i:s')
        ];
    }

    private function fetchAuctionDataOptimized()
    {
        $cacheKey = 'auction_data_optimized';

        try {
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            // Try external API with timeout
            $result = $this->makeHttpRequestOptimized(self::API_ENDPOINTS['auction'], 10);

            if (isset($result['success']) && $result['success']) {
                $data = $result['data'];
                $lots = $data['lots'] ?? [];

                // Process in smaller chunks
                $processedResult = $this->processAuctionDataInChunks($lots);
                $processedResult['source'] = 'external_api';
                $processedResult['status'] = 'success';
                $processedResult['last_updated'] = now()->format('Y-m-d H:i:s');

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }
        } catch (\Exception $e) {
            Log::error('Auction API Error: ' . $e->getMessage());
        }

        return [
            'status' => 'error',
            'total' => 0,
            'error' => 'Failed to fetch auction data',
            'last_updated' => now()->format('Y-m-d H:i:s')
        ];
    }

    private function fetchSoldDataOptimized()
    {
        $cacheKey = 'sold_data_optimized';

        try {
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $result = $this->makeHttpRequestOptimized(self::API_ENDPOINTS['sold'], 10);

            if (isset($result['success']) && $result['success']) {
                $data = $result['data'];
                $lots = $data['lots'] ?? [];

                $processedResult = $this->processSoldDataInChunks($lots);
                $processedResult['source'] = 'external_api';
                $processedResult['status'] = 'success';
                $processedResult['last_updated'] = now()->format('Y-m-d H:i:s');

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }
        } catch (\Exception $e) {
            Log::error('Sold API Error: ' . $e->getMessage());
        }

        return [
            'status' => 'error',
            'total' => 0,
            'error' => 'Failed to fetch sold data',
            'last_updated' => now()->format('Y-m-d H:i:s')
        ];
    }

    private function fetchJsonDataOptimized()
    {
        $cacheKey = 'json_data_optimized';

        try {
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $localPath = public_path('assets/data/443_output.json');

            // Check file size first
            if (file_exists($localPath)) {
                $fileSize = filesize($localPath);
                Log::info("JSON file size: " . $this->formatBytes($fileSize));

                // If file is too large, return basic info only
                if ($fileSize > 10 * 1024 * 1024) { // 10MB limit
                    $result = [
                        'total' => 0,
                        'status' => 'error',
                        'error' => 'File too large to process',
                        'file_size' => $this->formatBytes($fileSize),
                        'last_updated' => now()->format('Y-m-d H:i:s')
                    ];

                    Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_DURATION));
                    return $result;
                }
            }

            $data = $this->readFileStreamOptimized($localPath);

            if ($data !== null) {
                $processedResult = $this->processJsonDataInChunks($data);
                $processedResult['source'] = 'local_file';
                $processedResult['status'] = 'success';
                $processedResult['last_updated'] = now()->format('Y-m-d H:i:s');

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }

        } catch (\Exception $e) {
            Log::error('JSON Data API Error: ' . $e->getMessage());
        }

        return [
            'status' => 'error',
            'total' => 0,
            'error' => 'Failed to fetch JSON data',
            'last_updated' => now()->format('Y-m-d H:i:s')
        ];
    }

    private function fetchGeoJsonDataOptimized()
    {
        $cacheKey = 'geojson_data_optimized';

        try {
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $localPath = public_path('tashkent_master_plan.geojson');

            // Check file size first
            if (file_exists($localPath)) {
                $fileSize = filesize($localPath);
                Log::info("GeoJSON file size: " . $this->formatBytes($fileSize));

                // If file is too large, return basic info only
                if ($fileSize > 20 * 1024 * 1024) { // 20MB limit
                    $result = [
                        'total_features' => 0,
                        'status' => 'error',
                        'error' => 'File too large to process',
                        'file_size' => $this->formatBytes($fileSize),
                        'last_updated' => now()->format('Y-m-d H:i:s')
                    ];

                    Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_DURATION));
                    return $result;
                }
            }

            $data = $this->readFileStreamOptimized($localPath);

            if ($data !== null) {
                $features = $data['features'] ?? [];
                $processedResult = $this->processGeoJsonDataInChunks($features);
                $processedResult['source'] = 'local_file';
                $processedResult['status'] = 'success';
                $processedResult['last_updated'] = now()->format('Y-m-d H:i:s');

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }

        } catch (\Exception $e) {
            Log::error('GeoJSON API Error: ' . $e->getMessage());
        }

        return [
            'status' => 'error',
            'total_features' => 0,
            'error' => 'Failed to fetch GeoJSON data',
            'last_updated' => now()->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Memory-optimized HTTP request
     */
    private function makeHttpRequestOptimized($url, $timeout = 15)
    {
        $defaultResult = [
            'success' => false,
            'data' => null,
            'status_code' => 0,
            'error' => 'Unknown error',
        ];

        try {
            if (!function_exists('curl_init')) {
                return array_merge($defaultResult, ['error' => 'cURL not available']);
            }

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 2,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Laravel Monitoring/1.0',
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
                // Limit response size to prevent memory issues
                CURLOPT_BUFFERSIZE => 128 * 1024, // 128KB buffer
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                return array_merge($defaultResult, ['error' => 'Connection error: ' . $error]);
            }

            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, true);

                // Free response memory immediately
                unset($response);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'success' => true,
                        'data' => $data,
                        'status_code' => $httpCode,
                    ];
                }

                return array_merge($defaultResult, [
                    'status_code' => $httpCode,
                    'error' => 'Invalid JSON: ' . json_last_error_msg()
                ]);
            }

            return array_merge($defaultResult, [
                'status_code' => $httpCode,
                'error' => 'HTTP error: ' . $httpCode
            ]);

        } catch (\Exception $e) {
            return array_merge($defaultResult, ['error' => 'Exception: ' . $e->getMessage()]);
        }
    }

    /**
     * Process auction data in memory-efficient chunks
     */
    private function processAuctionDataInChunks($lots)
    {
        if (!is_array($lots)) {
            $lots = [];
        }

        $result = [
            'total' => count($lots),
            'active' => 0,
            'total_start_price' => 0,
            'by_region' => [],
            'by_payment_type' => [],
        ];

        // Process in chunks to save memory
        $chunks = array_chunk($lots, self::CHUNK_SIZE);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $lot) {
                if (isset($lot['lot_status']) && str_contains($lot['lot_status'], 'Savdoda ishtirok')) {
                    $result['active']++;
                }

                if (isset($lot['start_price']) && is_numeric($lot['start_price'])) {
                    $result['total_start_price'] += (float)$lot['start_price'];
                }

                if (!empty($lot['region'])) {
                    $result['by_region'][$lot['region']] = ($result['by_region'][$lot['region']] ?? 0) + 1;
                }

                if (!empty($lot['payment_type'])) {
                    $result['by_payment_type'][$lot['payment_type']] = ($result['by_payment_type'][$lot['payment_type']] ?? 0) + 1;
                }
            }

            unset($chunk);
            gc_collect_cycles();
        }

        $result['avg_start_price'] = $result['total'] > 0 ? $result['total_start_price'] / $result['total'] : 0;

        return $result;
    }

    /**
     * Process sold data in memory-efficient chunks
     */
    private function processSoldDataInChunks($lots)
    {
        if (!is_array($lots)) {
            $lots = [];
        }

        $result = [
            'total' => count($lots),
            'total_sold_amount' => 0,
            'total_start_amount' => 0,
            'by_payment_type' => [],
            'winners' => [],
        ];

        $chunks = array_chunk($lots, self::CHUNK_SIZE);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $lot) {
                if (isset($lot['sold_price']) && is_numeric($lot['sold_price'])) {
                    $result['total_sold_amount'] += (float)$lot['sold_price'];
                }

                if (isset($lot['start_price']) && is_numeric($lot['start_price'])) {
                    $result['total_start_amount'] += (float)$lot['start_price'];
                }

                if (!empty($lot['payment_type'])) {
                    $result['by_payment_type'][$lot['payment_type']] = ($result['by_payment_type'][$lot['payment_type']] ?? 0) + 1;
                }

                if (!empty($lot['winner_name'])) {
                    $result['winners'][$lot['winner_name']] = ($result['winners'][$lot['winner_name']] ?? 0) + 1;
                }
            }

            unset($chunk);
            gc_collect_cycles();
        }

        $result['total_profit'] = $result['total_sold_amount'] - $result['total_start_amount'];
        $result['avg_sold_price'] = $result['total'] > 0 ? $result['total_sold_amount'] / $result['total'] : 0;

        return $result;
    }

    /**
     * Process JSON data in memory-efficient chunks
     */
    private function processJsonDataInChunks($data)
    {
        if (!is_array($data)) {
            $data = [];
        }

        $result = [
            'total' => count($data),
            'by_district' => [],
            'by_type' => [],
            'total_area' => 0,
            'total_population' => 0,
        ];

        $chunks = array_chunk($data, self::CHUNK_SIZE);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $item) {
                if (!empty($item['Туман'])) {
                    $result['by_district'][$item['Туман']] = ($result['by_district'][$item['Туман']] ?? 0) + 1;
                }

                if (!empty($item['Таклиф_тури_(Реновация,_Инвестиция,_Аукцион)'])) {
                    $type = $item['Таклиф_тури_(Реновация,_Инвестиция,_Аукцион)'];
                    $result['by_type'][$type] = ($result['by_type'][$type] ?? 0) + 1;
                }

                if (isset($item['Таклиф_Ер_майдони_(га)']) && is_numeric($item['Таклиф_Ер_майдони_(га)'])) {
                    $result['total_area'] += (float)$item['Таклиф_Ер_майдони_(га)'];
                }

                if (isset($item['Ахоли_сони']) && is_numeric($item['Ахоли_сони'])) {
                    $result['total_population'] += (int)$item['Ахоли_сони'];
                }
            }

            unset($chunk);
            gc_collect_cycles();
        }

        return $result;
    }

    /**
     * Process GeoJSON data in memory-efficient chunks
     */
    private function processGeoJsonDataInChunks($features)
    {
        if (!is_array($features)) {
            $features = [];
        }

        $result = [
            'total_features' => count($features),
            'by_strategy' => [],
            'by_function' => [],
            'by_district' => [],
            'total_area' => 0,
        ];

        $chunks = array_chunk($features, self::CHUNK_SIZE);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $feature) {
                $props = $feature['properties'] ?? [];

                if (!empty($props['strategiya'])) {
                    $result['by_strategy'][$props['strategiya']] = ($result['by_strategy'][$props['strategiya']] ?? 0) + 1;
                }

                if (!empty($props['funksiya'])) {
                    $result['by_function'][$props['funksiya']] = ($result['by_function'][$props['funksiya']] ?? 0) + 1;
                }

                if (!empty($props['district'])) {
                    $result['by_district'][$props['district']] = ($result['by_district'][$props['district']] ?? 0) + 1;
                }

                if (isset($props['maydoni']) && is_numeric($props['maydoni'])) {
                    $result['total_area'] += (float)$props['maydoni'];
                }
            }

            unset($chunk);
            gc_collect_cycles();
        }

        return $result;
    }

    private function calculateSummary($data)
    {
        try {
            return [
                'total_properties' =>
                    ($data['aktivs']['total'] ?? 0) +
                    ($data['auctions']['total'] ?? 0) +
                    ($data['sold']['total'] ?? 0) +
                    ($data['jsonData']['total'] ?? 0),
                'total_investment_value' =>
                    ($data['auctions']['total_start_price'] ?? 0) +
                    ($data['sold']['total_sold_amount'] ?? 0),
                'total_area' =>
                    ($data['aktivs']['total_area'] ?? 0) +
                    ($data['jsonData']['total_area'] ?? 0) +
                    ($data['geoJsonStrategy']['total_area'] ?? 0),
                'total_population' => $data['jsonData']['total_population'] ?? 0,
                'data_sources_status' => [
                    'aktivs' => $data['aktivs']['status'] ?? 'error',
                    'auctions' => $data['auctions']['status'] ?? 'error',
                    'sold' => $data['sold']['status'] ?? 'error',
                    'json_data' => $data['jsonData']['status'] ?? 'error',
                    'geojson' => $data['geoJsonStrategy']['status'] ?? 'error',
                ],
                'last_sync' => now()->format('Y-m-d H:i:s'),
            ];
        } catch (\Exception $e) {
            Log::error('calculateSummary error: ' . $e->getMessage());
            return [
                'total_properties' => 0,
                'total_investment_value' => 0,
                'total_area' => 0,
                'total_population' => 0,
                'data_sources_status' => ['error' => 'Summary calculation failed'],
                'last_sync' => now()->format('Y-m-d H:i:s'),
            ];
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function refresh()
    {
        try {
            Cache::forget('aktivs_data_optimized');
            Cache::forget('auction_data_optimized');
            Cache::forget('sold_data_optimized');
            Cache::forget('json_data_optimized');
            Cache::forget('geojson_data_optimized');

            return redirect()->route('monitoring.dashboard')
                            ->with('success', 'Маълумотлар кэши тозаланди ва янгиланди');
        } catch (\Exception $e) {
            Log::error('refresh error: ' . $e->getMessage());
            return redirect()->route('monitoring.dashboard')
                            ->with('error', 'Кэшни янгилашда хатолик юз берди');
        }
    }

    public function clearCache()
    {
        try {
            Cache::forget('aktivs_data_optimized');
            Cache::forget('auction_data_optimized');
            Cache::forget('sold_data_optimized');
            Cache::forget('json_data_optimized');
            Cache::forget('geojson_data_optimized');

            return response()->json([
                'success' => true,
                'message' => 'Кэш муваффақиятли тозаланди'
            ]);
        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Кэшни тозалашда хатолик'
            ], 500);
        }
    }

    public function memoryStatus()
    {
        return response()->json([
            'memory_usage' => $this->formatBytes(memory_get_usage(true)),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'memory_limit' => ini_get('memory_limit'),
            'cache_status' => [
                'aktivs' => Cache::has('aktivs_data_optimized'),
                'auctions' => Cache::has('auction_data_optimized'),
                'sold' => Cache::has('sold_data_optimized'),
                'json_data' => Cache::has('json_data_optimized'),
                'geojson' => Cache::has('geojson_data_optimized'),
            ]
        ]);
    }
}
