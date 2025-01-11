<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAktivsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktivs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_street_id')->nullable();
            $table->foreign('sub_street_id')->references('id')->on('sub_streets')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('action', ['created', 'updated', 'deleted'])->nullable();
            $table->timestamp('action_timestamp')->nullable();
            $table->softDeletes();

            $table->string('object_name')->nullable();
            $table->string('balance_keeper')->nullable();
            $table->string('location')->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('building_area', 10, 2)->nullable();
            $table->decimal('total_area', 10, 2)->nullable();
            $table->string('gas')->nullable();
            $table->string('water')->nullable();
            $table->string('electricity')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('geolokatsiya')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('kadastr_raqami')->nullable();

            // new fields
            $table->decimal('turar_joy_maydoni', 12, 2)->nullable();
            $table->decimal('noturar_joy_maydoni', 12, 2)->nullable();
            $table->text('vaqtinchalik_parking_info')->nullable();
            $table->text('doimiy_parking_info')->nullable();
            $table->text('maktabgacha_tashkilot_info')->nullable();
            $table->text('umumtaolim_maktab_info')->nullable();
            $table->text('stasionar_tibbiyot_info')->nullable();
            $table->text('ambulator_tibbiyot_info')->nullable();
            $table->text('diniy_muassasa_info')->nullable();
            $table->text('sport_soglomlashtirish_info')->nullable();
            $table->text('saqlanadigan_kokalamzor_info')->nullable();
            $table->text('yangidan_tashkil_kokalamzor_info')->nullable();
            $table->text('saqlanadigan_muhandislik_tarmoqlari_info')->nullable();
            $table->text('yangidan_quriladigan_muhandislik_tarmoqlari_info')->nullable();
            $table->text('saqlanadigan_yollar_info')->nullable();
            $table->text('yangidan_quriladigan_yollar_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktivs');
    }
}
