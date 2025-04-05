<?php

namespace App\Http\Controllers;

use App\Services\MspdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;


class MarkerController extends Controller
{

    public function getApiKey()
    {
        return response()->json([
            'key' => env('GOOGLE_MAPS_API_KEY')
        ]);
    }
    public function maps()
    {
        // dd('dw');
        $apiName = env('API_NAME'); // Original API name
        return view('pages.maps', compact('apiName')); // Pass it directly to the view
    }

    public function index()
    {

        $markers = [
            [
                'position' => ['lat' => 41.311, 'lng' => 69.279],
                'title' => 'Marker 1',
                'info' => '<div class="info-content">
                    <h4 class="custom_sidebar_title"><b>Bino sotish joyi / № 212313</b></h4>
                    <table>
                        <tr>
                            <th class="sidebar_key">Qurilgan yili</th>
                            <td>Noma\'lum</td>
                        </tr>
                        <tr>
                            <th class="sidebar_key">Yer Maydoni</th>
                            <td>Noma\'lum</td>
                        </tr>
                        <tr>
                            <th class="sidebar_key">Bino Maydoni</th>
                            <td>Noma\'lum</td>
                        </tr>
                        <tr>
                            <th class="sidebar_key">Boshlang‘ich narxi</th>
                            <td>804 395 200.00 UZS</td>
                        </tr>
                        <tr>
                            <th class="sidebar_key">Uchastkaning joylashgan manzili</th>
                            <td>Toshkent sh., Yunusobod tumani, Oqtepa MFY</td>
                        </tr>
                        <tr>
                            <th class="sidebar_key">Savdo vaqti</th>
                            <td>15.08.2024 10:00</td>
                        </tr>
                    </table>
                    <a target="_blank" href="https://e-auksion.uz/lot-view?lot_id=10463423" class="btn-link">Batafsil</a>
                </div>
                ',
                'image' => '<img class="custom_sidebar_image" src="https://files.e-auksion.uz/files-worker/api/v1/images?file_hash=79b5e1ad94bb8985f01380627f8895a9e1c6ceae" alt="Marker Image"/>'
            ],

        ];

        return response()->json($markers);
    }

    public function getLots()
    {
        // Path to the file containing lot data
        $filePath = public_path('assets/data.txt');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Read file contents
            $fileContents = File::get($filePath);
            $fileData = json_decode($fileContents, true);

            // Check for JSON decoding errors and the existence of 'lots' key
            if (json_last_error() === JSON_ERROR_NONE && isset($fileData['lots'])) {
                // Filter the lots where 'property_group' is "Земельные участки" or "Yer uchastkalari"
                $filteredLots = array_filter($fileData['lots'], function ($lot) {
                    return in_array($lot['property_group'], ['Земельные участки', 'Yer uchastkalari']) && $lot['lot_number'] !== '10666575';
                });

                // If lots are found, return them
                if (!empty($filteredLots)) {
                    return response()->json(['lots' => array_values($filteredLots)]);
                } else {
                    // Return a 404 response if no lots are found
                    return response()->json(['message' => 'No lots found for the specified property group'], 404);
                }
            } else {
                Log::error('Error decoding file data.');
                return response()->json(['error' => 'Error decoding file data'], 500);
            }
        } else {
            Log::error('File not found: ' . $filePath);
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    // service which is msdp
    // protected $mspdService;

    // public function __construct(MspdService $mspdService)
    // {
    //     $this->mspdService = $mspdService;
    // }

    // public function sendData()
    // {
    //     $data = 'example data';
    //     $this->mspdService->sendData($data);
    //     return 'Data sent!';
    // }

    // public function receiveData()
    // {
    //     $data = $this->mspdService->receiveData();
    //     return response()->json(['data' => $data]);
    // }

    // public function closeConnection()
    // {
    //     $this->mspdService->close();
    //     return 'Connection closed!';
    // }
}
