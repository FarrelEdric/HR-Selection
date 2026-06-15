<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Candidate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalCandidates = Candidate::count();
        $totalJobs = Job::count();
        $recentCandidates = Candidate::with('job')->latest()->take(5)->get();
        
        $acceptedCount = Candidate::where('status', 'accepted')->count();
        $pendingCount = Candidate::where('status', 'pending')->count();

        // Handle date filtering for the chart
        $startDate = $request->filled('start_date') ? now()->parse($request->start_date) : now()->subDays(6);
        $endDate = $request->filled('end_date') ? now()->parse($request->end_date) : now();

        $labels = [];
        $data = [];
        
        $current = clone $startDate;
        while ($current->lte($endDate)) {
            $labels[] = $current->format('d M');
            $data[] = Candidate::whereDate('created_at', $current->toDateString())->count();
            $current->addDay();
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data
        ];

        // Job Interest Data (Candidates per Job)
        $jobInterest = Job::withCount('candidates')->orderBy('candidates_count', 'desc')->get();

        return view('admin.dashboard', compact(
            'totalCandidates', 
            'totalJobs', 
            'recentCandidates', 
            'chartData',
            'acceptedCount',
            'pendingCount',
            'startDate',
            'endDate',
            'jobInterest'
        ));
    }

    public function candidates(Request $request)
    {
        $query = Candidate::with('job');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $perPage = $request->get('per_page', 10);
        $candidates = $query->latest()->paginate($perPage)->withQueryString();
        $jobs = Job::orderBy('title')->get();

        return view('admin.candidates.index', compact('candidates', 'jobs'));
    }

    public function candidateDetail(Candidate $candidate)
    {
        return view('admin.candidates.show', compact('candidate'));
    }

    public function candidateDestroy(Candidate $candidate)
    {
        $candidate->delete();
        return redirect()->route('admin.candidates.index')->with('success', 'Applicant deleted successfully.');
    }
}
