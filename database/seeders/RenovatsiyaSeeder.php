<?php

namespace Database\Seeders;

use App\Models\PolygonAktiv;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RenovatsiyaSeeder extends Seeder
{
    public function run()
    {
        // Define the import handler with proper interfaces
        $importHandler = new class implements ToCollection, WithHeadingRow
        {
            public function collection(Collection $rows)
            {
                foreach ($rows as $row) {
                    // Validate and insert the row data
                    $aktiv_id = $row['aktiv_id'] ?? null;
                    $start_lat = $row['start_lat'] ?? null;
                    $start_lon = $row['start_lon'] ?? null;
                    $end_lat = $row['end_lat'] ?? null;
                    $end_lon = $row['end_lon'] ?? null;
                    $distance = $row['distance'] ?? null;
                    $comment = $row['comment'] ?? null;

                    // Save the data into the database
                    PolygonAktiv::create([
                        'aktiv_id' => $aktiv_id,
                        'start_lat' => $start_lat,
                        'start_lon' => $start_lon,
                        'end_lat' => $end_lat,
                        'end_lon' => $end_lon,
                        'distance' => $distance,
                        'comment' => $comment,
                    ]);
                }
            }
        };

        // File path to the Excel file
        $filePath = public_path('ren_polygon_10_jan.xlsx');  // Use storage path instead

        // Execute the import using the import handler
        Excel::import($importHandler, $filePath);

        echo "Data inserted successfully." . PHP_EOL;
    }
}
