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
            $table->string('city')->nullable()->after('phone');
            $table->date('birthdate')->nullable()->after('city');
            $table->text('educational')->nullable()->after('birthdate');
            $table->text('job_history')->nullable()->after('educational');
            $table->text('skills')->nullable()->after('job_history');
            $table->text('summarize')->nullable()->after('skills');
            $table->string('vote')->nullable()->after('summarize');
            $table->text('consideration')->nullable()->after('vote');
            $table->string('cv_link')->nullable()->after('cv_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'city', 'birthdate', 'educational', 'job_history', 
                'skills', 'summarize', 'vote', 'consideration', 'cv_link'
            ]);
        });
    }
};
