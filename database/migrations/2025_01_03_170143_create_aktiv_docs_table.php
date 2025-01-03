<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAktivDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktiv_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aktiv_id');
            $table->foreign('aktiv_id')
                ->references('id')
                ->on('aktivs')
                ->onDelete('cascade');

            // Example doc_type (you can store the type of file: '1-etap-protokol', 'elon', etc.)
            $table->string('doc_type', 50)->nullable();


            $table->string('path'); // Path to the uploaded document
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
        Schema::dropIfExists('aktiv_docs');
    }
}
