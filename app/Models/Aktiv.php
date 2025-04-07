<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aktiv extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'district_name',
        'neighborhood_name',
        'area_hectare',
        'total_building_area',
        'residential_area',
        'non_residential_area',
        'adjacent_area',
        'object_information',
        'umn_coefficient',
        'qmn_percentage',
        'designated_floors',
        'proposed_floors',
        'decision_number',
        'cadastre_certificate',
        'area_strategy',
        'investor',
        'status',
        'population',
        'household_count',
        'additional_information',
        'latitude',
        'longitude',
        'user_id',
        'action',
        'action_timestamp'
    ];

    // If your relationship is named polygonAktivs, ensure this method matches
    public function polygonAktivs()
    {
        return $this->hasMany(PolygonAktiv::class, 'aktiv_id');
    }

    // Add an alias method for consistency with your API response
    public function polygons()
    {
        return $this->polygonAktivs();
    }

    // Relationship for files
    public function files()
    {
        return $this->hasMany(File::class, 'aktiv_id');
    }

    // Relationship for user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aktivDocs()
    {
        return $this->hasMany(AktivDoc::class, 'aktiv_id');
    }
}
