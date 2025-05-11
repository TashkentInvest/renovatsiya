<?php

public function getLots()
    {
        $isSuperAdmin = auth()->id() === 1;

        if ($isSuperAdmin) {
            $aktivs = Aktiv::with([‘files’, ‘user’])->get();
        } else {
            $aktivs = Aktiv::with([‘files’, ‘user’])
                ->get();
        }

        $defaultImage = ‘https://cdn.dribbble.com/users/1651691/screenshots/5336717/404_v2.png’;

        $lots = $aktivs->map(function ($aktiv) use ($defaultImage) {
            $mainImagePath = $aktiv->files->first() ? ‘storage/’ . $aktiv->files->first()->path : null;
            $mainImageUrl = $mainImagePath && file_exists(public_path($mainImagePath))
                ? asset($mainImagePath)
                : $defaultImage;

            return [
                ‘lat’ => $aktiv->latitude ?? null,
                ‘lng’ => $aktiv->longitude ?? null,
                ‘property_name’ => $aktiv->object_name ?? null,
                ‘main_image’ => $mainImageUrl ?? null,
                ‘land_area’ => $aktiv->land_area ?? null,
                ‘start_price’ => $aktiv->start_price ?? 0,
                ‘lot_link’ => route(‘aktivs.show’, $aktiv->id ?? null),
                ‘lot_number’ => $aktiv->id ?? null,
                ‘address’ => $aktiv->location ?? null,
                ‘user_name’ => $aktiv->user ? $aktiv->user->name : ‘N/A’,
                ‘user_email’ => $aktiv->user ? $aktiv->user->email : ‘N/A’,
                ‘building_type’ => $aktiv->building_type ?? null

            ];
        });

        return response()->json([‘lots’ => $lots]);
    }
