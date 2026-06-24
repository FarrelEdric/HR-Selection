<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CvCheckerController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\CandidateDataController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvResultController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/download-cv-ats', [PublicController::class, 'downloadCvTemplate'])->name('download.cv-template');
Route::get('/job/{job}', [PublicController::class, 'show'])->name('job.show');
Route::post('/job/{job}/apply', [PublicController::class, 'apply'])->name('job.apply');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Job Management
    Route::resource('jobs', JobController::class);

    // Candidates
    Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates.index');
    Route::get('/candidates/{candidate}', [AdminController::class, 'candidateDetail'])->name('candidates.show');
    Route::delete('/candidates/{candidate}', [AdminController::class, 'candidateDestroy'])->name('candidates.destroy');
    Route::get('/candidates/{candidate}/file/{type}', [AdminController::class, 'viewFile'])->name('candidates.view-file');

    // Candidate Data (New Feature)
    Route::get('/candidate-data/export', [CandidateDataController::class, 'exportCsv'])->name('candidate-data.export');
    Route::get('/candidate-data/export-pdf', [CandidateDataController::class, 'exportPdf'])->name('candidate-data.export-pdf');
    Route::post('/candidate-data/import', [CandidateDataController::class, 'import'])->name('candidate-data.import');
    Route::post('/candidate-data/bulk-delete', [\App\Http\Controllers\Admin\CandidateDataController::class, 'bulkDelete'])->name('candidate-data.bulk-delete');
    Route::resource('candidate-data', \App\Http\Controllers\Admin\CandidateDataController::class)->except(['create', 'store']);

    // CV Checker
    Route::get('/cv-checker', [CvCheckerController::class, 'index'])->name('cv-checker.index');
    Route::post('/cv-checker/analyze', [CvCheckerController::class, 'analyze'])->name('cv-checker.analyze');
    Route::get('/cv-results', [CvResultController::class, 'index'])->name('cv-results.index');
});
