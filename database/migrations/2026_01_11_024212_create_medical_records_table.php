<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Bidan/Staff yang memeriksa
            $table->date('exam_date');
            $table->enum('type', ['ANC', 'INC', 'PNC', 'KB', 'Imunisasi', 'Umum'])->default('Umum');

            // Tanda Vital
            $table->decimal('blood_pressure_systolic', 5, 2)->nullable();
            $table->decimal('blood_pressure_diastolic', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable(); // kg
            $table->decimal('height', 5, 2)->nullable(); // cm
            $table->decimal('temperature', 4, 2)->nullable(); // celcius
            $table->integer('pulse')->nullable();

            // Anamnesa & Diagnosa
            $table->text('complaint')->nullable(); // Keluhan
            $table->text('diagnosis')->nullable();
            $table->string('icd_code')->nullable(); // ICD-10 code
            $table->text('treatment')->nullable(); // Tindakan
            $table->text('notes')->nullable();

            // ANC Specific
            $table->date('hpht')->nullable(); // Hari Pertama Haid Terakhir
            $table->date('hpl')->nullable(); // Hari Perkiraan Lahir
            $table->integer('pregnancy_week')->nullable();
            $table->integer('fetal_heart_rate')->nullable();
            $table->decimal('fundal_height', 5, 2)->nullable();
            $table->enum('fetal_position', ['kepala', 'sungsang', 'lintang'])->nullable();

            // KB Specific
            $table->string('kb_method')->nullable();
            $table->date('kb_next_visit')->nullable();

            // Imunisasi Specific
            $table->string('vaccine_type')->nullable();
            $table->string('vaccine_batch')->nullable();
            $table->date('next_vaccine_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
