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
        $path = public_path('assets/data/renovation.xlsx');

        // Set the path to the PDF directory
        $pdfDirectoryPath = public_path('assets/data/RENOVATSIYA ISXOD PDF/');

        // $pdfDirectoryPath = 'C:/Users/inves/OneDrive/Ishchi stol/ren.new/public/assets/data/RENOVATSIYA ISXOD PDF/';

        // Cache all PDF files in the directory for faster matching
        $pdfFiles = $this->scanPdfDirectory($pdfDirectoryPath);

        $this->command->info("Found " . count($pdfFiles) . " PDF files in the directory.");
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

            // Process each row
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    // Log the row data
                    Log::info("Processing row $row --------------------------------- ");

                    // Extract and log each cell value
                    $district_name = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue() ?? '');
                    $start_lat_raw = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $start_lon_raw = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $end_lat_raw = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $end_lon_raw = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $neighborhood_name = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $area_hectare = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $total_building_area = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $residential_area = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $non_residential_area = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $adjacent_area = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $object_information = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $umn_coefficient = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $qmn_percentage = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $designated_floors = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $proposed_floors = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $decision_number = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $cadastre_certificate = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $area_strategy = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $investor = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $status = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $population = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $household_count = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $additional_information = $worksheet->getCellByColumnAndRow(33, $row)->getValue();

                    // Log the row data in detail
                    Log::info("Row $row Data: ", [
                        'district_name' => $district_name,
                        'start_lat_raw' => $start_lat_raw,
                        'start_lon_raw' => $start_lon_raw,
                        'end_lat_raw' => $end_lat_raw,
                        'end_lon_raw' => $end_lon_raw,
                        'neighborhood_name' => $neighborhood_name,
                        'area_hectare' => $area_hectare,
                        'total_building_area' => $total_building_area,
                        'residential_area' => $residential_area,
                        'non_residential_area' => $non_residential_area,
                        'adjacent_area' => $adjacent_area,
                        'object_information' => $object_information,
                        'umn_coefficient' => $umn_coefficient,
                        'qmn_percentage' => $qmn_percentage,
                        'designated_floors' => $designated_floors,
                        'proposed_floors' => $proposed_floors,
                        'decision_number' => $decision_number,
                        'cadastre_certificate' => $cadastre_certificate,
                        'area_strategy' => $area_strategy,
                        'investor' => $investor,
                        'status' => $status,
                        'population' => $population,
                        'household_count' => $household_count,
                        'additional_information' => $additional_information,
                    ]);

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

                    // Validate and clean the data before inserting
                    $data = [
                        'district_name' => substr($district_name, 0, 255),
                        'neighborhood_name' => $neighborhood_name,
                        'area_hectare' => $this->parseNumeric($area_hectare),
                        'total_building_area' => $this->parseNumeric($total_building_area),
                        'residential_area' => $this->parseNumeric($residential_area),
                        'non_residential_area' => $this->parseNumeric($non_residential_area),
                        'adjacent_area' => $this->parseNumeric($adjacent_area),
                        'object_information' => $object_information ?? null,
                        'umn_coefficient' => $umn_coefficient ?? null,
                        'qmn_percentage' => $qmn_percentage ?? null,
                        'designated_floors' => $designated_floors ?? null,
                        'proposed_floors' => $proposed_floors ?? null,
                        'decision_number' => $decision_number ?? null,
                        'cadastre_certificate' => $cadastre_certificate ?? null,
                        'area_strategy' => $area_strategy ?? null,
                        'investor' => $investor ?? null,
                        'status' => $status ?? null,
                        'population' => $this->parseNumeric($population),
                        'household_count' => $this->parseNumeric($household_count),
                        'additional_information' => $additional_information ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                        // Use the first point's coordinates exactly as they are in DMS format
                        'latitude' => $latDecimal,
                        'longitude' => $lonDecimal,
                    ];

                    // Create the project
                    $aktiv = Aktiv::create($data);

                    // Create complete polygon records with start and end points
                    $comment = "{$district_name} tuman, {$neighborhood_name} " . ($area_hectare ? $area_hectare . " gektar" : "");
                    $this->createCompletePolygonRecords($aktivId = $aktiv->id, $start_lat_raw, $start_lon_raw, $end_lat_raw, $end_lon_raw, $comment);

                    // Update the Aktiv with the correct decimalized coordinates after polygons are created
                    if (!$latDecimal || !$lonDecimal) {
                        $firstPolygon = PolygonAktiv::where('aktiv_id', $aktiv->id)->first();
                        if ($firstPolygon) {
                            $latDecimal = $this->dmsToDecimal($firstPolygon->start_lat);
                            $lonDecimal = $this->dmsToDecimal($firstPolygon->start_lon);

                            if ($latDecimal && $lonDecimal) {
                                $aktiv->update([
                                    'latitude' => $latDecimal,
                                    'longitude' => $lonDecimal
                                ]);
                            }
                        }
                    }

                    // Match and link PDF files with the neighborhood name
                    if ($neighborhood_name) {
                        $linkedDocs = $this->linkPdfDocumentsToAktiv($aktiv->id, $neighborhood_name, $pdfFiles, $area_hectare);
                        $docsLinked += $linkedDocs;
                    }

                    $projectsImported++;
                } catch (\Exception $e) {
                    // Log the error but continue with the next row
                    $this->command->error("Error processing row $row: " . $e->getMessage());
                    Log::error("Error processing row $row: " . $e->getMessage());
                }

                $bar->advance();
            }

            $bar->finish();
            $this->command->newLine(2);
            $this->command->info("Import completed. $projectsImported projects imported successfully.");
            $this->command->info("$docsLinked PDF documents linked to projects.");
        } catch (\Exception $e) {
            Log::error("Error importing Excel file: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            $this->command->error("Error importing Excel file: " . $e->getMessage());
            $this->command->error($e->getTraceAsString());
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
                            'original_path' => str_replace('\\', '/', $file->getPathname())
                        ];
                    } else {
                        // If no match, still add the file with the raw filename as neighborhood name
                        $pdfFiles[] = [
                            'full_path' => $file->getPathname(),
                            'filename' => $filename,
                            'neighborhood_name' => pathinfo($filename, PATHINFO_FILENAME),
                            'original_path' => str_replace('\\', '/', $file->getPathname())
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
     * Link PDF documents to an Aktiv record based on neighborhood name
     */
    /**
     * Link PDF documents to an Aktiv record based on neighborhood name
     */
    private function linkPdfDocumentsToAktiv($aktivId, $neighborhoodName, $pdfFiles, $areaHectare = null)
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
        Log::info("Matching for Aktiv ID: $aktivId, Neighborhood: $neighborhoodName, Area: $areaString");

        // Extract neighborhood name parts for better matching
        $cleanNeighborhoodName = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $neighborhoodName);
        $neighborhoodParts = array_filter(
            explode(' ', $cleanNeighborhoodName),
            function ($part) {
                return mb_strlen($part) > 2;
            }
        );

        foreach ($pdfFiles as $pdfFile) {
            $match = false;
            $matchReason = '';

            // Clean up the PDF neighborhood name for comparison
            $pdfNeighborhood = trim($pdfFile['neighborhood_name']);

            // Skip if PDF neighborhood name is empty
            if (empty($pdfNeighborhood)) {
                continue;
            }

            // 1. EXACT MATCH - Case-insensitive exact match with neighborhood name
            if (mb_strtolower($pdfNeighborhood) === mb_strtolower($neighborhoodName)) {
                $match = true;
                $matchReason = "Exact match";
            }

            // 2. NUMBER + NAME MATCH - If filename starts with a number followed by the neighborhood name
            if (!$match && preg_match('/^\d+\.\s*' . preg_quote($neighborhoodName, '/') . '/iu', $pdfFile['filename'])) {
                $match = true;
                $matchReason = "Number + name match";
            }

            // 3. AREA BASED MATCH - Only if area hectare is specified and matches in the filename
            if (!$match && $areaString && stripos($pdfFile['filename'], $neighborhoodName) !== false) {
                // Make sure the area is also in the filename, with proper format checking
                if (preg_match('/\(\s*' . preg_quote($areaString, '/') . '\s*га\)/iu', $pdfFile['filename'])) {
                    $match = true;
                    $matchReason = "Area match";
                }
            }

            // 4. SIGNIFICANT WORD MATCH - If neighborhood has multiple words
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
                Log::info("Match found for Aktiv ID: $aktivId, File: {$pdfFile['filename']}, Reason: $matchReason");

                AktivDoc::create([
                    'aktiv_id' => $aktivId,
                    'doc_type' => 'pdf-document',
                    'path' => $relativePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $linkedDocs++;

                // Important: Remove this PDF from the array to prevent it from matching with other records
                // This ensures each PDF is only linked to the most relevant Aktiv record
                unset($pdfFiles[array_search($pdfFile, $pdfFiles)]);
            }
        }

        if ($linkedDocs > 0) {
            Log::info("Linked $linkedDocs documents to Aktiv ID: $aktivId");
        }

        return $linkedDocs;
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
     * Parse numeric values, handling commas as decimal separators
     */
    private function parseNumeric($value)
    {
        if (empty($value)) {
            return null;
        }

        // If the value is a formula or contains non-numeric characters, return null
        if (is_string($value) && (strpos($value, '=') === 0 || preg_match('/[a-zA-Z]/', $value))) {
            return null;
        }

        // Remove any commas used as thousand separators and convert to float
        if (is_string($value)) {
            $value = str_replace(',', '', $value);
        }

        return is_numeric($value) ? (float)$value : null;
    }
}
