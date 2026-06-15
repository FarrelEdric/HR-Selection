<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CvResultApiController extends Controller
{
    public function store(Request $request)
    {
        Log::info('N8n API Callback received', $request->all());

        $data = $request->all();
        $results = [];

        // Handle n8n response structure: { data: { candidate_profile: [...] } }
        if (isset($data['data']['candidate_profile'])) {
            $results = $data['data']['candidate_profile'];
        } elseif (isset($data['candidate_profile'])) {
            $results = $data['candidate_profile'];
        } elseif (is_array($data) && isset($data[0])) {
            $results = $data;
        } else {
            $results = [$data];
        }

        foreach ($results as $item) {
            $this->saveResult($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Results saved successfully',
            'count' => count($results)
        ]);
    }

    protected function saveResult($data)
    {
        $cvResult = CvResult::create([
            'name' => $data['NAME'] ?? $data['name'] ?? 'Unknown',
            'email' => $data['EMAIL'] ?? $data['email'] ?? null,
            'skills' => $data['SKILLS'] ?? $data['skills'] ?? [],
            'education' => $data['EDUCATIONAL'] ?? $data['education'] ?? [],
            'job_history' => $data['JOB HISTORY'] ?? $data['job_history'] ?? [],
            'score' => $data['VOTE'] ?? $data['score'] ?? 0,
            'reasoning' => $data['CONSIDERATION'] ?? $data['reasoning'] ?? '',
            'summary' => $data['SUMMARIZE'] ?? $data['summary'] ?? '',
            'cv_link' => $data['LINK_CV'] ?? $data['cv_link'] ?? '',
        ]);

        // Also Save to CandidateData for the "Data Candidate" feature
        \App\Models\CandidateData::create([
            'name' => $data['NAME'] ?? $data['name'] ?? 'Unknown',
            'email' => $data['EMAIL'] ?? $data['email'] ?? 'unknown@example.com',
            'phone' => $data['PHONE'] ?? $data['phone'] ?? '-',
            'city' => $data['CITY'] ?? $data['city'] ?? '-',
            'birthdate' => $data['BIRTHDATE'] ?? null,
            'educational' => is_array($data['EDUCATIONAL'] ?? null) ? implode(", ", $data['EDUCATIONAL']) : ($data['EDUCATIONAL'] ?? ''),
            'job_history' => is_array($data['JOB HISTORY'] ?? null) ? implode(", ", $data['JOB HISTORY']) : ($data['JOB HISTORY'] ?? ''),
            'skills' => is_array($data['SKILLS'] ?? null) ? implode(", ", $data['SKILLS']) : ($data['SKILLS'] ?? ''),
            'summarize' => $data['SUMMARIZE'] ?? $data['summary'] ?? '',
            'vote' => $data['VOTE'] ?? $data['score'] ?? '',
            'consideration' => $data['CONSIDERATION'] ?? $data['reasoning'] ?? '',
            'cv_link' => $data['LINK_CV'] ?? $data['cv_link'] ?? '',
            'job_id' => 1,
        ]);

        return $cvResult;
    }
}
