<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'aktiv_id',
        'doc_type',
        'path',
    ];

    public function aktiv()
    {
        return $this->belongsTo(Aktiv::class);
    }
}
