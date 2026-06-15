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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('cv_file');
            $table->string('portfolio_file')->nullable();
            $table->string('ktp_file')->nullable();
            $table->string('kk_file')->nullable();
            $table->string('linkedin')->nullable();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('Applied');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
