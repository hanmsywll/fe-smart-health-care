<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->index();
            $table->string('hospital')->nullable();
            $table->string('doctor_name')->nullable();
            $table->date('date_of_visit')->nullable();
            $table->text('diagnosis')->nullable();
            $table->json('prescriptions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};