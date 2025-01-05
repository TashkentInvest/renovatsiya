<?php

namespace App\Models;

use App\Services\HistoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aktiv extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::updated(function ($model) {
            $original = $model->getOriginal();
            $changes = $model->getChanges();

            HistoryService::record($model, $original, $changes);
        });

        static::deleted(function ($model) {
            History::create([
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'field' => 'deleted',
                'old_value' => json_encode($model->getOriginal()), // Store old data as JSON
                'new_value' => null,
                'user_id' => auth()->id() ?? 1,
            ]);
        });
    }

    protected $fillable = [
        'user_id',
        'action',
        'action_timestamp',
        'object_name',
        'balance_keeper',
        'location',
        'land_area',
        'building_area',
        'gas',
        'water',
        'electricity',
        'additional_info',
        'geolokatsiya',
        'latitude',
        'longitude',
        'kadastr_raqami',
        'sub_street_id',
        'street_id',

        // Below are the newly added fields from the requirements

        // 2.1.1.2 — Turar/noturar joy maydonlari
        'turar_joy_maydoni',
        'noturar_joy_maydoni',

        // 2.1.1.3 — Transport vositalarining vaqtinchalik/doimiy to‘xtab turish joylari
        // For simplicity, storing them as text. If you have multiple, you can store as JSON or separate fields:
        'vaqtinchalik_parking_info', // e.g. "1-вақтинчалик: __ та жой; 2-вақтинчалик: __ та жой; ..."
        'doimiy_parking_info',       // e.g. "1-доимий: __ та жой; 2-доимий: __ та жой; ..."

        // 2.1.1.4 — Maktabgacha ta’lim
        'maktabgacha_tashkilot_info', // e.g. "1-...__ ўринли; 2-...__ ўринли; ..."

        // 2.1.1.5 — Umumta’lim maktablari
        'umumtaolim_maktab_info', // e.g. "1-...__ ўринли; 2-...__ ўринли; ..."

        // 2.1.1.6 — Tibbiyot muassasalari
        'stasionar_tibbiyot_info',   // e.g. "1-стационар __ ўринли; 2-стационар __ ўринли; ..."
        'ambulator_tibbiyot_info',   // e.g. "1-амбулатор __ тагача; 2-амбулатор __ тагача; ..."

        // 2.1.1.7 — Diniy muassasalar
        'diniy_muassasa_info', // e.g. "1-диний __ тагача; 2-диний __ тагача; ..."

        // 2.1.1.8 — Sport-sog’lomlashtirish
        'sport_soglomlashtirish_info', // e.g. "1-спорт __ тагача; 2-спорт __ тагача; ..."

        // 2.1.1.9 — Saqlab qolinadigan (yoki rekonstruksiya qilinadigan) ko‘kalamzorlashtirish maydonlari
        'saqlanadigan_kokalamzor_info', // e.g. "1-майдон __ кв.м ... 2-майдон __ кв.м ..."

        // 2.1.1.10 — Yangidan tashkil qilinadigan ko‘kalamzorlashtirish maydonlari
        'yangidan_tashkil_kokalamzor_info',

        // 2.1.1.11 — Saqlanib qol. muhandislik-kommunikatsiya tarmoqlari
        'saqlanadigan_muhandislik_tarmoqlari_info',

        // 2.1.1.12 — Yangidan quriladigan muhandislik-kommunikatsiya tarmoqlari
        'yangidan_quriladigan_muhandislik_tarmoqlari_info',

        // 2.1.1.13 — Saqlanib qol. yo‘llar va yo‘laklar
        'saqlanadigan_yollar_info',

        // 2.1.1.14 — Yangidan quriladigan yo‘llar va yo‘laklar
        'yangidan_quriladigan_yollar_info',

        // polygon datas

        // 'tr',
        // 'start_lat',
        // 'start_lon',
        // 'end_lat',
        // 'end_lon',
        // 'distance',
    ];

    public function polygonAktivs()
    {
        return $this->hasMany(PolygonAktiv::class, 'aktiv_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function substreet()
    {
        return $this->belongsTo(SubStreet::class, 'sub_street_id', 'id');
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_id', 'id');
    }

    public function docs()
    {
        return $this->hasMany(AktivDoc::class, 'aktiv_id', 'id');
    }
}
