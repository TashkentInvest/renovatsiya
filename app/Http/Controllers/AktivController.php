<?php

namespace App\Http\Controllers;

use App\Exports\AktivsExport;
use App\Models\Aktiv;
use App\Models\Districts;
use App\Models\PolygonAktiv;
use App\Models\Regions;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class AktivController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->input('user_id');
        $district_id = $request->input('district_id');  // Fixed typo here
        $userRole = auth()->user()->roles->first()->name;

        // Initialize the query builder for Aktivs
        $query = Aktiv::query();

        // Only Super Admins and Managers can filter by user_id
        if ($userRole == 'Super Admin' || $userRole == 'Manager') {
            if ($user_id) {
                // Show aktivs for the specified user
                $query->where('user_id', $user_id);
            }

            // Apply district filter if provided
            if ($district_id) {
                $query->whereHas('user', function ($q) use ($district_id) {
                    $q->where('district_id', $district_id);
                });
            }
        } else {
            // If not Super Admin or Manager, show only the logged-in user's aktivs
            $query->where('user_id', auth()->id());
        }

        // Order the results by created_at and paginate
        $aktivs = $query->orderBy('created_at', 'desc')
            ->with('files') // eager load the files relationship
            ->paginate(10)
            ->appends($request->query()); // Keep query parameters in pagination links

        return view('pages.aktiv.index', compact('aktivs'));
    }

    public function userTumanlarCounts(Request $request)
    {
        $user_id = $request->input('user_id');
        $district_id = $request->input('district_id');
        $userRole = auth()->user()->roles->first()->name;

        // Only Super Admins and Managers can filter by user_id or district_id
        if ($userRole != 'Super Admin' && $userRole != 'Manager') {
            abort(403, 'Unauthorized access.');
        }

        // Initialize the query builder for Aktivs
        $query = Aktiv::query();

        // Only Super Admins and Managers can filter by user_id
        if ($userRole == 'Super Admin' || $userRole == 'Manager') {
            if ($user_id) {
                // Filter aktivs by the specified user_id
                $query->where('user_id', $user_id);
            }

            // Apply district filter if provided
            if ($district_id) {
                $query->whereHas('user', function ($q) use ($district_id) {
                    $q->where('district_id', $district_id);
                });
            }
        } else {
            // If not Super Admin or Manager, show only the logged-in user's aktivs
            $query->where('user_id', auth()->id());
        }

        // Get distinct districts by joining with users and selecting the distinct district_id
        $districts = Districts::select('districts.id', 'districts.name_uz') // select relevant columns
            ->distinct()
            ->join('users', 'districts.id', '=', 'users.district_id') // join with users table
            ->join('aktivs', 'users.id', '=', 'aktivs.user_id') // join with aktivs table
            ->whereIn('aktivs.id', $query->pluck('id')) // filter the aktivs based on the query
            ->get();

        // Manually count aktivs for each district
        foreach ($districts as $district) {
            $aktivCount = Aktiv::query()
                ->whereHas('user', function ($q) use ($district, $user_id) {
                    // Apply district filter if needed
                    $q->where('district_id', $district->id);

                    // Apply user_id filter if provided
                    if ($user_id) {
                        $q->where('user_id', $user_id);
                    }
                })
                ->count(); // Get the count of aktivs for the current district

            // Add the count to the district object
            $district->aktiv_count = $aktivCount;
        }

        // Return the view with districts data
        return view('pages.aktiv.tuman_counts', compact('districts'));
    }


    // public function create()
    // {
    //     $regions = Regions::get();
    //     return view('pages.aktiv.create', compact('regions'));
    // }

    public function create()
    {
        $regions = Regions::get();  // Assuming this needs no filtering
        $isSuperAdmin = auth()->id() === 1 || true;  // Check if the user is the Super Admin
        $userDistrictId = auth()->user()->district_id;  // Get the district ID of the authenticated user

        if ($isSuperAdmin) {
            // Super Admin gets to see all Aktivs except their own creations
            $aktivs = Aktiv::with('files')->where('user_id', '!=', auth()->id())->get();
        } else {
            // Regular users see only Aktivs from their district and not created by themselves
            $aktivs = Aktiv::with('files')
                ->join('streets', 'aktivs.street_id', '=', 'streets.id')  // Join the streets table
                ->where('streets.district_id', $userDistrictId)  // Filter by user's district
                ->where('aktivs.user_id', '!=', auth()->id())  // Exclude their own Aktivs
                ->get();
        }

        $defaultImage = 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

        // Assign a default image if no files are associated with an Aktiv
        $aktivs->map(function ($aktiv) use ($defaultImage) {
            $aktiv->main_image = $aktiv->files->first() ? asset('storage/' . $aktiv->files->first()->path) : $defaultImage;
            return $aktiv;
        });

        return view('pages.aktiv.create', compact('aktivs', 'regions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'object_name'      => 'required|string|max:255',
            'balance_keeper'   => 'required|string|max:255',
            'location'         => 'required|string|max:255',
            'land_area'        => 'required|numeric',
            'building_area'    => 'nullable',
            'gas'              => 'required|string',
            'water'            => 'required|string',
            'electricity'      => 'required|string',
            'additional_info'  => 'nullable|string|max:255',
            'geolokatsiya'     => 'required|string',
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric',
            'kadastr_raqami'   => 'nullable|string|max:255',
            'files.*'          => 'required',
            'files' => 'required|array|min:4', // Enforces at least 4 files

            // Docs (optional or required as you see fit)
            'aktiv_docs'       => 'nullable|array',      // e.g. array of docs
            'aktiv_docs.*'     => 'nullable',
            // If you want doc_type selection:
            // 'doc_types'     => 'nullable|array',
            // 'doc_types.*'   => 'in:1-etap-protokol,2-etap-protokol,elon,zayavka,hokim_qarori,other',


            'sub_street_id'    => 'required',
            'street_id'    => 'required',
            'user_id'          => 'nullable',

            // New fields validation (example: all nullable)
            'turar_joy_maydoni'                         => 'nullable',
            'noturar_joy_maydoni'                       => 'nullable',
            'vaqtinchalik_parking_info'                 => 'nullable',
            'doimiy_parking_info'                       => 'nullable',
            'maktabgacha_tashkilot_info'                => 'nullable',
            'umumtaolim_maktab_info'                    => 'nullable',
            'stasionar_tibbiyot_info'                   => 'nullable',
            'ambulator_tibbiyot_info'                   => 'nullable',
            'diniy_muassasa_info'                       => 'nullable',
            'sport_soglomlashtirish_info'               => 'nullable',
            'saqlanadigan_kokalamzor_info'              => 'nullable',
            'yangidan_tashkil_kokalamzor_info'          => 'nullable',
            'saqlanadigan_muhandislik_tarmoqlari_info'  => 'nullable',
            'yangidan_quriladigan_muhandislik_tarmoqlari_info' => 'nullable',
            'saqlanadigan_yollar_info'                  => 'nullable',
            'yangidan_quriladigan_yollar_info'          => 'nullable',
        ]);


        $data = $request->except(['files', 'aktiv_docs']);
        $data['user_id'] = auth()->id();

        $aktiv = Aktiv::create($data);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('assets', 'public');

                $aktiv->files()->create([
                    'path' => $path,
                ]);
            }
        }

        // Save docs (1-etap, 2-etap, elon, etc.)
        $docTypes = [
            '1-etap-protokol' => '1-etap-protokol',
            '2-etap-protokol' => '2-etap-protokol',
            '1-etap-elon' => '1-etap-elon',
            '2-etap-elon' => '2-etap-elon',
            'zayavka' => 'zayavka',
            'hokim_qarori' => 'hokim_qarori',
            'others' => 'other',
        ];

        foreach ($docTypes as $inputName => $docType) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $path = $file->store('aktiv_docs', 'public');
                $aktiv->docs()->create([
                    'doc_type' => $docType,
                    'path'     => $path,
                ]);
            }
        }

        $data = $request->validate([
            'coordinates.*.tr' => 'required|integer',
            'coordinates.*.start_lat' => 'required|string',
            'coordinates.*.start_lon' => 'required|string',
            'coordinates.*.end_lat' => 'required|string',
            'coordinates.*.end_lon' => 'required|string',
            'coordinates.*.distance' => 'required|integer',
        ]);

        foreach ($data['coordinates'] as $coordinate) {
            Aktiv::create($coordinate);
        }

        return redirect()->route('aktivs.index')->with('success', 'Aktiv created successfully.');
    }

    public function show(Aktiv $aktiv)
    {
        // Check if the user can view this Aktiv (for authorization)
        $this->authorizeView($aktiv);

        // Load necessary relationships including the street to district relationship
        // It's crucial that subStreet is correctly mapped to district in your Aktiv model
        $aktiv->load('subStreet.district.region', 'files', 'docs');

        $defaultImage = 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

        // Add main_image attribute to the current Aktiv
        $aktiv->main_image = $aktiv->files->first() ? asset('storage/' . $aktiv->files->first()->path) : $defaultImage;

        // Retrieve user district ID from the authenticated user's associated street
        $userDistrictId = auth()->user()->district_id;  // Get the district ID of the authenticated user

        if (auth()->id() === 1 || true) {
            // Super Admin can see all aktivs
            $aktivs = Aktiv::with(['files', 'docs'])->get();
        } else {
            // Regular users see only aktivs from their district and not created by Super Admin
            $aktivs = Aktiv::with(['files', 'docs'])
                ->join('streets', 'aktivs.street_id', '=', 'streets.id')  // Ensure street is joined correctly
                ->where('streets.district_id', $userDistrictId)  // Filter by user's district from street relationship
                ->where('user_id', '!=', 1)  // Exclude aktivs created by Super Admin
                ->get();
        }

        // Add main_image attribute to each Aktiv
        $aktivs->map(function ($a) use ($defaultImage) {
            $a->main_image = $a->files->first() ? asset('storage/' . $a->files->first()->path) : $defaultImage;
            return $a;
        });

        return view('pages.aktiv.show', compact('aktiv', 'aktivs'));
    }


    public function edit(Aktiv $aktiv)
    {
        $this->authorizeView($aktiv); // Check if the user can edit this Aktiv

        $regions = Regions::get();

        $aktiv->load(['docs','polygonAktivs']);

        $polygonData = $aktiv->polygonData;

        return view('pages.aktiv.edit', compact('aktiv', 'regions','polygonData'));
    }

    public function update(Request $request, Aktiv $aktiv)
    {
        $this->authorizeView($aktiv); // Check if the user can update this Aktiv

        $request->validate([
            'object_name'      => 'required|string|max:255',
            'balance_keeper'   => 'required|string|max:255',
            'location'         => 'required|string|max:255',
            'land_area'        => 'required|numeric',
            'building_area'    => 'nullable',
            'gas'              => 'required|string',
            'water'            => 'required|string',
            'electricity'      => 'required|string',
            'additional_info'  => 'nullable|string|max:255',
            'geolokatsiya'     => 'required|string',
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric',
            'kadastr_raqami'   => 'nullable|string|max:255',
            'files.*'          => 'required',

            'aktiv_docs.*'     => 'nullable',

            'sub_street_id'    => 'nullable',
            'street_id'    => 'nullable',

            // New fields validation (example: all nullable)
            'turar_joy_maydoni'                         => 'nullable',
            'noturar_joy_maydoni'                       => 'nullable',
            'vaqtinchalik_parking_info'                 => 'nullable',
            'doimiy_parking_info'                       => 'nullable',
            'maktabgacha_tashkilot_info'                => 'nullable',
            'umumtaolim_maktab_info'                    => 'nullable',
            'stasionar_tibbiyot_info'                   => 'nullable',
            'ambulator_tibbiyot_info'                   => 'nullable',
            'diniy_muassasa_info'                       => 'nullable',
            'sport_soglomlashtirish_info'               => 'nullable',
            'saqlanadigan_kokalamzor_info'              => 'nullable',
            'yangidan_tashkil_kokalamzor_info'          => 'nullable',
            'saqlanadigan_muhandislik_tarmoqlari_info'  => 'nullable',
            'yangidan_quriladigan_muhandislik_tarmoqlari_info' => 'nullable',
            'saqlanadigan_yollar_info'                  => 'nullable',
            'yangidan_quriladigan_yollar_info'          => 'nullable',
            'coordinates'      => 'required|string', // Added validation for coordinates


            'user_id'          => 'nullable'
        ]);


        if ($request->has('delete_files')) {
            foreach ($request->delete_files as $fileId) {
                $file = $aktiv->files()->find($fileId);
                if ($file) {
                    \Storage::disk('public')->delete($file->path);
                    $file->delete();
                }
            }
        }

        // Handle doc deletions
        // Delete selected files
        if ($request->has('delete_docs')) {
            foreach ($request->delete_docs as $docId) {
                $doc = $aktiv->docs()->find($docId);
                if ($doc) {
                    \Storage::disk('public')->delete($doc->path);
                    $doc->delete();
                }
            }
        }



        $data = $request->except(['files', 'aktiv_docs', 'delete_files', 'delete_docs']);

        $aktiv->update($data);

        $aktiv->polygonAktivs()->delete();

        // Parse and save coordinates
        $coordinates = $this->parseInput($request->input('coordinates'));
        foreach ($coordinates as $coordinate) {
            PolygonAktiv::create([
                'aktiv_id' => $aktiv->id,
                'tr' => $coordinate['tr'],
                'start_lat' => $coordinate['start_lat'],
                'start_lon' => $coordinate['start_lon'],
                'end_lat' => $coordinate['end_lat'],
                'end_lon' => $coordinate['end_lon'],
                'distance' => $coordinate['distance'],
            ]);
        }
    

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('assets', 'public');
                $aktiv->files()->create([
                    'path' => $path,
                ]);
            }
        }

        // Upload new specific documents
        $docTypes = [
            '1-etap-protokol' => '1-etap-protokol',
            '2-etap-protokol' => '2-etap-protokol',
            '1-etap-elon' => '1-etap-elon',
            '2-etap-elon' => '2-etap-elon',
            'zayavka' => 'zayavka',
            'hokim_qarori' => 'hokim_qarori',
            'others' => 'other',
        ];

        foreach ($docTypes as $inputName => $docType) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $path = $file->store('aktiv_docs', 'public');
                $aktiv->docs()->create([
                    'doc_type' => $docType,
                    'path'     => $path,
                ]);
            }
        }


        return redirect()->route('aktivs.index')->with('success', 'Aktiv updated successfully.');
    }

    private function parseInput($input)
    {
        // Clean the input by removing unwanted carriage return characters and extra spaces
        $input = str_replace("\r", "", $input); // Remove any carriage returns
        $lines = explode("\n", $input); // Split into lines
        $data = [];
    
        $i = 0;
        $tempLine = [];
    
        foreach ($lines as $line) {
            // Ensure we're not processing empty lines
            if (trim($line) === '') {
                continue;
            }
    
            // Case where each set of coordinates is on one line
            if (preg_match('/^(\d+)\.\s+([\d°\'"]+С)\s+([\d°\'"]+В)\s+([\d°\'"]+С)\s+([\d°\'"]+В)\s+(\d+)$/', trim($line), $matches)) {
                // Store the parsed data in the $data array
                $data[] = [
                    'tr' => (int)$matches[1], // Transaction number
                    'start_lat' => $matches[2], // Start latitude
                    'start_lon' => $matches[3], // Start longitude
                    'end_lat' => $matches[4],   // End latitude
                    'end_lon' => $matches[5],   // End longitude
                    'distance' => (int)$matches[6], // Distance in meters
                ];
            }
            // Case where coordinates are on multiple lines
            else {
                $tempLine[] = trim($line);
                // We collect the entire set of 4 coordinates and distance
                if (count($tempLine) == 6) {
                    $data[] = [
                        'tr' => (int)$tempLine[0], // Transaction number
                        'start_lat' => $tempLine[1], // Start latitude
                        'start_lon' => $tempLine[2], // Start longitude
                        'end_lat' => $tempLine[3],   // End latitude
                        'end_lon' => $tempLine[4],   // End longitude
                        'distance' => (int)$tempLine[5], // Distance in meters
                    ];
                    // Clear the temporary array to start collecting the next line
                    $tempLine = [];
                }
            }
        }
    
        return $data;
    }



    public function destroy(Aktiv $aktiv)
    {
        $this->authorizeView($aktiv); // Check if the user can delete this Aktiv

        foreach ($aktiv->files as $file) {
            \Storage::disk('public')->delete($file->path);
            $file->delete();
        }

        $aktiv->delete();

        return redirect()->route('aktivs.index')->with('success', 'Aktiv deleted successfully.');
    }

    /**
     * Check if the authenticated user is authorized to view, edit, or delete an Aktiv.
     *
     * @param Aktiv $aktiv
     * @return void
     */
    private function authorizeView(Aktiv $aktiv)
    {
        $userRole = auth()->user()->roles->first()->name;

        if ($userRole == 'Super Admin' || $userRole == 'Manager') {
            // Super Admins and Managers can access any Aktiv
            return;
        }

        if ($aktiv->user_id == auth()->id()) {
            // The Aktiv belongs to the authenticated user
            return;
        }

        // If none of the above, deny access
        abort(403, 'Unauthorized access.');
    }

    public function userAktivCounts()
    {
        $userRole = auth()->user()->roles->first()->name;

        // Only Super Admins and Managers can access this page
        if ($userRole != 'Super Admin' && $userRole != 'Manager') {
            abort(403, 'Unauthorized access.');
        }

        // Get users and their Aktiv counts
        $users = User::withCount('aktivs')->get();
        // dd('dwq');
        return view('pages.aktiv.user_counts', compact('users'));
    }


    public function export()
    {
        // dd('daw');
        return Excel::download(new AktivsExport, 'aktivs.xlsx');
    }

    public function myMap()
    {
        $userRole = auth()->user()->roles->first()->name;

        if ($userRole == 'Super Admin') {
            return view('pages.aktiv.map_orginal');
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    // map code with source data


    public function getLots()
    {
        try {

            // Check if the authenticated user is the Super Admin (user_id = 1)
            $isSuperAdmin = auth()->id() === 1 || true;
            Log::info($isSuperAdmin);

            if ($isSuperAdmin) {
                // Super Admin sees all aktivs
                $aktivs = Aktiv::with(['files', 'user'])->get();
            } else {
                // Other users should not see aktivs created by the Super Admin (user_id = 1)
                $aktivs = Aktiv::with(['files', 'user'])
                    ->where('user_id', '!=', 1)  // Exclude records created by the Super Admin
                    ->get();
            }

            // Define the default image in case there is no image
            $defaultImage = 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

            // Map the aktivs to the required format
            $lots = $aktivs->map(function ($aktiv) use ($defaultImage) {
                // Determine the main image URL
                $mainImagePath = $aktiv->files->first() ? 'storage/' . $aktiv->files->first()->path : null;
                $mainImageUrl = $mainImagePath && file_exists(public_path($mainImagePath))
                    ? asset($mainImagePath)
                    : $defaultImage;

                // Return the necessary data
                return [
                    'lat' => $aktiv->latitude,
                    'lng' => $aktiv->longitude,
                    'property_name' => $aktiv->object_name,
                    'main_image' => $mainImageUrl,
                    'land_area' => $aktiv->land_area,
                    'start_price' => $aktiv->start_price ?? 0,
                    'lot_link' => route('aktivs.show', $aktiv->id),
                    'lot_number' => $aktiv->id,
                    'address' => $aktiv->location,
                    'user_name' => $aktiv->user ? $aktiv->user->name : 'N/A',
                    'user_email' => $aktiv->user ? $aktiv->user->email : 'N/A',

                    'turar_joy_maydoni' => $aktiv->turar_joy_maydoni ?? '',
                    'noturar_joy_maydoni' => $aktiv->noturar_joy_maydoni ?? '',
                    'vaqtinchalik_parking_info' => $aktiv->vaqtinchalik_parking_info ?? '',
                    'doimiy_parking_info' => $aktiv->doimiy_parking_info ?? '',
                    'maktabgacha_tashkilot_info' => $aktiv->maktabgacha_tashkilot_info ?? '',
                    'umumtaolim_maktab_info' => $aktiv->umumtaolim_maktab_info ?? '',
                    'stasionar_tibbiyot_info' => $aktiv->stasionar_tibbiyot_info ?? '',
                    'ambulator_tibbiyot_info' => $aktiv->ambulator_tibbiyot_info ?? '',
                    'diniy_muassasa_info' => $aktiv->diniy_muassasa_info ?? '',
                    'sport_soglomlashtirish_info' => $aktiv->sport_soglomlashtirish_info ?? '',
                    'saqlanadigan_kokalamzor_info' => $aktiv->saqlanadigan_kokalamzor_info ?? '',
                    'yangidan_tashkil_kokalamzor_info' => $aktiv->yangidan_tashkil_kokalamzor_info ?? '',
                    'saqlanadigan_muhandislik_tarmoqlari_info' => $aktiv->saqlanadigan_muhandislik_tarmoqlari_info ?? '',
                    'yangidan_quriladigan_muhandislik_tarmoqlari_info' => $aktiv->yangidan_quriladigan_muhandislik_tarmoqlari_info ?? '',
                    'saqlanadigan_yollar_info' => $aktiv->saqlanadigan_yollar_info ?? '',
                    'yangidan_quriladigan_yollar_info' => $aktiv->yangidan_quriladigan_yollar_info ?? '',
                ];
            });

            // Return the response as JSON
            return response()->json(['lots' => $lots]);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error fetching lots: ' . $e->getMessage());

            // Optionally, you can return a specific error message
            return response()->json(['error' => 'An error occurred while fetching the lots.'], 500);
        }
    }



    /**
     * Generate a QR code for the given lot's latitude and longitude
     *
     * @param string $lat Latitude of the lot
     * @param string $lng Longitude of the lot
     * @return \Illuminate\Http\Response
     */
    public function generateQRCode($lat, $lng)
    {
        $url = url("/?lat={$lat}&lng={$lng}");

        // Use the SVG format
        $qrCode = QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate($url);

        return response($qrCode, 200)->header('Content-Type', 'image/svg+xml');
    }
}
