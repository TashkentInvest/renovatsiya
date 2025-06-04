<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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

    public function index()
    {
        $data = [
            'aktivs' => $this->fetchAktivsData(),
            'auctions' => $this->fetchAuctionData(),
            'sold' => $this->fetchSoldData(),
            'jsonData' => $this->fetchJsonData(),
            'geoJsonStrategy' => $this->fetchGeoJsonData(),
        ];

        // Calculate summary after getting all data
        $data['summary'] = $this->calculateSummary($data);

        return view('monitoring.dashboard', $data);
    }

    /**
     * Make HTTP request using cURL with optimized settings
     */
    private function makeHttpRequest($url, $timeout = 30)
    {
        // Default return structure
        $defaultResult = [
            'success' => false,
            'data' => null,
            'status_code' => 0,
            'error' => 'Unknown error',
            'response_time' => 0
        ];

        try {
            // Skip self-referencing URLs to prevent loops
            if (str_contains($url, '127.0.0.1:8000') || str_contains($url, 'localhost')) {
                return array_merge($defaultResult, [
                    'error' => 'Self-referencing URL blocked to prevent loops'
                ]);
            }

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Laravel Monitoring/1.0',
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Cache-Control: no-cache'
                ],
                CURLOPT_ENCODING => '', // Enable compression
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_FORBID_REUSE => true,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            if ($error) {
                Log::warning("cURL Error for {$url}: {$error}");
                return array_merge($defaultResult, [
                    'error' => 'Connection error: ' . $error
                ]);
            }

            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'success' => true,
                        'data' => $data,
                        'status_code' => $httpCode,
                        'response_time' => round($info['total_time'] * 1000, 2)
                    ];
                }

                Log::warning("JSON Parse Error for {$url}: " . json_last_error_msg());
                return array_merge($defaultResult, [
                    'status_code' => $httpCode,
                    'error' => 'Invalid JSON response: ' . json_last_error_msg()
                ]);
            }

            Log::warning("HTTP Error for {$url}: Code {$httpCode}");
            return array_merge($defaultResult, [
                'status_code' => $httpCode,
                'error' => 'HTTP error: ' . $httpCode
            ]);

        } catch (\Exception $e) {
            Log::error("makeHttpRequest exception for {$url}: " . $e->getMessage());
            return array_merge($defaultResult, [
                'error' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get file contents with local file priority and caching
     */
    private function getFileContents($path, $cacheKey = null)
    {
        // Default return structure
        $defaultResult = [
            'success' => false,
            'data' => null,
            'status_code' => 0,
            'source' => 'unknown',
            'error' => 'Unknown error'
        ];

        try {
            // Try cache first if cache key provided
            if ($cacheKey && Cache::has($cacheKey)) {
                $cached = Cache::get($cacheKey);
                if (is_array($cached) && isset($cached['success'])) {
                    $cached['source'] = 'cache';
                    return $cached;
                }
            }

            $result = null;

            // For local files, try direct file access first
            if (str_starts_with($path, '/')) {
                $localPath = public_path(ltrim($path, '/'));

                if (file_exists($localPath)) {
                    try {
                        $content = file_get_contents($localPath);
                        if ($content !== false) {
                            $data = json_decode($content, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $result = [
                                    'success' => true,
                                    'data' => $data,
                                    'status_code' => 200,
                                    'source' => 'local_file'
                                ];
                            } else {
                                Log::error("JSON Parse Error for local file {$localPath}: " . json_last_error_msg());
                                $result = array_merge($defaultResult, [
                                    'error' => 'JSON parse error: ' . json_last_error_msg()
                                ]);
                            }
                        } else {
                            $result = array_merge($defaultResult, [
                                'error' => 'Could not read file content'
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error("File read error for {$localPath}: " . $e->getMessage());
                        $result = array_merge($defaultResult, [
                            'error' => 'File read error: ' . $e->getMessage()
                        ]);
                    }
                } else {
                    Log::warning("Local file not found: {$localPath}");
                    $result = array_merge($defaultResult, [
                        'status_code' => 404,
                        'error' => 'Local file not found'
                    ]);
                }
            }

            // If local file failed or it's an external URL, try HTTP
            if (!$result || !$result['success']) {
                if (str_starts_with($path, '/')) {
                    // Don't make HTTP requests to self
                    $result = array_merge($defaultResult, [
                        'status_code' => 404,
                        'error' => 'Local file not found and HTTP blocked'
                    ]);
                } else {
                    $httpResult = $this->makeHttpRequest($path);
                    // Ensure the HTTP result has the proper structure
                    $result = array_merge($defaultResult, $httpResult);
                }
            }

            // Cache successful results
            if ($cacheKey && $result && $result['success']) {
                Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_DURATION));
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("getFileContents error for {$path}: " . $e->getMessage());
            return array_merge($defaultResult, [
                'error' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    private function fetchAktivsData()
    {
        $cacheKey = 'aktivs_data';

        try {
            // Check cache first
            if (Cache::has($cacheKey)) {
                $cached = Cache::get($cacheKey);
                $cached['source'] = 'cache';
                return $cached;
            }

            // Method 1: Try to call the API controller directly (most efficient)
            try {
                // Try different possible controller paths
                $possibleControllers = [
                    \App\Http\Controllers\Api\AktivsController::class,
                    \App\Http\Controllers\AktivsController::class,
                    \App\Http\Controllers\API\AktivsController::class,
                ];

                foreach ($possibleControllers as $controllerClass) {
                    if (class_exists($controllerClass)) {
                        $apiController = app($controllerClass);
                        if (method_exists($apiController, 'index')) {
                            $apiResponse = $apiController->index();

                            // Handle different response types
                            if (is_object($apiResponse) && method_exists($apiResponse, 'getData')) {
                                $responseData = $apiResponse->getData(true);
                            } elseif (is_array($apiResponse)) {
                                $responseData = $apiResponse;
                            } else {
                                continue;
                            }

                            if (isset($responseData['lots'])) {
                                $lots = $responseData['lots'];

                                $result = $this->processAktivsData($lots, 'direct_api');
                                Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_DURATION));
                                return $result;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::info('Direct API call failed: ' . $e->getMessage());
            }

            // Method 2: Try to get data from database directly
            try {
                // Try to get lots from database
                if (class_exists(\App\Models\Lot::class)) {
                    $lots = \App\Models\Lot::all()->toArray();
                    if (!empty($lots)) {
                        $result = $this->processAktivsData($lots, 'database');
                        Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_DURATION));
                        return $result;
                    }
                }
            } catch (\Exception $e) {
                Log::info('Database access failed: ' . $e->getMessage());
            }

            // Method 3: Try making internal HTTP request (fallback)
            try {
                // Create a mock request to simulate the API call
                $request = Request::create('/api/aktivs', 'GET');
                $response = app()->handle($request);

                if ($response->getStatusCode() === 200) {
                    $content = $response->getContent();
                    $data = json_decode($content, true);

                    if (json_last_error() === JSON_ERROR_NONE && isset($data['lots'])) {
                        $lots = $data['lots'];
                        $result = $this->processAktivsData($lots, 'internal_request');
                        Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_DURATION));
                        return $result;
                    }
                }
            } catch (\Exception $e) {
                Log::info('Internal request failed: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('Aktivs Data Error: ' . $e->getMessage());
        }

        return ['status' => 'error', 'total' => 0, 'error' => 'Failed to fetch aktivs data - API not accessible'];
    }

    private function processAktivsData($lots, $source = 'unknown')
    {
        return [
            'total' => count($lots),
            'with_coordinates' => count(array_filter($lots, function($lot) {
                return isset($lot['lat']) && isset($lot['lng']) &&
                       !empty($lot['lat']) && !empty($lot['lng']);
            })),
            'with_images' => count(array_filter($lots, function($lot) {
                return isset($lot['main_image']) && !empty($lot['main_image']);
            })),
            'with_documents' => count(array_filter($lots, function($lot) {
                return isset($lot['documents']) && !empty($lot['documents']);
            })),
            'total_area' => array_sum(array_filter(array_column($lots, 'area_hectare'), 'is_numeric')),
            'investors' => array_values(array_unique(array_filter(array_column($lots, 'investor')))),
            'districts' => array_count_values(array_filter(array_column($lots, 'district_name'))),
            'by_floors' => $this->groupByFloors($lots),
            'avg_area' => count($lots) > 0 ?
                array_sum(array_filter(array_column($lots, 'area_hectare'), 'is_numeric')) / count($lots) : 0,
            'with_kmz' => count(array_filter($lots, function($lot) {
                if (empty($lot['documents'])) return false;
                foreach ($lot['documents'] as $doc) {
                    if (isset($doc['doc_type']) && $doc['doc_type'] === 'kmz-document') {
                        return true;
                    }
                }
                return false;
            })),
            'status' => 'success',
            'last_updated' => now()->format('Y-m-d H:i:s'),
            'source' => $source
        ];
    }

    private function fetchAuctionData()
    {
        $cacheKey = 'auction_data';

        try {
            $result = $this->getFileContents(self::API_ENDPOINTS['auction'], $cacheKey);

            if (isset($result['success']) && $result['success']) {
                $data = $result['data'];
                $lots = $data['lots'] ?? [];

                $processedResult = [
                    'total' => count($lots),
                    'active' => count(array_filter($lots, function($lot) {
                        return isset($lot['lot_status']) &&
                               str_contains($lot['lot_status'], 'Savdoda ishtirok');
                    })),
                    'total_start_price' => array_sum(array_filter(array_column($lots, 'start_price'), 'is_numeric')),
                    'avg_start_price' => count($lots) > 0 ?
                        array_sum(array_filter(array_column($lots, 'start_price'), 'is_numeric')) / count($lots) : 0,
                    'by_region' => array_count_values(array_filter(array_column($lots, 'region'))),
                    'by_area' => array_count_values(array_filter(array_column($lots, 'area'))),
                    'by_property_type' => array_count_values(array_filter(array_column($lots, 'property_category'))),
                    'by_payment_type' => array_count_values(array_filter(array_column($lots, 'payment_type'))),
                    'upcoming_auctions' => $this->getUpcomingAuctions($lots),
                    'total_land_area' => array_sum(array_filter(array_column($lots, 'land_area'), 'is_numeric')),
                    'status' => 'success',
                    'last_updated' => now()->format('Y-m-d H:i:s'),
                    'source' => $result['source'] ?? 'unknown'
                ];

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }
        } catch (\Exception $e) {
            Log::error('Auction API Error: ' . $e->getMessage());
        }

        return ['status' => 'error', 'total' => 0, 'error' => 'Failed to fetch auction data'];
    }

    private function fetchSoldData()
    {
        $cacheKey = 'sold_data';

        try {
            $result = $this->getFileContents(self::API_ENDPOINTS['sold'], $cacheKey);

            if (isset($result['success']) && $result['success']) {
                $data = $result['data'];
                $lots = $data['lots'] ?? [];

                $soldPrices = array_filter(array_column($lots, 'sold_price'), 'is_numeric');
                $startPrices = array_filter(array_column($lots, 'start_price'), 'is_numeric');

                $processedResult = [
                    'total' => count($lots),
                    'total_sold_amount' => array_sum($soldPrices),
                    'total_start_amount' => array_sum($startPrices),
                    'total_profit' => array_sum($soldPrices) - array_sum($startPrices),
                    'avg_sold_price' => count($soldPrices) > 0 ? array_sum($soldPrices) / count($soldPrices) : 0,
                    'avg_profit_margin' => array_sum($startPrices) > 0 ?
                        ((array_sum($soldPrices) - array_sum($startPrices)) / array_sum($startPrices)) * 100 : 0,
                    'winners' => array_count_values(array_filter(array_column($lots, 'winner_name'))),
                    'by_payment_type' => array_count_values(array_filter(array_column($lots, 'payment_type'))),
                    'by_zone' => array_count_values(array_filter(array_column($lots, 'zone'))),
                    'by_building_type' => array_count_values(array_filter(array_column($lots, 'building_type_comment'))),
                    'monthly_sales' => $this->groupSalesByMonth($lots),
                    'total_land_area' => array_sum(array_filter(array_column($lots, 'land_area'), 'is_numeric')),
                    'status' => 'success',
                    'last_updated' => now()->format('Y-m-d H:i:s'),
                    'source' => $result['source'] ?? 'unknown'
                ];

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }
        } catch (\Exception $e) {
            Log::error('Sold API Error: ' . $e->getMessage());
        }

        return ['status' => 'error', 'total' => 0, 'error' => 'Failed to fetch sold data'];
    }

    private function fetchJsonData()
    {
        $cacheKey = 'json_data';

        try {
            $result = $this->getFileContents(self::API_ENDPOINTS['jsonData'], $cacheKey);

            if (isset($result['success']) && $result['success']) {
                $data = $result['data'];

                $processedResult = [
                    'total' => count($data),
                    'by_district' => array_count_values(array_filter(array_column($data, 'Туман'))),
                    'by_type' => array_count_values(array_filter(array_column($data, 'Таклиф_тури_(Реновация,_Инвестиция,_Аукцион)'))),
                    'by_master_plan_status' => array_count_values(array_filter(array_column($data, 'Таклиф_Бош_режага_таклиф_киритилганлиги_(таклиф_берилган_лекин_киритилмаган_бўлас_КИРИТИЛМАГАН_хисобланади)'))),
                    'by_activity_type' => array_count_values(array_filter(array_column($data, 'Таклиф_Фаолият_тури'))),
                    'total_area' => array_sum(array_filter(array_column($data, 'Таклиф_Ер_майдони_(га)'), 'is_numeric')),
                    'total_population' => array_sum(array_filter(array_column($data, 'Ахоли_сони'), 'is_numeric')),
                    'total_apartments' => array_sum(array_filter(array_column($data, 'хонадонлар_сони'), 'is_numeric')),
                    'total_building_area' => array_sum(array_filter(array_column($data, 'Бинонинг_умумий_майдони'), 'is_numeric')),
                    'avg_floors' => count($data) > 0 ?
                        array_sum(array_filter(array_column($data, 'Этажность'), 'is_numeric')) / count($data) : 0,
                    'by_floor_range' => $this->groupJsonByFloors($data),
                    'status' => 'success',
                    'last_updated' => now()->format('Y-m-d H:i:s'),
                    'source' => $result['source'] ?? 'unknown'
                ];

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }
        } catch (\Exception $e) {
            Log::error('JSON Data API Error: ' . $e->getMessage());
        }

        return ['status' => 'error', 'total' => 0, 'error' => 'Failed to fetch JSON data'];
    }

    private function fetchGeoJsonData()
    {
        $cacheKey = 'geojson_data';

        try {
            $result = $this->getFileContents(self::API_ENDPOINTS['geoJsonStrategy'], $cacheKey);

            if (isset($result['success']) && $result['success']) {
                $data = $result['data'];
                $features = $data['features'] ?? [];
                $properties = array_column($features, 'properties');

                $processedResult = [
                    'total_features' => count($features),
                    'by_strategy' => array_count_values(array_filter(array_column($properties, 'strategiya'))),
                    'by_function' => array_count_values(array_filter(array_column($properties, 'funksiya'))),
                    'by_district' => array_count_values(array_filter(array_column($properties, 'district'))),
                    'red_lines' => count(array_filter($features, function($f) {
                        return isset($f['properties']['funksiya']) &&
                               $f['properties']['funksiya'] === 'Qizil_chiziqlar';
                    })),
                    'by_region' => array_count_values(array_filter(array_column($properties, 'region_name'))),
                    'by_strategy_work_type' => array_count_values(array_filter(array_column($properties, 'strategiya_ishlari_turi'))),
                    'total_area' => array_sum(array_filter(array_column($properties, 'maydoni'), 'is_numeric')),
                    'avg_floors' => count($properties) > 0 ?
                        array_sum(array_filter(array_column($properties, 'qavatlilik'), 'is_numeric')) / count($properties) : 0,
                    'by_seismic_zone' => array_count_values(array_filter(array_column($properties, 'seysmologik_zonasi'))),
                    'status' => 'success',
                    'last_updated' => now()->format('Y-m-d H:i:s'),
                    'source' => $result['source'] ?? 'unknown'
                ];

                Cache::put($cacheKey, $processedResult, now()->addMinutes(self::CACHE_DURATION));
                return $processedResult;
            }
        } catch (\Exception $e) {
            Log::error('GeoJSON API Error: ' . $e->getMessage());
        }

        return ['status' => 'error', 'total' => 0, 'error' => 'Failed to fetch GeoJSON data'];
    }

    private function calculateSummary($data)
    {
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
    }

    private function groupByFloors($lots)
    {
        $grouped = [];
        foreach ($lots as $lot) {
            $floors = $lot['proposed_floors'] ?? $lot['designated_floors'] ?? 0;
            if (is_numeric($floors)) {
                $range = $this->getFloorRange((int)$floors);
                $grouped[$range] = ($grouped[$range] ?? 0) + 1;
            }
        }
        return $grouped;
    }

    private function groupJsonByFloors($data)
    {
        $grouped = [];
        foreach ($data as $item) {
            $floors = $item['Этажность'] ?? 0;
            if (is_numeric($floors)) {
                $range = $this->getFloorRange((int)$floors);
                $grouped[$range] = ($grouped[$range] ?? 0) + 1;
            }
        }
        return $grouped;
    }

    private function getFloorRange($floors)
    {
        if ($floors <= 5) return '1-5 қават';
        if ($floors <= 10) return '6-10 қават';
        if ($floors <= 15) return '11-15 қават';
        return '15+ қават';
    }

    private function getUpcomingAuctions($lots)
    {
        return array_filter($lots, function($lot) {
            if (empty($lot['auction_date'])) return false;
            try {
                $auctionDate = \Carbon\Carbon::parse($lot['auction_date']);
                return $auctionDate->isFuture();
            } catch (\Exception $e) {
                return false;
            }
        });
    }

    private function groupSalesByMonth($lots)
    {
        $grouped = [];
        foreach ($lots as $lot) {
            if (!empty($lot['auction_date'])) {
                try {
                    $month = \Carbon\Carbon::parse($lot['auction_date'])->format('Y-m');
                    $grouped[$month] = ($grouped[$month] ?? 0) + 1;
                } catch (\Exception $e) {
                    // Skip invalid dates
                }
            }
        }
        ksort($grouped);
        return $grouped;
    }

    public function apiStatus()
    {
        $status = [];

        foreach (self::API_ENDPOINTS as $name => $endpoint) {
            $start = microtime(true);

            try {
                if (str_starts_with($endpoint, '/')) {
                    // For local endpoints, check file existence
                    $localPath = public_path(ltrim($endpoint, '/'));
                    if (file_exists($localPath)) {
                        $status[$name] = [
                            'status' => 'online',
                            'response_time' => '< 1ms',
                            'http_code' => 200,
                            'url' => url($endpoint),
                            'source' => 'local_file',
                            'last_check' => now()->format('Y-m-d H:i:s'),
                        ];
                    } else {
                        $status[$name] = [
                            'status' => 'error',
                            'response_time' => '< 1ms',
                            'http_code' => 404,
                            'url' => url($endpoint),
                            'error' => 'File not found',
                            'source' => 'local_file',
                            'last_check' => now()->format('Y-m-d H:i:s'),
                        ];
                    }
                } else {
                    // For external endpoints, use HTTP
                    $result = $this->makeHttpRequest($endpoint, 5);
                    $time = round((microtime(true) - $start) * 1000, 2);

                    $status[$name] = [
                        'status' => $result['success'] ? 'online' : 'error',
                        'response_time' => $time . 'ms',
                        'http_code' => $result['status_code'] ?? 0,
                        'url' => $endpoint,
                        'error' => $result['error'] ?? null,
                        'source' => 'http',
                        'last_check' => now()->format('Y-m-d H:i:s'),
                    ];
                }
            } catch (\Exception $e) {
                $status[$name] = [
                    'status' => 'offline',
                    'error' => $e->getMessage(),
                    'url' => $endpoint,
                    'last_check' => now()->format('Y-m-d H:i:s'),
                ];
            }
        }

        return response()->json($status);
    }

    public function refresh()
    {
        // Clear all monitoring caches
        Cache::forget('aktivs_data');
        Cache::forget('auction_data');
        Cache::forget('sold_data');
        Cache::forget('json_data');
        Cache::forget('geojson_data');

        return redirect()->route('monitoring.dashboard')
                        ->with('success', 'Маълумотлар кэши тозаланди ва янгиланди');
    }

    /**
     * Individual refresh methods for AJAX calls
     */
    public function refreshAktivs()
    {
        Cache::forget('aktivs_data');
        $data = $this->fetchAktivsData();
        return response()->json($data);
    }

    public function refreshAuctions()
    {
        Cache::forget('auction_data');
        $data = $this->fetchAuctionData();
        return response()->json($data);
    }

    public function refreshSold()
    {
        Cache::forget('sold_data');
        $data = $this->fetchSoldData();
        return response()->json($data);
    }

    public function refreshGIS()
    {
        Cache::forget('geojson_data');
        $data = $this->fetchGeoJsonData();
        return response()->json($data);
    }

    public function clearCache()
    {
        try {
            // Clear all monitoring caches
            Cache::forget('aktivs_data');
            Cache::forget('auction_data');
            Cache::forget('sold_data');
            Cache::forget('json_data');
            Cache::forget('geojson_data');

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

    public function getChartData($type)
    {
        try {
            switch ($type) {
                case 'district':
                    $aktivs = $this->fetchAktivsData();
                    return response()->json([
                        'labels' => array_keys($aktivs['districts'] ?? []),
                        'values' => array_values($aktivs['districts'] ?? [])
                    ]);

                case 'investment':
                    $jsonData = $this->fetchJsonData();
                    return response()->json([
                        'labels' => array_keys($jsonData['by_type'] ?? []),
                        'values' => array_values($jsonData['by_type'] ?? [])
                    ]);

                case 'timeline':
                    $sold = $this->fetchSoldData();
                    return response()->json([
                        'labels' => array_keys($sold['monthly_sales'] ?? []),
                        'values' => array_values($sold['monthly_sales'] ?? [])
                    ]);

                default:
                    return response()->json(['error' => 'Invalid chart type'], 400);
            }
        } catch (\Exception $e) {
            Log::error("Chart data error for {$type}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch chart data'], 500);
        }
    }

    public function exportReport()
    {
        try {
            // For now, return a simple JSON report
            // In production, you might want to generate a PDF using dompdf or similar
            $data = [
                'aktivs' => $this->fetchAktivsData(),
                'auctions' => $this->fetchAuctionData(),
                'sold' => $this->fetchSoldData(),
                'jsonData' => $this->fetchJsonData(),
                'geoJsonStrategy' => $this->fetchGeoJsonData(),
            ];

            $data['summary'] = $this->calculateSummary($data);
            $data['generated_at'] = now()->format('Y-m-d H:i:s');

            $filename = 'monitoring-report-' . now()->format('Y-m-d-H-i-s') . '.json';

            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            Log::error('Export report error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate report'], 500);
        }
    }
}
