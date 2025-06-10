<?php

namespace Database\Seeders;

use App\Models\Aktiv;
use App\Models\AktivDoc;
use App\Models\PolygonAktiv;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class RenovationProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks before truncating
        Schema::disableForeignKeyConstraints();

        // Clear all existing projects
        DB::table('polygon_aktivs')->truncate();
        DB::table('aktivs')->truncate();
        DB::table('aktiv_docs')->truncate(); // Also clear the docs table

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Set the path to the Excel file
        $path = public_path('assets/data/renovation_upgrade.xlsx');

        // Set the path to the PDF directory
        $pdfDirectoryPath = public_path('assets/data/RENOVATSIYA ISXOD PDF/');

        // Set the path to the KMZ directory
        $kmzDirectoryPath = public_path('assets/data/BASA_RENOVA/');

        // Cache all PDF files in the directory for faster matching
        $pdfFiles = $this->scanPdfDirectory($pdfDirectoryPath);

        // Cache all KMZ files in the directory for faster matching
        $kmzFiles = $this->scanKmzDirectory($kmzDirectoryPath);

        $this->command->info("Found " . count($pdfFiles) . " PDF files in the directory.");
        $this->command->info("Found " . count($kmzFiles) . " KMZ files in the directory.");
        $this->command->info("Importing Excel file from: $path");

        try {
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();

            // Get the highest row number
            $highestRow = $worksheet->getHighestRow();

            $this->command->info("Found $highestRow rows in the Excel file.");
            $this->command->info("Starting import process...");

            // Progress bar
            $bar = $this->command->getOutput()->createProgressBar($highestRow - 1);
            $bar->start();

            // Skip the header row
            $projectsImported = 0;
            $docsLinked = 0;
            $kmzLinked = 0;

            // Process each row
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    // Log the row number
                    Log::info("Processing row $row --------------------------------- ");

                    // Extract each cell value by column index
                    $district_name = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue() ?? '');
                    $neighborhood_name = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $area_hectare = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $single_house_count = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $single_house_area = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $multi_story_house_count = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $multi_story_house_area = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $non_residential_count = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $non_residential_building_area = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $umn_coefficient = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $qmn_percentage = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $designated_floors = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $proposed_floors = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $decision_number = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $cadastre_certificate = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $area_passport = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $investor = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $status = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $protocol_number = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $land_assessment = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $assessment_status = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $assessment_end = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $investment_contract = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $public_discussion = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $resettlement_start = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $resettlement_end = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $project_start = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $project_status = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $announcement = $worksheet->getCellByColumnAndRow(30, $row)->getValue();

                    // Coordinates
                    $start_lat_raw = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $start_lon_raw = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $end_lat_raw = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $end_lon_raw = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $zone = $worksheet->getCellByColumnAndRow(35, $row)->getValue();

                    // Additional fields that may exist
                    $additional_information = $worksheet->getCellByColumnAndRow(36, $row)->getValue() ?? null;
                    $object_information = null; // This seems to be missing in your sample Excel, set to null

                    // Calculate total building area from residential + non-residential
                    $residential_area = $this->parseNumeric($multi_story_house_area);
                    $non_residential_area = $this->parseNumeric($non_residential_building_area);
                    $total_building_area = null;

                    if ($residential_area !== null || $non_residential_area !== null) {
                        $total_building_area = ($residential_area ?? 0) + ($non_residential_area ?? 0);
                    }

                    // Calculate adjacent area - seems to be missing in your sample data
                    $adjacent_area = null;

                    // Skip empty rows
                    if (empty($district_name)) {
                        $bar->advance();
                        continue;
                    }

                    // Process the neighborhood name to remove HTML formatting and truncate if necessary
                    if ($neighborhood_name) {
                        $neighborhood_name = strip_tags($neighborhood_name);
                        // Limit neighborhood_name to 255 characters (typical VARCHAR size)
                        $neighborhood_name = substr($neighborhood_name, 0, 255);
                    }

                    // Split the coordinates into arrays
                    $startLatCoordinates = $this->splitCoordinates($start_lat_raw);
                    $startLonCoordinates = $this->splitCoordinates($start_lon_raw);
                    $endLatCoordinates = $this->splitCoordinates($end_lat_raw);
                    $endLonCoordinates = $this->splitCoordinates($end_lon_raw);

                    // Get the count of valid coordinates
                    $count = min(
                        count($startLatCoordinates),
                        count($startLonCoordinates),
                        count($endLatCoordinates),
                        count($endLonCoordinates)
                    );

                    // Use first coordinate for Aktiv model latitude and longitude
                    $latDms = $count > 0 ? $startLatCoordinates[0] : '';
                    $lonDms = $count > 0 ? $startLonCoordinates[0] : '';

                    // Convert to decimal only to store in the database
                    $latDecimal = $this->dmsToDecimal($latDms);
                    $lonDecimal = $this->dmsToDecimal($lonDms);

                    // Format dates - get correct parsing for Excel date values
                    $resettlement_start_date = $this->parseDate($resettlement_start);
                    $resettlement_end_date = $this->parseDate($resettlement_end);
                    $project_start_date = $this->parseDate($project_start);

                    // Map status values to make them consistent
                    $mapped_status = $this->mapStatus($status);

                    // Create the main record
                    $aktiv = Aktiv::create([
                        'district_name' => substr($district_name, 0, 255),
                        'neighborhood_name' => $neighborhood_name,
                        'lat' => $latDecimal,  // For map display
                        'lng' => $lonDecimal,  // For map display
                        'latitude' => $latDecimal, // For existing code compatibility
                        'longitude' => $lonDecimal, // For existing code compatibility
                        'area_hectare' => $this->parseNumeric($area_hectare),
                        'total_building_area' => $total_building_area,
                        'residential_area' => $residential_area,
                        'non_residential_area' => $non_residential_area,
                        'adjacent_area' => $this->parseNumeric($adjacent_area),
                        'object_information' => $object_information,
                        'umn_coefficient' => $umn_coefficient,
                        'qmn_percentage' => $qmn_percentage,
                        'designated_floors' => $designated_floors,
                        'proposed_floors' => $proposed_floors,
                        'decision_number' => $decision_number,
                        'cadastre_certificate' => $cadastre_certificate,
                        'area_strategy' => $investment_contract,  // Seems to be similar to investment_contract
                        'investor' => $investor,
                        'status' => $mapped_status,
                        'population' => null,  // Not provided in the Excel sample
                        'household_count' => null,  // Not provided in the Excel sample
                        'additional_information' => $additional_information,

                        // Additional fields
                        'single_house_count' => $this->parseNumeric($single_house_count),
                        'single_house_area' => $this->parseNumeric($single_house_area),
                        'multi_story_house_count' => $this->parseNumeric($multi_story_house_count),
                        'multi_story_house_area' => $this->parseNumeric($multi_story_house_area),
                        'non_residential_count' => $this->parseNumeric($non_residential_count),
                        'non_residential_building_area' => $this->parseNumeric($non_residential_building_area),
                        'area_passport' => $area_passport,
                        'protocol_number' => $protocol_number,
                        'land_assessment' => $land_assessment,
                        'investment_contract' => $investment_contract,
                        'public_discussion' => $public_discussion,
                        'resettlement_start' => $resettlement_start_date,
                        'resettlement_end' => $resettlement_end_date,
                        'project_start' => $project_start_date,
                        'assessment_status' => $assessment_status,
                        'announcement' => $announcement,
                        'zone' => $zone,
                    ]);

                    // Create complete polygon records with start and end points
                    $comment = "{$district_name} тумани, {$neighborhood_name} " . ($area_hectare ? $area_hectare . " гектар" : "");
                    $this->createCompletePolygonRecords($aktiv->id, $start_lat_raw, $start_lon_raw, $end_lat_raw, $end_lon_raw, $comment);

                    // Match and link PDF files with the neighborhood name
                    if ($neighborhood_name) {
                        $linkedDocs = $this->linkPdfDocumentsToAktiv($aktiv->id, $neighborhood_name, $pdfFiles, $area_hectare);
                        $docsLinked += $linkedDocs;

                        // Pass the row number (Т/р) for better KMZ matching
                        $rowNumber = $row - 1; // Subtract 1 because we start from row 2
                        $linkedKmz = $this->linkKmzDocumentsToAktiv($aktiv->id, $neighborhood_name, $kmzFiles, $investor, $area_hectare, $rowNumber);
                        $kmzLinked += $linkedKmz;
                    }

                    $projectsImported++;
                } catch (\Exception $e) {
                    // Log the error but continue with the next row
                    $this->command->error("Error processing row $row: " . $e->getMessage());
                    Log::error("Error processing row $row: " . $e->getMessage());
                    Log::error($e->getTraceAsString());
                }

                $bar->advance();
            }

            $bar->finish();
            $this->command->newLine(2);
            $this->command->info("Import completed. $projectsImported projects imported successfully.");
            $this->command->info("$docsLinked PDF documents linked to projects.");
            $this->command->info("$kmzLinked KMZ documents linked to projects.");
        } catch (\Exception $e) {
            Log::error("Error importing Excel file: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            $this->command->error("Error importing Excel file: " . $e->getMessage());
        }
    }

    /**
     * Map status values to standardized format
     */
    private function mapStatus($status)
    {
        if (empty($status)) {
            return null;
        }

        $status = trim(strtolower($status));

        // Map Uzbek status values
        if (strpos($status, 'инвест') !== false || strpos($status, 'договор') !== false) {
            return "9"; // Investment contract
        } elseif (strpos($status, 'ишлаб') !== false || strpos($status, 'чиқилмоқда') !== false) {
            return "1"; // In development
        } elseif (strpos($status, 'қурилиш') !== false || strpos($status, 'жараёнида') !== false) {
            return "2"; // Under construction
        }

        // Return as-is if no mapping found
        return $status;
    }

    /**
     * Parse date value from various formats
     */
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // If it's a numeric value, treat as Excel date
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                    ->format('Y-m-d');
            }

            // If it's a string that starts with "до", extract the date part
            if (is_string($value) && strpos($value, 'до') === 0) {
                $datePart = trim(substr($value, 2)); // Remove the "до " prefix
                return date('Y-m-d', strtotime($datePart));
            }

            // For other string formats
            return date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            Log::warning("Failed to parse date: $value");
            return null;
        }
    }

    /**
     * Scan the PDF directory and return all PDF files
     */
    private function scanPdfDirectory($directory)
    {
        $pdfFiles = [];

        try {
            if (!file_exists($directory)) {
                Log::error("PDF directory does not exist: $directory");
                return $pdfFiles;
            }

            $files = File::files($directory);

            foreach ($files as $file) {
                if (strtolower($file->getExtension()) === 'pdf') {
                    $filename = $file->getFilename();

                    // Extract the neighborhood name from the filename
                    // Format: "1.Кашгар (0,12 га).pdf"
                    if (preg_match('/^\d+\.(.+?)(?:\s*\([\d,\.\s-]+\s*га\))?\.pdf$/i', $filename, $matches)) {
                        $nameInFile = trim($matches[1]);
                        $pdfFiles[] = [
                            'full_path' => $file->getPathname(),
                            'filename' => $filename,
                            'neighborhood_name' => $nameInFile,
                            'original_path' => str_replace('\\', '/', $file->getPathname()),
                            'order_number' => $this->extractOrderNumber($filename)
                        ];
                    } else {
                        // If no match, still add the file with the raw filename as neighborhood name
                        $pdfFiles[] = [
                            'full_path' => $file->getPathname(),
                            'filename' => $filename,
                            'neighborhood_name' => pathinfo($filename, PATHINFO_FILENAME),
                            'original_path' => str_replace('\\', '/', $file->getPathname()),
                            'order_number' => $this->extractOrderNumber($filename)
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Error scanning PDF directory: " . $e->getMessage());
        }

        return $pdfFiles;
    }

    /**
     * Scan the KMZ directory and return all KMZ files with improved pattern matching
     */
    private function scanKmzDirectory($directory)
    {
        $kmzFiles = [];

        try {
            if (!file_exists($directory)) {
                Log::error("KMZ directory does not exist: $directory");
                return $kmzFiles;
            }

            $files = File::files($directory);

            foreach ($files as $file) {
                if (strtolower($file->getExtension()) === 'kmz') {
                    $filename = $file->getFilename();

                    // Updated regex patterns for different filename formats
                    $patterns = [
                        // Pattern 1: "1.Кашгар_0,12 га.kmz" (number.name_area format)
                        '/^\d+\.(.+?)_([0-9,.]+)\s*га\.kmz$/i' => function ($matches) {
                            return [
                                'neighborhood_name' => trim($matches[1]),
                                'investor' => null,
                                'area' => str_replace(',', '.', $matches[2])
                            ];
                        },

                        // Pattern 2: "2.Катта Козиробод-1_1,52 га.kmz" (number.name-suffix_area format)
                        '/^\d+\.(.+?)-(\d+)_([0-9,.]+)\s*га\.kmz$/i' => function ($matches) {
                            return [
                                'neighborhood_name' => trim($matches[1]) . '-' . $matches[2],
                                'investor' => null,
                                'area' => str_replace(',', '.', $matches[3])
                            ];
                        },

                        // Pattern 3: With МФЙ prefix: "1.МФЙ Кашгар_0,12 га.kmz"
                        '/^\d+\.МФЙ\s+(.+?)_([0-9,.]+)\s*га\.kmz$/i' => function ($matches) {
                            return [
                                'neighborhood_name' => trim($matches[1]),
                                'investor' => null,
                                'area' => str_replace(',', '.', $matches[2])
                            ];
                        },

                        // Pattern 4: With investor: "3.Катта Козиробод_Nur Hayat New Classic_2,13 га.kmz"
                        '/^\d+\.(.+?)_([^_]+)_([0-9,.]+)\s*га\.kmz$/i' => function ($matches) {
                            return [
                                'neighborhood_name' => trim($matches[1]),
                                'investor' => trim($matches[2]),
                                'area' => str_replace(',', '.', $matches[3])
                            ];
                        },

                        // Pattern 5: Simple format with parentheses: "Name (area га).kmz"
                        '/^(.+?)\s*\(([0-9,.]+)\s*га\)\.kmz$/i' => function ($matches) {
                            return [
                                'neighborhood_name' => trim($matches[1]),
                                'investor' => null,
                                'area' => str_replace(',', '.', $matches[2])
                            ];
                        },

                        // Pattern 6: Just extract everything before .kmz as fallback
                        '/^(.+)\.kmz$/i' => function ($matches) {
                            // Remove leading numbers and dots if present
                            $name = preg_replace('/^\d+\./', '', trim($matches[1]));
                            return [
                                'neighborhood_name' => trim($name),
                                'investor' => null,
                                'area' => null
                            ];
                        }
                    ];

                    $fileInfo = null;

                    // Try each pattern until we find a match
                    foreach ($patterns as $pattern => $processor) {
                        if (preg_match($pattern, $filename, $matches)) {
                            $fileInfo = $processor($matches);
                            Log::info("KMZ Pattern matched for '$filename': " . json_encode($fileInfo));
                            break;
                        }
                    }

                    // If no pattern matched, use a default extraction
                    if (!$fileInfo) {
                        $fileInfo = [
                            'neighborhood_name' => pathinfo($filename, PATHINFO_FILENAME),
                            'investor' => null,
                            'area' => null
                        ];
                        Log::warning("No KMZ pattern matched for '$filename', using filename as neighborhood name");
                    }

                    $kmzFiles[] = [
                        'full_path' => $file->getPathname(),
                        'filename' => $filename,
                        'neighborhood_name' => $fileInfo['neighborhood_name'],
                        'investor' => $fileInfo['investor'],
                        'area' => $fileInfo['area'],
                        'original_path' => str_replace('\\', '/', $file->getPathname()),
                        'order_number' => $this->extractOrderNumber($filename)
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Error scanning KMZ directory: " . $e->getMessage());
        }

        return $kmzFiles;
    }

    /**
     * Extract order number from filename (the number before the dot)
     */
    private function extractOrderNumber($filename)
    {
        if (preg_match('/^(\d+)\./', $filename, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }

    /**
     * Link PDF documents to an Aktiv record based on neighborhood name and order number
     */
    private function linkPdfDocumentsToAktiv($aktivId, $neighborhoodName, $pdfFiles, $areaHectare = null, $rowNumber = null)
    {
        $linkedDocs = 0;
        $neighborhoodName = trim($neighborhoodName);

        // Skip if neighborhood name is empty
        if (empty($neighborhoodName)) {
            return 0;
        }

        // Prepare area string for matching if available
        $areaString = '';
        if ($areaHectare) {
            // Convert area to string with comma as decimal separator for matching with filenames
            $areaString = str_replace('.', ',', (string)$areaHectare);
        }

        // Log info for debugging
        Log::info("Matching PDF for Aktiv ID: $aktivId, Row: $rowNumber, Neighborhood: $neighborhoodName, Area: $areaString");

        // Extract neighborhood name parts for better matching
        $cleanNeighborhoodName = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $neighborhoodName);
        $neighborhoodParts = array_filter(
            explode(' ', $cleanNeighborhoodName),
            function ($part) {
                return mb_strlen($part) > 2;
            }
        );

        foreach ($pdfFiles as $key => $pdfFile) {
            $match = false;
            $matchReason = '';

            // Clean up the PDF neighborhood name for comparison
            $pdfNeighborhood = trim($pdfFile['neighborhood_name']);

            // Skip if PDF neighborhood name is empty
            if (empty($pdfNeighborhood)) {
                continue;
            }

            // PRIORITY 1: Order number match (Т/р column matching)
            if ($rowNumber && isset($pdfFile['order_number']) && $pdfFile['order_number'] == $rowNumber) {
                $match = true;
                $matchReason = "Order number match (Т/р: $rowNumber)";
            }

            // 2. EXACT MATCH - Case-insensitive exact match with neighborhood name
            if (!$match && mb_strtolower($pdfNeighborhood) === mb_strtolower($neighborhoodName)) {
                $match = true;
                $matchReason = "Exact neighborhood match";
            }

            // 3. NUMBER + NAME MATCH - If filename starts with a number followed by the neighborhood name
            if (!$match && preg_match('/^\d+\.\s*' . preg_quote($neighborhoodName, '/') . '/iu', $pdfFile['filename'])) {
                $match = true;
                $matchReason = "Number + name match";
            }

            // 4. AREA BASED MATCH - Only if area hectare is specified and matches in the filename
            if (!$match && $areaString && stripos($pdfFile['filename'], $neighborhoodName) !== false) {
                // Make sure the area is also in the filename, with proper format checking
                if (preg_match('/\(\s*' . preg_quote($areaString, '/') . '\s*га\)/iu', $pdfFile['filename'])) {
                    $match = true;
                    $matchReason = "Area match";
                }
            }

            // 5. SIGNIFICANT WORD MATCH - If neighborhood has multiple words
            if (!$match && count($neighborhoodParts) > 1) {
                // For multi-word neighborhoods, check if significant words match
                $pdfNameParts = array_filter(
                    explode(' ', preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $pdfNeighborhood)),
                    function ($part) {
                        return mb_strlen($part) > 2;
                    }
                );

                // Count matching words
                $matchingWords = 0;
                $totalSignificantWords = count($neighborhoodParts);

                foreach ($neighborhoodParts as $part) {
                    foreach ($pdfNameParts as $pdfPart) {
                        // Only compare significant word parts
                        if (mb_strlen($part) > 3 && mb_strlen($pdfPart) > 3) {
                            if (mb_strtolower($part) === mb_strtolower($pdfPart)) {
                                $matchingWords++;
                                break;
                            }
                        }
                    }
                }

                // Match if at least 80% of significant words match
                if (
                    $totalSignificantWords > 0 &&
                    $matchingWords >= ceil($totalSignificantWords * 0.8)
                ) {
                    $match = true;
                    $matchReason = "Word match ($matchingWords/$totalSignificantWords words)";
                }
            }

            // If we have a match, create the document record
            if ($match) {
                // Store the relative path in the database (easier to use in web context)
                $relativePath = 'assets/data/RENOVATSIYA ISXOD PDF/' . $pdfFile['filename'];

                // Log the successful match
                Log::info("PDF Match found for Aktiv ID: $aktivId, File: {$pdfFile['filename']}, Reason: $matchReason");

                AktivDoc::create([
                    'aktiv_id' => $aktivId,
                    'doc_type' => 'pdf-document',
                    'path' => $relativePath,
                    'filename' => $pdfFile['filename'],  // Store the filename separately
                    'url' => url($relativePath),  // Store the full URL for easy access
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $linkedDocs++;

                // Important: Remove this PDF from the array to prevent it from matching with other records
                // This ensures each PDF is only linked to the most relevant Aktiv record
                unset($pdfFiles[$key]);
                break; // Only match one PDF per aktiv for now
            }
        }

        if ($linkedDocs > 0) {
            Log::info("Linked $linkedDocs PDF documents to Aktiv ID: $aktivId");
        } else {
            Log::warning("No PDF match found for Aktiv ID: $aktivId, Neighborhood: $neighborhoodName");
        }

        return $linkedDocs;
    }

    /**
     * Improved linkKmzDocumentsToAktiv method with better matching logic
     */
    private function linkKmzDocumentsToAktiv($aktivId, $neighborhoodName, $kmzFiles, $investor = null, $areaHectare = null, $rowNumber = null)
    {
        $linkedDocs = 0;
        $neighborhoodName = trim($neighborhoodName);

        // Skip if neighborhood name is empty
        if (empty($neighborhoodName)) {
            return 0;
        }

        // Prepare area string for matching if available
        $areaString = '';
        if ($areaHectare) {
            $areaString = str_replace('.', ',', (string)$areaHectare);
        }

        Log::info("Matching KMZ for Aktiv ID: $aktivId, Row: $rowNumber, Neighborhood: $neighborhoodName, Investor: $investor, Area: $areaString");

        // Extract neighborhood name parts for better matching
        $cleanNeighborhoodName = preg_replace('/[^\p{L}\p{N}\s-]/u', ' ', $neighborhoodName);
        $neighborhoodParts = array_filter(
            explode(' ', $cleanNeighborhoodName),
            function ($part) {
                return mb_strlen(trim($part)) > 1;
            }
        );

        // Clean investor name for matching if it exists
        $cleanInvestorName = null;
        if ($investor && $investor !== '0' && $investor !== 'N/A' && $investor !== '---') {
            $cleanInvestorName = trim(preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $investor));
        }

        foreach ($kmzFiles as $key => $kmzFile) {
            $match = false;
            $matchReason = '';
            $matchScore = 0;

            $kmzNeighborhood = trim($kmzFile['neighborhood_name']);

            // Skip if KMZ neighborhood name is empty
            if (empty($kmzNeighborhood)) {
                continue;
            }

            // PRIORITY 1: Order number match (Т/р column matching)
            if ($rowNumber && $kmzFile['order_number'] && $kmzFile['order_number'] == $rowNumber) {
                $match = true;
                $matchReason = "Order number match (Т/р: $rowNumber)";
                $matchScore = 100;
            }

            // PRIORITY 2: Exact neighborhood match
            if (!$match && mb_strtolower($kmzNeighborhood) === mb_strtolower($neighborhoodName)) {
                $match = true;
                $matchReason = "Exact neighborhood match";
                $matchScore = 90;
            }

            // PRIORITY 3: Neighborhood match with area verification
            if (!$match && $areaString && $kmzFile['area']) {
                // Normalize areas for comparison
                $excelArea = (float)str_replace(',', '.', $areaString);
                $kmzArea = (float)str_replace(',', '.', $kmzFile['area']);

                // Allow for small differences in area (±0.1 hectare)
                if (abs($excelArea - $kmzArea) <= 0.1) {
                    // Check if neighborhood names are similar
                    $similarity = $this->calculateStringSimilarity($neighborhoodName, $kmzNeighborhood);
                    if ($similarity >= 0.6) { // 60% similarity threshold
                        $match = true;
                        $matchReason = "Area + neighborhood similarity match (similarity: " . round($similarity * 100) . "%)";
                        $matchScore = 80;
                    }
                }
            }

            // PRIORITY 4: Investor match (if both have investors)
            if (!$match && $cleanInvestorName && $kmzFile['investor']) {
                if (mb_strtolower($kmzFile['investor']) === mb_strtolower($cleanInvestorName)) {
                    $match = true;
                    $matchReason = "Investor match";
                    $matchScore = 70;
                }
            }

            // PRIORITY 5: Neighborhood word matching
            if (!$match && count($neighborhoodParts) > 0) {
                $kmzNameParts = array_filter(
                    explode(' ', preg_replace('/[^\p{L}\p{N}\s-]/u', ' ', $kmzNeighborhood)),
                    function ($part) {
                        return mb_strlen(trim($part)) > 1;
                    }
                );

                $matchingWords = 0;
                $totalWords = count($neighborhoodParts);

                foreach ($neighborhoodParts as $part) {
                    $part = trim($part);
                    if (mb_strlen($part) <= 1) continue;

                    foreach ($kmzNameParts as $kmzPart) {
                        $kmzPart = trim($kmzPart);
                        if (mb_strlen($kmzPart) <= 1) continue;

                        // Exact word match
                        if (mb_strtolower($part) === mb_strtolower($kmzPart)) {
                            $matchingWords++;
                            break;
                        }

                        // Partial word match for longer words
                        if (mb_strlen($part) > 3 && mb_strlen($kmzPart) > 3) {
                            $similarity = $this->calculateStringSimilarity($part, $kmzPart);
                            if ($similarity >= 0.8) {
                                $matchingWords++;
                                break;
                            }
                        }
                    }
                }

                // Match if at least 70% of words match
                if ($totalWords > 0 && $matchingWords >= ceil($totalWords * 0.7)) {
                    $match = true;
                    $matchReason = "Word similarity match ($matchingWords/$totalWords words)";
                    $matchScore = 60;
                }
            }

            // PRIORITY 6: Filename pattern match (lowest priority)
            if (!$match) {
                if (stripos($kmzFile['filename'], $neighborhoodName) !== false) {
                    $match = true;
                    $matchReason = "Filename contains neighborhood name";
                    $matchScore = 30;
                }
            }

            // If we have a match, create the document record
            if ($match) {
                $relativePath = 'assets/data/BASA_RENOVA/' . $kmzFile['filename'];

                Log::info("KMZ Match found for Aktiv ID: $aktivId, File: {$kmzFile['filename']}, Reason: $matchReason, Score: $matchScore");

                AktivDoc::create([
                    'aktiv_id' => $aktivId,
                    'doc_type' => 'kmz-document',
                    'path' => $relativePath,
                    'filename' => $kmzFile['filename'],
                    'url' => url($relativePath),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $linkedDocs++;

                // Remove this KMZ from the array to prevent duplicate matching
                unset($kmzFiles[$key]);
                break; // Only match one KMZ per aktiv for now
            }
        }

        if ($linkedDocs > 0) {
            Log::info("Linked $linkedDocs KMZ documents to Aktiv ID: $aktivId");
        } else {
            Log::warning("No KMZ match found for Aktiv ID: $aktivId, Neighborhood: $neighborhoodName");
        }

        return $linkedDocs;
    }

    /**
     * Calculate string similarity between two strings
     */
    private function calculateStringSimilarity($str1, $str2)
    {
        $str1 = mb_strtolower(trim($str1));
        $str2 = mb_strtolower(trim($str2));

        if ($str1 === $str2) {
            return 1.0;
        }

        if (empty($str1) || empty($str2)) {
            return 0.0;
        }

        // Use Levenshtein distance for similarity calculation
        $maxLen = max(mb_strlen($str1), mb_strlen($str2));
        if ($maxLen == 0) {
            return 1.0;
        }

        $distance = levenshtein($str1, $str2);
        return 1 - ($distance / $maxLen);
    }

    /**
     * Convert DMS (Degrees, Minutes, Seconds) coordinates to decimal
     */
    private function dmsToDecimal($dmsString)
    {
        if (!$dmsString || !is_string($dmsString)) {
            return null;
        }

        // Format: 41°15'26.33"С (Cyrillic С for North)
        $pattern = '/(\d+)°(\d+)\'(\d+(?:\.\d+)?)["\'"]([СЮЗВNEWS])?/';
        $match = preg_match($pattern, $dmsString, $matches);

        if ($match) {
            $degrees = floatval($matches[1]);
            $minutes = floatval($matches[2]) / 60;
            $seconds = floatval($matches[3]) / 3600;

            $decimal = $degrees + $minutes + $seconds;

            // Handle direction if provided
            if (isset($matches[4])) {
                $direction = $matches[4];

                // South or West should be negative
                if ($direction === 'Ю' || $direction === 'S') {
                    $decimal = -$decimal;
                } else if ($direction === 'З' || $direction === 'W') {
                    $decimal = -$decimal;
                }
            }

            return number_format($decimal, 7, '.', '');
        }

        // If it's already a decimal, just return it
        if (is_numeric($dmsString)) {
            return number_format(floatval($dmsString), 7, '.', '');
        }

        return null;
    }

    /**
     * Create polygon records with start and end points for each segment
     */
    private function createCompletePolygonRecords($aktivId, $start_lat_raw, $start_lon_raw, $end_lat_raw, $end_lon_raw, $comment)
    {
        // Split the coordinates into arrays
        $startLatCoordinates = $this->splitCoordinates($start_lat_raw);
        $startLonCoordinates = $this->splitCoordinates($start_lon_raw);
        $endLatCoordinates = $this->splitCoordinates($end_lat_raw);
        $endLonCoordinates = $this->splitCoordinates($end_lon_raw);

        // Get the count of valid coordinates (use the smallest array to avoid index errors)
        $count = min(
            count($startLatCoordinates),
            count($startLonCoordinates),
            count($endLatCoordinates),
            count($endLonCoordinates)
        );

        // Create records connecting consecutive points
        for ($i = 0; $i < $count; $i++) {
            PolygonAktiv::create([
                'aktiv_id' => $aktivId,
                'start_lat' => $startLatCoordinates[$i],
                'start_lon' => $startLonCoordinates[$i],
                'end_lat' => $endLatCoordinates[$i],
                'end_lon' => $endLonCoordinates[$i],
                'distance' => null,
                'comment' => $comment,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // If there are missing closing segments, add them manually
        if ($count > 0 && (
            $endLatCoordinates[$count - 1] != $startLatCoordinates[0] ||
            $endLonCoordinates[$count - 1] != $startLonCoordinates[0]
        )) {
            // Add closing segment connecting last point back to first
            PolygonAktiv::create([
                'aktiv_id' => $aktivId,
                'start_lat' => $endLatCoordinates[$count - 1],
                'start_lon' => $endLonCoordinates[$count - 1],
                'end_lat' => $startLatCoordinates[0],
                'end_lon' => $startLonCoordinates[0],
                'distance' => null,
                'comment' => $comment,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Split coordinates string into an array of coordinates
     */
    private function splitCoordinates($coordinateString)
    {
        if (!is_string($coordinateString)) {
            return [];
        }

        // Split by any combination of newline characters
        $coordinates = preg_split('/\r\n|\r|\n/', $coordinateString);

        // Trim each coordinate and remove empty ones
        return array_filter(array_map('trim', $coordinates), function ($value) {
            return !empty($value);
        });
    }

    /**
     * Parse numeric values, handling various formats
     */
    private function parseNumeric($value)
    {
        if (empty($value) || $value === '---') {
            return null;
        }

        // If the value is a formula or contains non-numeric characters (except comma or dot), return null
        if (is_string($value) && (
            strpos($value, '=') === 0 ||
            preg_match('/[a-zA-Z]/', $value) ||
            $value === '-'
        )) {
            return null;
        }

        // Replace comma with dot for decimal values
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);

            // Remove any non-numeric characters except the decimal point
            $value = preg_replace('/[^0-9.]/', '', $value);
        }

        return is_numeric($value) ? (float)$value : null;
    }
}
?>
