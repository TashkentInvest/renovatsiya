<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('polygon_aktivs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aktiv_id');
            $table->string('start_lat')->nullable();
            $table->string('start_lon')->nullable();
            $table->string('end_lat')->nullable();
            $table->string('end_lon')->nullable();
            $table->float('distance')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('aktiv_id')
                  ->references('id')
                  ->on('aktivs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polygon_aktivs');
    }
};
