<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    public function index()
    {
        $jobs = Job::latest()->get();
        return view('public.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        return view('public.job-detail', compact('job'));
    }

    public function apply(Request $request, Job $job)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'portfolio_file' => 'nullable|file|mimes:pdf,zip|max:10240',
            'ktp_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'linkedin' => 'nullable|url',
        ]);

        $candidate = new Candidate($request->except(['cv_file', 'portfolio_file', 'ktp_file', 'kk_file']));
        $candidate->job_id = $job->id;
        $candidate->status = 'Applied';

        if ($request->hasFile('cv_file')) {
            $candidate->cv_file = $request->file('cv_file')->store('cvs', 'public');
        }
        if ($request->hasFile('portfolio_file')) {
            $candidate->portfolio_file = $request->file('portfolio_file')->store('portfolios', 'public');
        }
        if ($request->hasFile('ktp_file')) {
            $candidate->ktp_file = $request->file('ktp_file')->store('ktp', 'public');
        }
        if ($request->hasFile('kk_file')) {
            $candidate->kk_file = $request->file('kk_file')->store('kk', 'public');
        }

        $candidate->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Lamaran berhasil dikirim'
        ]);
    }

    public function downloadCvTemplate()
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('public.cv-template-ats')
            ->setPaper('a4', 'portrait');

        return $pdf->download('contoh-cv-ats-proper.pdf');
    }
}
