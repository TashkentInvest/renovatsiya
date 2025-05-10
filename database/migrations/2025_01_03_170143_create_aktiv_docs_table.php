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
        Schema::create('aktiv_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aktiv_id');
            $table->string('doc_type')->nullable();
            $table->string('path')->nullable();
            $table->string('filename')->nullable(); // Added to store the original filename
            $table->string('url')->nullable(); // Added to store the full URL
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
        Schema::dropIfExists('aktiv_docs');
    }
};
