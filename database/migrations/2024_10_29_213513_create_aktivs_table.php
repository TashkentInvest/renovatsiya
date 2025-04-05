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

            $table->unsignedBigInteger('street_id')->nullable();
            $table->foreign('street_id')->references('id')->on('streets')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('action', ['created', 'updated', 'deleted'])->nullable();
            $table->timestamp('action_timestamp')->nullable();
            $table->softDeletes();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('district_name')->nullable();
            $table->text('start_lat')->nullable();
            $table->text('start_lon')->nullable();
            $table->text('end_lat')->nullable();
            $table->text('end_lon')->nullable();
            $table->string('neighborhood_name')->nullable();
            $table->decimal('area_hectare', 15, 6)->nullable();
            $table->decimal('total_building_area', 15, 2)->nullable();
            $table->decimal('residential_area', 15, 2)->nullable();
            $table->decimal('non_residential_area', 15, 2)->nullable();
            $table->decimal('adjacent_area', 15, 2)->nullable();
            $table->text('object_information')->nullable();
            $table->string('umn_coefficient')->nullable();
            $table->string('qmn_percentage')->nullable();
            $table->string('designated_floors')->nullable();
            $table->string('proposed_floors')->nullable();
            $table->string('decision_number')->nullable();
            $table->string('cadastre_certificate')->nullable();
            $table->string('area_strategy')->nullable();
            $table->string('investor')->nullable();
            $table->string('status')->nullable();
            $table->integer('population')->nullable();
            $table->integer('household_count')->nullable();
            $table->text('additional_information')->nullable();
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
