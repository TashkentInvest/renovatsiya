<?php

namespace App\Http\Controllers;

use App\Models\Aktiv;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function dmsToDecimal($dms)
    {
        if (!$dms) {
            return null;
        }

        // Удалить лишние символы
        $dms = preg_replace('/[^\d°\'".,]/u', '', $dms);

        // Разделить на градусы, минуты, секунды
        if (preg_match('/(\d{1,3})°(\d{1,2})\'(\d{1,2}(?:\.\d+)?)"/', $dms, $matches)) {
            $degrees = $matches[1];
            $minutes = $matches[2] / 60;
            $seconds = $matches[3] / 3600;
            return $degrees + $minutes + $seconds;
        }

        return null; // Некорректный формат
    }

    public function showPolygonData($aktiv_id)
    {
        $aktiv = Aktiv::with('polygonAktivs')->findOrFail($aktiv_id);

        // Преобразовать координаты
        $polygonData = $aktiv->polygonAktivs->map(function ($polygon) {
            return [
                'start' => [
                    'lat' => $this->dmsToDecimal($polygon->start_lat),
                    'lng' => $this->dmsToDecimal($polygon->start_lon),
                ],
                'end' => [
                    'lat' => $this->dmsToDecimal($polygon->end_lat),
                    'lng' => $this->dmsToDecimal($polygon->end_lon),
                ]
            ];
        });

        return response()->json($polygonData);
    }
}
