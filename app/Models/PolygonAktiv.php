<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolygonAktiv extends Model
{
    use HasFactory;

    protected $fillable = [
        'aktiv_id',  // The foreign key for the relationship
        'start_lat',
        'start_lon',
        'end_lat',
        'end_lon',
        'distance',
        'comment',
    ];

    // Relationship: Each PolygonAktiv belongs to one Aktiv
    public function aktiv()
    {
        return $this->belongsTo(Aktiv::class);
    }
}