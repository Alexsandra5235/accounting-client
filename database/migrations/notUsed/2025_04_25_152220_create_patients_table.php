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
        Schema::create('classifiers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('diagnosis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('classifiers')->onDelete('cascade');
            $table->foreignId('wound_id')->constrained('classifiers')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')->constrained('diagnosis')->onDelete('cascade');
            $table->string('name');
            $table->date('birth_day');
            $table->string('gender');
            $table->string('medical_card');
            $table->string('passport')->nullable();
            $table->string('nationality')->nullable();
            $table->string('address')->nullable();
            $table->string('register_place')->nullable();
            $table->string('snils')->nullable();
            $table->string('polis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classifiers');
        Schema::dropIfExists('diagnosis');
        Schema::dropIfExists('patients');
    }
};
