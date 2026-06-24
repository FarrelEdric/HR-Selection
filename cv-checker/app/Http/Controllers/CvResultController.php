<?php

namespace App\Http\Controllers;

use App\Models\CvResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CvResultController extends Controller
{
    /**
     * Store a newly created CV analysis result from n8n.
     */
    public function store(Request $request)
    {
        Log::info('N8n CV Checker callback received', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'city' => 'required|string|max:255',
            'birthdate' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'score' => 'required|numeric|min:1|max:10',
            'consideration' => 'required|string',
            'summary' => 'required|string',
            'skills' => 'required|string',
            'education' => 'required|string',
            'job_history' => 'required|string',
            'cv_link' => 'required|url',
            'processed_at' => 'required|string',
        ]);
        
          $validated['score'] = (int) $validated['score'];

        // Parse processed_at explicitly using the incoming European/Indonesian format
        if (isset($validated['processed_at'])) {
            try {
                // Parse DD/MM/YYYY HH:ii:ss format explicitly
                $validated['processed_at'] = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $validated['processed_at']);
            } catch (\Exception $e) {
                try {
                    // Fallback to format without seconds if sent as 'd/m/Y H:i'
                    $validated['processed_at'] = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $validated['processed_at']);
                } catch (\Exception $ex) {
                    // Fallback to default Carbon parsing
                    $validated['processed_at'] = \Carbon\Carbon::parse($validated['processed_at']);
                }
            }
        }

        CvResult::create($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Saved'
        ], 200);
    }

    /**
     * Display a listing of CV analysis results.
     */
    public function index(Request $request)
    {
        $selectedPosition = $request->get('position');
        $selectedCity = $request->get('city');
        $selectedSearch = $request->get('search');
        $selectedStatus = $request->get('status');
        $selectedDate = $request->get('date');
        $selectedSort = $request->get('sort', 'score_date'); // Default to score_date

        // Query CV results
        $query = CvResult::query();

        // Apply Position filter
        if ($selectedPosition) {
            $query->filterByPosition($selectedPosition);
        }

        // Apply City/Tinggal filter
        if ($selectedCity) {
            $query->where('city', $selectedCity);
        }

        // Apply Search query (matches name, email, skills, education, position)
        if ($selectedSearch) {
            $query->where(function ($q) use ($selectedSearch) {
                $q->where('name', 'like', '%' . $selectedSearch . '%')
                  ->orWhere('email', 'like', '%' . $selectedSearch . '%')
                  ->orWhere('skills', 'like', '%' . $selectedSearch . '%')
                  ->orWhere('education', 'like', '%' . $selectedSearch . '%')
                  ->orWhere('position', 'like', '%' . $selectedSearch . '%');
            });
        }

        // Apply Status filter (based on score threshold from model accessor)
        if ($selectedStatus) {
            if ($selectedStatus === 'recommended') {
                $query->where('score', '>=', 8);
            } elseif ($selectedStatus === 'consider') {
                $query->where('score', '>=', 5)->where('score', '<', 8);
            } elseif ($selectedStatus === 'not_recommended') {
                $query->where('score', '<', 5);
            }
        }

        // Apply Date filter
        if ($selectedDate) {
            $query->whereDate(\Illuminate\Support\Facades\DB::raw('COALESCE(processed_at, CONVERT_TZ(created_at, "+00:00", "+07:00"))'), $selectedDate);
        }

        // Apply Sorting
        if ($selectedSort === 'date_desc') {
            $today = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $query->whereDate(\Illuminate\Support\Facades\DB::raw('COALESCE(processed_at, CONVERT_TZ(created_at, "+00:00", "+07:00"))'), $today)
                  ->orderBy(\Illuminate\Support\Facades\DB::raw('COALESCE(processed_at, created_at)'), 'desc');
        } elseif ($selectedSort === 'score_desc') {
            $query->orderBy('score', 'desc');
        } elseif ($selectedSort === 'score_asc') {
            $query->orderBy('score', 'asc');
        } else {
            // Default: score tertinggi & terbaru
            $query->orderBy('score', 'desc')->orderBy('created_at', 'desc');
        }

        $results = $query->paginate(10)->withQueryString();

        // Get unique positions for dropdown filter
        $positions = CvResult::select('position')
            ->whereNotNull('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        // Get unique cities for dropdown filter (Tinggal)
        $cities = CvResult::select('city')
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        // Overall total count of candidate results
        $totalCandidates = CvResult::count();

        // Flag to check if any filters are active
        $hasActiveFilters = $selectedPosition || $selectedCity || $selectedSearch || $selectedStatus || $selectedDate || $selectedSort !== 'score_date';

        return view('cv-results.index', compact(
            'results', 
            'positions', 
            'cities', 
            'totalCandidates', 
            'selectedPosition', 
            'selectedCity', 
            'selectedSearch', 
            'selectedStatus', 
            'selectedDate',
            'selectedSort',
            'hasActiveFilters'
        ));
    }
}
