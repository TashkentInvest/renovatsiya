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

            $table->string('district_name')->nullable(); // Туман номи - District name
            $table->text('start_lat')->nullable(); // start_lat - Starting latitude (coordinate)
            $table->text('start_lon')->nullable(); // start_lon - Starting longitude (coordinate)
            $table->text('end_lat')->nullable(); // end_lat - Ending latitude (coordinate)
            $table->text('end_lon')->nullable(); // end_lon - Ending longitude (coordinate)
            $table->string('neighborhood_name')->nullable(); // Маҳалла фуқаролар йиғини номи - Neighborhood (mahalla) name
            $table->decimal('area_hectare', 15, 6)->nullable(); // Ҳудуд майдони (гектар) - Area in hectares
            $table->decimal('total_building_area', 15, 2)->nullable(); // Қурилиш ости майдони жами - Total building footprint area (m²)
            $table->decimal('residential_area', 15, 2)->nullable(); // турар - Residential building area (m²)
            $table->decimal('non_residential_area', 15, 2)->nullable(); // нотуратор - Non-residential building area (m²)
            $table->decimal('adjacent_area', 15, 2)->nullable(); // туташ ҳудуд - Adjacent area
            $table->text('object_information')->nullable(); // Ҳудуддаги объектлар тўғрисида маълумот - Information about objects in the area
            $table->string('umn_coefficient')->nullable(); // УМН (Umumiy maydonga nisbati) - UMN ratio to total area
            $table->string('qmn_percentage')->nullable(); // ҚМН (Qurilish maydoniga nisbati) % - QMN ratio to construction area (%)
            $table->string('designated_floors')->nullable(); // Белгиланган қаватлар - Designated number of floors
            $table->string('proposed_floors')->nullable(); // Таклиф этилган қаватлар - Proposed number of floors
            $table->string('decision_number')->nullable(); // Қарор - Decision number
            $table->string('cadastre_certificate')->nullable(); // Кадастр далолатномаси (кадастр акт) - Cadastral certificate
            $table->string('area_strategy')->nullable(); // Ҳудуд стратегияси - Area strategy
            $table->string('investor')->nullable(); // Инвестор - Investor
            $table->string('status')->nullable(); // Статус - Project status
            $table->integer('population')->nullable(); // аҳоли сони - Population
            $table->integer('household_count')->nullable(); // хонадон сони - Number of households
            $table->text('additional_information')->nullable(); // Қўшимча маълумот - Additional information

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
