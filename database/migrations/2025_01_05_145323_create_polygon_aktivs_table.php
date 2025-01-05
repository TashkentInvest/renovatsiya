<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolygonAktivsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polygon_aktivs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aktiv_id')->nullable(); // Foreign key to Aktiv
            $table->string('start_lat')->nullable();
            $table->string('start_lon')->nullable();
            $table->string('end_lat')->nullable();
            $table->string('end_lon')->nullable();
            $table->string('distance')->nullable();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('aktiv_id')->references('id')->on('aktivs')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polygon_aktivs');
    }
}
