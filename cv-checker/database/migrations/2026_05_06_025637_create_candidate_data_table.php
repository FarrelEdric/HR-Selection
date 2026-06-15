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
        Schema::create('candidate_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->date('birthdate')->nullable();
            $table->text('educational')->nullable();
            $table->text('job_history')->nullable();
            $table->text('skills')->nullable();
            $table->text('summarize')->nullable();
            $table->string('vote')->nullable();
            $table->text('consideration')->nullable();
            $table->string('cv_link')->nullable();
            $table->foreignId('job_id')->nullable()->constrained('jobs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_data');
    }
};
