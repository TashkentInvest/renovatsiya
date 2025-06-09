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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AktivController extends Controller
{

    public function index(Request $request)
    {
        $user_id = $request->input('user_id');
        $district_id = $request->input('district_id');
        $search = $request->input('search');
        $status = $request->input('status');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $userRole = auth()->user()->roles->first()->name;

        // Initialize the query builder for Aktivs
        $query = Aktiv::query();

        // Only Super Admins and Managers can filter by user_id
        if ($userRole == 'Super Admin' || $userRole == 'Manager') {
            if ($user_id) {
                $query->where('user_id', $user_id);
            }

            if ($district_id) {
                $query->whereHas('user', function ($q) use ($district_id) {
                    $q->where('district_id', $district_id);
                });
            }
        } else {
            // If not Super Admin or Manager, show only the logged-in user's aktivs
            $query->where('user_id', auth()->id());
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('district_name', 'like', "%{$search}%")
                  ->orWhere('neighborhood_name', 'like', "%{$search}%")
                  ->orWhere('investor', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status) {
            if ($status == 'active') {
                $query->where('status', '>=', 8);
            } elseif ($status == 'inactive') {
                $query->where('status', '<', 5);
            } elseif ($status == 'pending') {
                $query->whereBetween('status', [5, 7]);
            }
        }

        // Apply date filters
        if ($date_from) {
            $query->whereDate('created_at', '>=', $date_from);
        }

        if ($date_to) {
            $query->whereDate('created_at', '<=', $date_to);
        }

        // Order the results by created_at and paginate
        $aktivs = $query->orderBy('created_at', 'desc')
            ->with(['files', 'polygons']) // eager load relationships
            ->paginate(15)
            ->appends($request->query()); // Keep query parameters in pagination links

        return view('pages.aktiv.index', compact('aktivs'));
    }

    /**
     * API endpoint to get all aktivs data
     */
    public function apiIndex(Request $request)
    {
        try {
            $userRole = auth()->user()->roles->first()->name ?? 'User';

            // Initialize the query builder for Aktivs
            $query = Aktiv::query();

            // Apply role-based filtering
            if (!in_array($userRole, ['Super Admin', 'Manager'])) {
                $query->where('user_id', auth()->id());
            }

            // Get all aktivs with related data
            $aktivs = $query->with(['files', 'polygons'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Transform the data to match the expected API format
            $transformedData = $aktivs->map(function ($aktiv) {
                return [
                    'id' => $aktiv->id,
                    'district_name' => $aktiv->district_name,
                    'neighborhood_name' => $aktiv->neighborhood_name,
                    'lat' => $aktiv->lat,
                    'lng' => $aktiv->lng,
                    'area_hectare' => $aktiv->area_hectare,
                    'total_building_area' => $aktiv->total_building_area,
                    'residential_area' => $aktiv->residential_area,
                    'non_residential_area' => $aktiv->non_residential_area,
                    'adjacent_area' => $aktiv->adjacent_area,
                    'object_information' => $aktiv->object_information,
                    'umn_coefficient' => $aktiv->umn_coefficient,
                    'qmn_percentage' => $aktiv->qmn_percentage,
                    'designated_floors' => $aktiv->designated_floors,
                    'proposed_floors' => $aktiv->proposed_floors,
                    'decision_number' => $aktiv->decision_number,
                    'cadastre_certificate' => $aktiv->cadastre_certificate,
                    'area_strategy' => $aktiv->area_strategy,
                    'investor' => $aktiv->investor,
                    'status' => $aktiv->status,
                    'population' => $aktiv->population,
                    'household_count' => $aktiv->household_count,
                    'additional_information' => $aktiv->additional_information,
                    'main_image' => $aktiv->main_image ? asset($aktiv->main_image) : null,
                    'polygons' => $aktiv->polygons ? $aktiv->polygons->map(function ($polygon) {
                        return [
                            'id' => $polygon->id,
                            'coordinates' => $polygon->coordinates,
                            'type' => $polygon->type,
                        ];
                    }) : [],
                    'documents' => $aktiv->files ? $aktiv->files->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'doc_type' => $file->file_type ?? 'document',
                            'path' => $file->file_path,
                            'url' => asset($file->file_path),
                            'filename' => basename($file->file_path),
                        ];
                    }) : [],
                ];
            });

            return response()->json([
                'success' => true,
                'lots' => $transformedData,
                'total' => $aktivs->count(),
                'message' => 'Активлар муваффақиятли юкланди'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Маълумотларни юклашда хатолик: ' . $e->getMessage(),
                'lots' => [],
                'total' => 0
            ], 500);
        }
    }

    /**
     * API endpoint for filtered data
     */
    public function apiFilter(Request $request)
    {
        try {
            $userRole = auth()->user()->roles->first()->name ?? 'User';

            // Initialize the query builder
            $query = Aktiv::query();

            // Apply role-based filtering
            if (!in_array($userRole, ['Super Admin', 'Manager'])) {
                $query->where('user_id', auth()->id());
            }

            // Apply filters
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('district_name', 'like', "%{$search}%")
                      ->orWhere('neighborhood_name', 'like', "%{$search}%")
                      ->orWhere('investor', 'like', "%{$search}%");
                });
            }

            if ($request->has('district_name') && !empty($request->district_name)) {
                $query->where('district_name', $request->district_name);
            }

            if ($request->has('status') && !empty($request->status)) {
                $status = $request->status;
                if ($status == 'active') {
                    $query->where('status', '>=', 8);
                } elseif ($status == 'inactive') {
                    $query->where('status', '<', 5);
                } elseif ($status == 'pending') {
                    $query->whereBetween('status', [5, 7]);
                }
            }

            if ($request->has('investor') && !empty($request->investor)) {
                $query->where('investor', 'like', "%{$request->investor}%");
            }

            // Pagination
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 15);

            $aktivs = $query->with(['files', 'polygons'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            // Transform data
            $transformedData = $aktivs->getCollection()->map(function ($aktiv) {
                return [
                    'id' => $aktiv->id,
                    'district_name' => $aktiv->district_name,
                    'neighborhood_name' => $aktiv->neighborhood_name,
                    'lat' => $aktiv->lat,
                    'lng' => $aktiv->lng,
                    'area_hectare' => $aktiv->area_hectare,
                    'total_building_area' => $aktiv->total_building_area,
                    'residential_area' => $aktiv->residential_area,
                    'non_residential_area' => $aktiv->non_residential_area,
                    'adjacent_area' => $aktiv->adjacent_area,
                    'object_information' => $aktiv->object_information,
                    'umn_coefficient' => $aktiv->umn_coefficient,
                    'qmn_percentage' => $aktiv->qmn_percentage,
                    'designated_floors' => $aktiv->designated_floors,
                    'proposed_floors' => $aktiv->proposed_floors,
                    'decision_number' => $aktiv->decision_number,
                    'cadastre_certificate' => $aktiv->cadastre_certificate,
                    'area_strategy' => $aktiv->area_strategy,
                    'investor' => $aktiv->investor,
                    'status' => $aktiv->status,
                    'population' => $aktiv->population,
                    'household_count' => $aktiv->household_count,
                    'additional_information' => $aktiv->additional_information,
                    'main_image' => $aktiv->main_image ? asset($aktiv->main_image) : null,
                    'polygons' => $aktiv->polygons ? $aktiv->polygons->map(function ($polygon) {
                        return [
                            'id' => $polygon->id,
                            'coordinates' => $polygon->coordinates,
                            'type' => $polygon->type,
                        ];
                    }) : [],
                    'documents' => $aktiv->files ? $aktiv->files->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'doc_type' => $file->file_type ?? 'document',
                            'path' => $file->file_path,
                            'url' => asset($file->file_path),
                            'filename' => basename($file->file_path),
                        ];
                    }) : [],
                ];
            });

            return response()->json([
                'success' => true,
                'lots' => $transformedData,
                'current_page' => $aktivs->currentPage(),
                'last_page' => $aktivs->lastPage(),
                'per_page' => $aktivs->perPage(),
                'total' => $aktivs->total(),
                'from' => $aktivs->firstItem(),
                'to' => $aktivs->lastItem(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Филтрлашда хатолик: ' . $e->getMessage(),
                'lots' => [],
                'total' => 0
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function apiStatistics(Request $request)
    {
        try {
            $userRole = auth()->user()->roles->first()->name ?? 'User';

            $query = Aktiv::query();

            // Apply role-based filtering
            if (!in_array($userRole, ['Super Admin', 'Manager'])) {
                $query->where('user_id', auth()->id());
            }

            $aktivs = $query->get();

            $statistics = [
                'total_aktivs' => $aktivs->count(),
                'total_residential_area' => $aktivs->sum('residential_area'),
                'total_non_residential_area' => $aktivs->sum('non_residential_area'),
                'total_building_area' => $aktivs->sum('total_building_area'),
                'total_population' => $aktivs->sum('population'),
                'total_area_hectare' => $aktivs->sum('area_hectare'),
                'total_household_count' => $aktivs->sum('household_count'),
                'status_distribution' => [
                    'active' => $aktivs->where('status', '>=', 8)->count(),
                    'pending' => $aktivs->whereBetween('status', [5, 7])->count(),
                    'inactive' => $aktivs->where('status', '<', 5)->count(),
                ],
                'district_distribution' => $aktivs->groupBy('district_name')
                    ->map(function ($group) {
                        return $group->count();
                    })->toArray(),
                'monthly_additions' => $this->getMonthlyStatistics($aktivs),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $statistics,
                'message' => 'Статистика муваффақиятли юкланди'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Статистикани юклашда хатолик: ' . $e->getMessage(),
                'statistics' => null
            ], 500);
        }
    }

    /**
     * Export aktivs to Excel/CSV
     */
    public function export(Request $request)
    {
        try {
            $userRole = auth()->user()->roles->first()->name ?? 'User';

            $query = Aktiv::query();

            // Apply role-based filtering
            if (!in_array($userRole, ['Super Admin', 'Manager'])) {
                $query->where('user_id', auth()->id());
            }

            // Apply any filters from request
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('district_name', 'like', "%{$search}%")
                      ->orWhere('neighborhood_name', 'like', "%{$search}%")
                      ->orWhere('investor', 'like', "%{$search}%");
                });
            }

            if ($request->has('district_name') && !empty($request->district_name)) {
                $query->where('district_name', $request->district_name);
            }

            if ($request->has('status') && !empty($request->status)) {
                $status = $request->status;
                if ($status == 'active') {
                    $query->where('status', '>=', 8);
                } elseif ($status == 'inactive') {
                    $query->where('status', '<', 5);
                } elseif ($status == 'pending') {
                    $query->whereBetween('status', [5, 7]);
                }
            }

            $aktivs = $query->orderBy('created_at', 'desc')->get();

            // Create CSV content
            $headers = [
                '№',
                'Туман',
                'Маҳалла',
                'Ҳудуд майдони (га)',
                'Қурилиш майдони (м²)',
                'Турар жой майдони (м²)',
                'Нотурар жой майдони (м²)',
                'Туташ ҳудуд (м²)',
                'Инвестор',
                'Ҳолат',
                'Аҳоли сони',
                'Уй хўжаликлари',
                'УМН коэффициент',
                'ҚМН фоизи',
                'Белгиланган қаватлар',
                'Таклиф этилган қаватлар',
                'Қарор рақами',
                'Яратилган сана'
            ];

            $filename = 'aktivlar_' . date('Y-m-d_H-i-s') . '.csv';
            $file = fopen('php://output', 'w');

            // Set headers for download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write headers
            fputcsv($file, $headers);

            // Write data
            foreach ($aktivs as $index => $aktiv) {
                $status = '';
                if ($aktiv->status >= 8) {
                    $status = 'Актив';
                } elseif ($aktiv->status >= 5) {
                    $status = 'Кутилмоқда';
                } else {
                    $status = 'Ноактив';
                }

                fputcsv($file, [
                    $index + 1,
                    $aktiv->district_name ?? '',
                    $aktiv->neighborhood_name ?? '',
                    number_format($aktiv->area_hectare ?? 0, 2),
                    number_format($aktiv->total_building_area ?? 0, 2),
                    number_format($aktiv->residential_area ?? 0, 2),
                    number_format($aktiv->non_residential_area ?? 0, 2),
                    number_format($aktiv->adjacent_area ?? 0, 2),
                    $aktiv->investor ?? '',
                    $status,
                    $aktiv->population ?? 0,
                    $aktiv->household_count ?? 0,
                    $aktiv->umn_coefficient ?? '',
                    $aktiv->qmn_percentage ?? '',
                    $aktiv->designated_floors ?? '',
                    $aktiv->proposed_floors ?? '',
                    $aktiv->decision_number ?? '',
                    $aktiv->created_at ? $aktiv->created_at->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Экспорт қилишда хатолик: ' . $e->getMessage());
        }
    }

    /**
     * Get monthly statistics for charts
     */
    private function getMonthlyStatistics($aktivs)
    {
        $monthlyData = [];
        $currentYear = date('Y');

        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('M', mktime(0, 0, 0, $i, 1));
            $count = $aktivs->filter(function ($aktiv) use ($i, $currentYear) {
                return $aktiv->created_at &&
                       $aktiv->created_at->year == $currentYear &&
                       $aktiv->created_at->month == $i;
            })->count();

            $monthlyData[$monthName] = $count;
        }

        return $monthlyData;
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
            'land_area'        => 'nullable',
            'building_area'    => 'nullable',
            'total_area'    => 'nullable',
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


        $data = $request->except(['files', 'aktiv_docs', 'polygon_aktivs']);
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

        if ($request->has('polygon_aktivs')) {
            foreach ($request->polygon_aktivs as $polygonAktivData) {
                $aktiv->polygonAktivs()->create($polygonAktivData);
            }
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

        $aktiv->load(['docs', 'polygonAktivs']);

        $polygonData = $aktiv->polygonAktivs;

        return view('pages.aktiv.edit', compact('aktiv', 'regions', 'polygonData'));
    }

    public function update(Request $request, Aktiv $aktiv)
    {
        $this->authorizeView($aktiv); // Check if the user can update this Aktiv

        $request->validate([
            'object_name'      => 'required|string|max:255',
            'balance_keeper'   => 'required|string|max:255',
            'location'         => 'required|string|max:255',
            'land_area'        => 'nullable',
            'building_area'    => 'nullable',
            'total_area'    => 'nullable',
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
            'coordinates'      => 'nullable', // Added validation for coordinates


            'user_id'          => 'nullable'
        ]);


        if ($request->has('delete_files')) {
            foreach ($request->delete_files as $fileId) {
                $file = $aktiv->files()->find($fileId);
                if ($file) {
                    Storage::disk('public')->delete($file->path);
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
                    Storage::disk('public')->delete($doc->path);
                    $doc->delete();
                }
            }
        }



        $data = $request->except(['files', 'aktiv_docs', 'delete_files', 'delete_docs', 'polygon_aktivs']);

        $aktiv->update($data);

        $aktiv->polygonAktivs()->delete();
        if ($request->has('polygon_aktivs')) {
            foreach ($request->polygon_aktivs as $polygonAktivData) {
                $aktiv->polygonAktivs()->create($polygonAktivData);
            }
        }
        // Parse and save coordinates

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
            Storage::disk('public')->delete($file->path);
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



    public function myMap()
    {
        // dd('dsa');
        // Check if the user is a Super Admin
        if (auth()->user()) {
            return view('pages.aktiv.map_orginal_taklif');
        }

        return view('pages.aktiv.map_orginal_old_which_work');

        // $userRole = auth()->user()->roles->first()->name;

        // if ($userRole == 'Super Admin') {
        //     return view('pages.aktiv.map_orginal');
        // } else {
        //     abort(403, 'Unauthorized access.');
        // }
    }

    public function myTaklifMap()
    {
        $userRole = auth()->user()->roles->first()->name;

        if ($userRole == 'Super Admin') {
            return view('pages.aktiv.map_orginal_taklif');
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    public function myTaklifMap_which_work()
    {
        $userRole = auth()->user()->roles->first()->name;

        if ($userRole == 'Super Admin') {
            return view('pages.aktiv.react_map');
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    // map code with source data


    // clear cache
    public function clearCache()
    {
        try {
            Cache::forget('aktivs_data'); // Clear the specific cache
            // Log::error('cleared ');

            return redirect()->back()->with(['message' => 'Cache cleared successfully.']);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error clearing cache: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while clearing the cache.'], 500);
        }
    }

    public function getLots()
    {
        try {
            // Define cache key
            $cacheKey = 'aktivs_data';

            // Check if data is cached
            // $lots = Cache::remember($cacheKey, 60 * 60, function () {
            // Fetch the data from the database
            $isSuperAdmin = auth()->id() === 1 || true;
            Log::info("User is superAdmin: " . ($isSuperAdmin ? 'Yes' : 'No'));

            if ($isSuperAdmin) {
                // Super Admin sees all aktivs
                $aktivs = Aktiv::with(['files', 'user', 'polygonAktivs', 'aktivDocs'])->get();
            } else {
                // Other users should not see aktivs created by the Super Admin (user_id = 1)
                $aktivs = Aktiv::with(['files', 'user', 'polygonAktivs', 'aktivDocs'])
                    ->where('user_id', '!=', 1)  // Exclude records created by the Super Admin
                    ->get();
            }

            // Define the default image in case there is no image
            $defaultImage = 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

            // Map the aktivs to the required format
            $lots =  $aktivs->map(function ($aktiv) use ($defaultImage) {
                // Determine the main image URL
                $mainImagePath = $aktiv->files->first() ? 'storage/' . $aktiv->files->first()->path : null;
                $mainImageUrl = $mainImagePath && file_exists(public_path($mainImagePath))
                    ? asset($mainImagePath)
                    : $defaultImage;

                // Return the necessary data
                return [
                    'id' => $aktiv->id, // Make sure to include the ID
                    'district_name' => $aktiv->district_name,
                    'neighborhood_name' => $aktiv->neighborhood_name,
                    'lat' => $aktiv->latitude, // Make sure this matches the field name in your JS
                    'lng' => $aktiv->longitude, // Make sure this matches the field name in your JS
                    'area_hectare' => $aktiv->area_hectare,
                    'total_building_area' => $aktiv->total_building_area,
                    'residential_area' => $aktiv->residential_area,
                    'non_residential_area' => $aktiv->non_residential_area,
                    'adjacent_area' => $aktiv->adjacent_area,
                    'object_information' => $aktiv->object_information,
                    'umn_coefficient' => $aktiv->umn_coefficient,
                    'qmn_percentage' => $aktiv->qmn_percentage,
                    'designated_floors' => $aktiv->designated_floors,
                    'proposed_floors' => $aktiv->proposed_floors,
                    'decision_number' => $aktiv->decision_number,
                    'cadastre_certificate' => $aktiv->cadastre_certificate,
                    'area_strategy' => $aktiv->area_strategy,
                    'investor' => $aktiv->investor,
                    'status' => $aktiv->status,
                    'population' => $aktiv->population,
                    'household_count' => $aktiv->household_count,
                    'additional_information' => $aktiv->additional_information,
                    'main_image' => $mainImageUrl,
                    'polygons' => $aktiv->polygonAktivs->map(function ($polygon) {
                        return [
                            'start_lat' => $polygon->start_lat,
                            'start_lon' => $polygon->start_lon,
                            'end_lat' => $polygon->end_lat,
                            'end_lon' => $polygon->end_lon
                        ];
                    }),
                    'documents' => $aktiv->aktivDocs->map(function ($doc) {
                        // Ensure URL is absolute
                        $url = $doc->url ? $doc->url : asset($doc->path);

                        return [
                            'id' => $doc->id,
                            'doc_type' => $doc->doc_type,
                            'path' => $doc->path,
                            'url' => $url,
                            'filename' => $doc->filename ?: basename($doc->path)
                        ];
                    }),
                ];
            });
            // });

            // Return the response as JSON
            return response()->json(['lots' => $lots]);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error fetching lots: ' . $e->getMessage());

            // Optionally, you can return a specific error message
            return response()->json(['error' => 'An error occurred while fetching the lots.'], 500);
        }
    }

    // public function getLots()
    // {
    //     try {
    //         // Determine user authorization
    //         $isSuperAdmin = auth()->id() === 1 || true;
    //         Log::info($isSuperAdmin);

    //         if ($isSuperAdmin) {
    //             // Super Admin sees all aktivs
    //             $aktivs = Aktiv::with(['files', 'user', 'polygonAktivs', 'aktivDocs'])->get();
    //         } else {
    //             // Other users should not see aktivs created by the Super Admin (user_id = 1)
    //             $aktivs = Aktiv::with(['files', 'user', 'polygonAktivs', 'aktivDocs'])
    //                 ->where('user_id', '!=', 1)  // Exclude records created by the Super Admin
    //                 ->get();
    //         }

    //         // Define the default image in case there is no image
    //         $defaultImage = 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

    //         // Map the aktivs to the required format
    //         $lots = $aktivs->map(function ($aktiv) use ($defaultImage) {
    //             // Determine the main image URL
    //             $mainImagePath = $aktiv->files->first() ? 'storage/' . $aktiv->files->first()->path : null;
    //             $mainImageUrl = $mainImagePath && file_exists(public_path($mainImagePath))
    //                 ? asset($mainImagePath)
    //                 : $defaultImage;

    //             // Return the necessary data
    //             return [
    //                 'district_name' => $aktiv->district_name,
    //                 'neighborhood_name' => $aktiv->neighborhood_name,
    //                 'lat' => $aktiv->latitude, // Make sure this matches the field name in your JS
    //                 'lng' => $aktiv->longitude, // Make sure this matches the field name in your JS
    //                 'area_hectare' => $aktiv->area_hectare,
    //                 'total_building_area' => $aktiv->total_building_area,
    //                 'residential_area' => $aktiv->residential_area,
    //                 'non_residential_area' => $aktiv->non_residential_area,
    //                 'adjacent_area' => $aktiv->adjacent_area,
    //                 'object_information' => $aktiv->object_information,
    //                 'umn_coefficient' => $aktiv->umn_coefficient,
    //                 'qmn_percentage' => $aktiv->qmn_percentage,
    //                 'designated_floors' => $aktiv->designated_floors,
    //                 'proposed_floors' => $aktiv->proposed_floors,
    //                 'decision_number' => $aktiv->decision_number,
    //                 'cadastre_certificate' => $aktiv->cadastre_certificate,
    //                 'area_strategy' => $aktiv->area_strategy,
    //                 'investor' => $aktiv->investor,
    //                 'status' => $aktiv->status,
    //                 'population' => $aktiv->population,
    //                 'household_count' => $aktiv->household_count,
    //                 'additional_information' => $aktiv->additional_information,
    //                 'main_image' => $mainImageUrl,
    //                 'polygons' => $aktiv->polygonAktivs->map(function ($polygon) {
    //                     return [
    //                         'start_lat' => $polygon->start_lat,
    //                         'start_lon' => $polygon->start_lon,
    //                         'end_lat' => $polygon->end_lat,
    //                         'end_lon' => $polygon->end_lon
    //                     ];
    //                 }),
    //                 // 'documents' => $aktiv->aktivDocs->map(function ($doc) {
    //                 //     return [
    //                 //         'id' => $doc->id,
    //                 //         'doc_type' => $doc->doc_type,
    //                 //         'path' => $doc->path,
    //                 //         'url' => asset($doc->path),
    //                 //         'filename' => basename($doc->path)
    //                 //     ];
    //                 // })
    //             ];
    //         });

    //         // Return the response as JSON
    //         return response()->json(['lots' => $lots]);
    //     } catch (\Exception $e) {
    //         // Log the error message
    //         Log::error('Error fetching lots: ' . $e->getMessage());

    //         // Optionally, you can return a specific error message
    //         return response()->json(['error' => 'An error occurred while fetching the lots.'], 500);
    //     }
    // }
    public function getTaklifLots()
    {
        try {
            $cacheKey = 'aktivs_data';

            return Cache::remember($cacheKey, 60 * 60, function () {
                $isSuperAdmin = auth()->id() === 1 || true;
                Log::info('User is SuperAdmin: ' . ($isSuperAdmin ? 'Yes' : 'No'));

                $aktivs = Aktiv::with(['files', 'user', 'polygonAktivs'])
                    ->when(!$isSuperAdmin, function ($query) {
                        return $query->where('user_id', '!=', 1);
                    })
                    ->get();

                $defaultImage = 'https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png';

                return [
                    'lots' => $aktivs->map(function ($aktiv) use ($defaultImage) {
                        $mainImagePath = $aktiv->files->first() ? 'storage/' . $aktiv->files->first()->path : null;
                        $mainImageUrl = $mainImagePath && file_exists(public_path($mainImagePath))
                            ? asset($mainImagePath)
                            : $defaultImage;

                        // Format dates
                        $resettlementStart = $aktiv->resettlement_start ? date('d.m.Y', strtotime($aktiv->resettlement_start)) : null;
                        $resettlementEnd = $aktiv->resettlement_end ? date('d.m.Y', strtotime($aktiv->resettlement_end)) : null;
                        $projectStart = $aktiv->project_start ? date('d.m.Y', strtotime($aktiv->project_start)) : null;

                        return [
                            'lat' => $aktiv->latitude,
                            'lng' => $aktiv->longitude,
                            'neighborhood_name' => $aktiv->neighborhood_name,
                            'main_image' => $mainImageUrl,
                            'area_hectare' => $aktiv->area_hectare,
                            'total_building_area' => $aktiv->total_building_area,
                            'residential_area' => $aktiv->residential_area,
                            'non_residential_area' => $aktiv->non_residential_area,
                            'umn_coefficient' => $aktiv->umn_coefficient,
                            'qmn_percentage' => $aktiv->qmn_percentage,
                            'designated_floors' => $aktiv->designated_floors,
                            'proposed_floors' => $aktiv->proposed_floors,
                            'decision_number' => $aktiv->decision_number,
                            'cadastre_certificate' => $aktiv->cadastre_certificate,
                            'area_strategy' => $aktiv->area_strategy,
                            'investor' => $aktiv->investor,
                            'status' => $aktiv->status,
                            'population' => $aktiv->population,
                            'household_count' => $aktiv->household_count,
                            'additional_information' => $aktiv->additional_information,

                            // New renovation-specific fields
                            'single_house_count' => $aktiv->single_house_count,
                            'single_house_area' => $aktiv->single_house_area,
                            'multi_story_house_count' => $aktiv->multi_story_house_count,
                            'multi_story_house_area' => $aktiv->multi_story_house_area,
                            'non_residential_count' => $aktiv->non_residential_count,
                            'non_residential_building_area' => $aktiv->non_residential_building_area,
                            'area_passport' => $aktiv->area_passport,
                            'protocol_number' => $aktiv->protocol_number,
                            'land_assessment' => $aktiv->land_assessment,
                            'investment_contract' => $aktiv->investment_contract,
                            'public_discussion' => $aktiv->public_discussion,
                            'resettlement_start' => $resettlementStart,
                            'resettlement_end' => $resettlementEnd,
                            'project_start' => $projectStart,
                            'assessment_status' => $aktiv->assessment_status,
                            'announcement' => $aktiv->announcement,
                            'zone' => $aktiv->zone,

                            'lot_link' => route('aktivs.show', $aktiv->id),
                            'lot_number' => $aktiv->id,
                            'documents' => $aktiv->files->map(function ($file) {
                                return [
                                    'url' => asset('storage/' . $file->path),
                                    'filename' => basename($file->path)
                                ];
                            }),
                            'polygons' => $aktiv->polygonAktivs->map(function ($polygon) {
                                return [
                                    'start_lat' => $polygon->start_lat,
                                    'start_lon' => $polygon->start_lon,
                                    'end_lat' => $polygon->end_lat,
                                    'end_lon' => $polygon->end_lon
                                ];
                            })
                        ];
                    })
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error fetching lots: ' . $e->getMessage());
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
