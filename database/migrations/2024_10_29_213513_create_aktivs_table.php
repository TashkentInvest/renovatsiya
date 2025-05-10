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
        Schema::create('aktivs', function (Blueprint $table) {
            $table->id();

            // Location coordinates - both naming conventions for flexibility
            $table->decimal('latitude', 10, 7)->nullable(); // Original field
            $table->decimal('longitude', 10, 7)->nullable(); // Original field
            $table->decimal('lat', 10, 7)->nullable(); // Additional field for map compatibility
            $table->decimal('lng', 10, 7)->nullable(); // Additional field for map compatibility

            // Basic information
            $table->string('district_name')->nullable(); // Туман номи - District name
            $table->text('neighborhood_name')->nullable(); // Маҳалла фуқаролар йиғини номи - Neighborhood (mahalla) name
            $table->decimal('area_hectare', 15, 6)->nullable(); // Ҳудуд майдони (гектар) - Area in hectares
            $table->decimal('total_building_area', 15, 2)->nullable(); // Қурилиш ости майдони жами - Total building footprint area (m²)
            $table->decimal('residential_area', 15, 2)->nullable(); // турар - Residential building area (m²)
            $table->decimal('non_residential_area', 15, 2)->nullable(); // нотуратор - Non-residential building area (m²)
            $table->decimal('adjacent_area', 15, 2)->nullable(); // туташ ҳудуд - Adjacent area
            $table->text('object_information')->nullable(); // Ҳудуддаги объектлар тўғрисида маълумот - Information about objects in the area

            // Technical parameters
            $table->string('umn_coefficient')->nullable(); // УМН (Umumiy maydonga nisbati) - UMN ratio to total area
            $table->string('qmn_percentage')->nullable(); // ҚМН (Qurilish maydoniga nisbati) % - QMN ratio to construction area (%)
            $table->string('designated_floors')->nullable(); // Белгиланган қаватлар - Designated number of floors
            $table->string('proposed_floors')->nullable(); // Таклиф этилган қаватлар - Proposed number of floors
            $table->string('decision_number')->nullable(); // Қарор - Decision number
            $table->string('cadastre_certificate')->nullable(); // Кадастр далолатномаси (кадастр акт) - Cadastral certificate
            $table->string('area_strategy')->nullable(); // Ҳудуд стратегияси - Area strategy
            $table->string('investor')->nullable(); // Инвестор - Investor
            $table->string('status')->nullable(); // Статус - Project status

            // Demographic information
            $table->integer('population')->nullable(); // аҳоли сони - Population
            $table->integer('household_count')->nullable(); // хонадон сони - Number of households

            // Additional information
            $table->text('additional_information')->nullable(); // Қўшимча маълумот - Additional information

            // Additional fields from the Excel data
            $table->integer('single_house_count')->nullable(); // Якка тартиб уйлар (ИЖС) сони
            $table->decimal('single_house_area', 15, 2)->nullable(); // Якка тартиб уйлар майдони (м²)
            $table->integer('multi_story_house_count')->nullable(); // Кўп қаватли уйлар сони
            $table->decimal('multi_story_house_area', 15, 2)->nullable(); // Кўп қаватли уйлар майдони (м²)
            $table->integer('non_residential_count')->nullable(); // Нотурар объектлар сони
            $table->decimal('non_residential_building_area', 15, 2)->nullable(); // Нотурар объектлар майдони (м²)

            // Project documentation fields
            $table->string('area_passport')->nullable(); // Ҳудуд паспорт
            $table->string('protocol_number')->nullable(); // Протокол
            $table->string('land_assessment')->nullable(); // Оценка земельного участка
            $table->string('investment_contract')->nullable(); // Заключение Инвестиционного договра
            $table->string('public_discussion')->nullable(); // Народное слушание проекта реновации

            // Project timeline fields
            $table->date('resettlement_start')->nullable(); // Расселение (компенсация) Начало
            $table->date('resettlement_end')->nullable(); // Расселение (компенсация) Конец
            $table->date('project_start')->nullable(); // Начало проекта

            // Status fields
            $table->string('assessment_status')->nullable(); // оценка
            $table->string('announcement')->nullable(); // Эълон
            $table->string('zone')->nullable(); // Zona

            // Foreign key relations
            $table->unsignedBigInteger('sub_street_id')->nullable();
            $table->foreign('sub_street_id')->references('id')->on('sub_streets')->onDelete('cascade');

            $table->unsignedBigInteger('street_id')->nullable();
            $table->foreign('street_id')->references('id')->on('streets')->onDelete('cascade');

            // Audit fields
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('action', ['created', 'updated', 'deleted'])->nullable();
            $table->timestamp('action_timestamp')->nullable();

            // Indexes for frequently queried fields
            $table->index('district_name');
            $table->index('investor');
            $table->index('status');
            $table->index('zone');

            // Standard timestamps and soft deletes
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivs');
    }
};
