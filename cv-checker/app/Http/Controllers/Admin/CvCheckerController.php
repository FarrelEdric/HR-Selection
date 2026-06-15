<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CvResult;
use App\Services\N8nService;
use Illuminate\Http\Request;

class CvCheckerController extends Controller
{
    protected $n8nService;

    public function __construct(N8nService $n8nService)
    {
        $this->n8nService = $n8nService;
    }

    public function index()
    {
        return view('admin.cv-checker.index');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'driveLink' => 'required|url',
            'folderName' => 'required|string',
            'profile_wanted' => 'required|string',
        ]);

        // Trigger n8n asynchronously
        $response = $this->n8nService->analyzeCV(
            $request->driveLink,
            $request->folderName,
            $request->profile_wanted
        );

        if ($response) {
            return redirect()->route('admin.cv-results.index')->with('success', 'Trigger Sukses! Proses analisis CV sedang berjalan di latar belakang (n8n).');
        }

        return back()->with('error', 'Gagal memicu n8n service. Silakan periksa koneksi atau alur n8n Anda.');
    }

    protected function saveResult($data)
    {
        // Save to CvResult (existing logic)
        $cvResult = CvResult::create([
            'name' => $data['NAME'] ?? $data['name'] ?? 'Unknown',
            'email' => $data['EMAIL'] ?? $data['email'] ?? null,
            'skills' => is_array($data['SKILLS'] ?? null) ? json_encode($data['SKILLS']) : ($data['SKILLS'] ?? ''),
            'education' => is_array($data['EDUCATIONAL'] ?? null) ? json_encode($data['EDUCATIONAL']) : ($data['EDUCATIONAL'] ?? ''),
            'job_history' => is_array($data['JOB HISTORY'] ?? null) ? json_encode($data['JOB HISTORY']) : ($data['JOB HISTORY'] ?? ''),
            'score' => $data['VOTE'] ?? 0,
            'reasoning' => $data['CONSIDERATION'] ?? '',
            'summary' => $data['SUMMARIZE'] ?? '',
            'cv_link' => $data['LINK_CV'] ?? '',
        ]);

        // Also Save to CandidateData for the "Data Candidate" feature
        return \App\Models\CandidateData::create([
            'name' => $data['NAME'] ?? $data['name'] ?? 'Unknown',
            'email' => $data['EMAIL'] ?? $data['email'] ?? 'unknown@example.com',
            'phone' => $data['PHONE'] ?? $data['phone'] ?? '-',
            'city' => $data['CITY'] ?? $data['city'] ?? '-',
            'birthdate' => $data['BIRTHDATE'] ?? null,
            'educational' => is_array($data['EDUCATIONAL'] ?? null) ? implode(", ", $data['EDUCATIONAL']) : ($data['EDUCATIONAL'] ?? ''),
            'job_history' => is_array($data['JOB HISTORY'] ?? null) ? implode(", ", $data['JOB HISTORY']) : ($data['JOB HISTORY'] ?? ''),
            'skills' => is_array($data['SKILLS'] ?? null) ? implode(", ", $data['SKILLS']) : ($data['SKILLS'] ?? ''),
            'summarize' => $data['SUMMARIZE'] ?? '',
            'vote' => $data['VOTE'] ?? '',
            'consideration' => $data['CONSIDERATION'] ?? '',
            'cv_link' => $data['LINK_CV'] ?? '',
            'job_id' => 1, // Default or handle properly if possible
        ]);
    }

    public function results(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $results = \App\Models\CandidateData::latest()->paginate($perPage)->withQueryString();
        return view('admin.cv-checker.results', compact('results'));
    }
}
