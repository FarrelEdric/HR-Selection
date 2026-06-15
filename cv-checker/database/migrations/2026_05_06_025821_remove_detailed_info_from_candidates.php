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
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'city', 'birthdate', 'educational', 'job_history', 
                'skills', 'summarize', 'vote', 'consideration', 'cv_link'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('city')->nullable();
            $table->date('birthdate')->nullable();
            $table->text('educational')->nullable();
            $table->text('job_history')->nullable();
            $table->text('skills')->nullable();
            $table->text('summarize')->nullable();
            $table->string('vote')->nullable();
            $table->text('consideration')->nullable();
            $table->string('cv_link')->nullable();
        });
    }
};
