<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidateData;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class CandidateDataController extends Controller
{
    public function index(Request $request)
    {
        $query = CandidateData::with('job');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $perPage = $request->get('per_page', 10);
        $candidates = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.candidate-data.index', compact('candidates'));
    }

    public function create()
    {
        $jobs = Job::all();
        return view('admin.candidate-data.form', compact('jobs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'birthdate' => 'nullable|date',
            'job_id' => 'required|exists:jobs,id',
            'educational' => 'nullable|string',
            'job_history' => 'nullable|string',
            'skills' => 'nullable|string',
            'summarize' => 'nullable|string',
            'vote' => 'nullable|string',
            'consideration' => 'nullable|string',
            'cv_link' => 'nullable|string',
        ]);

        CandidateData::create($validated);

        return redirect()->route('admin.candidate-data.index')->with('success', 'Candidate data created successfully.');
    }

    public function edit(CandidateData $candidate)
    {
        $jobs = Job::all();
        return view('admin.candidate-data.form', compact('candidate', 'jobs'));
    }

    public function show(CandidateData $candidate)
    {
        $candidate->load('job');

        return view('admin.candidate-data.show', compact('candidate'));
    }

    public function update(Request $request, CandidateData $candidate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'birthdate' => 'nullable|date',
            'job_id' => 'required|exists:jobs,id',
            'educational' => 'nullable|string',
            'job_history' => 'nullable|string',
            'skills' => 'nullable|string',
            'summarize' => 'nullable|string',
            'vote' => 'nullable|string',
            'consideration' => 'nullable|string',
            'cv_link' => 'nullable|string',
        ]);

        $candidate->update($validated);

        return redirect()->route('admin.candidate-data.index')->with('success', 'Candidate data updated successfully.');
    }

    public function destroy(CandidateData $candidate)
    {
        $candidate->delete();
        return redirect()->route('admin.candidate-data.index')->with('success', 'Candidate data deleted successfully.');
    }

    public function export()
    {
        $candidates = CandidateData::with('job')->get();
        $filename = "candidates_" . date('Y-m-d_H-i-s') . ".csv";

        $handle = fopen('php://output', 'w');
        fputcsv($handle, [
            'DATE',
            'NAME',
            'PHONE',
            'CITY',
            'EMAIL',
            'BIRTHDATE',
            'EDUCATIONAL',
            'JOB HISTORY',
            'SKILLS',
            'SUMMARIZE',
            'VOTE',
            'CONSIDERATION',
            'LINK_CV'
        ]);

        foreach ($candidates as $candidate) {
            fputcsv($handle, [
                $candidate->created_at->format('Y-m-d'),
                $candidate->name,
                $candidate->phone,
                $candidate->city,
                $candidate->email,
                $candidate->birthdate,
                $candidate->educational,
                $candidate->job_history,
                $candidate->skills,
                $candidate->summarize,
                $candidate->vote,
                $candidate->consideration,
                $candidate->cv_link
            ]);
        }

        fclose($handle);

        return Response::make('', 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = \App\Models\CandidateData::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('city', 'like', "%$search%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $candidates = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.candidate-data.pdf', compact('candidates'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('candidate-report-' . date('Y-m-d') . '.pdf');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $tempCsv = tempnam(sys_get_temp_dir(), 'import_') . '.csv';

        // 1. Handle Excel (.xlsx, .xls) via Node.js converter
        if (in_array($extension, ['xlsx', 'xls'])) {
            $scriptPath = base_path('convert_excel.js');
            $command = "node \"$scriptPath\" \"$path\" \"$tempCsv\" 2>&1";
            $output = shell_exec($command);

            if (!file_exists($tempCsv) || filesize($tempCsv) === 0) {
                return redirect()->back()->with('error', 'Failed to convert Excel file: ' . ($output ?: 'Unknown error'));
            }
            $importPath = $tempCsv;
        } else {
            $importPath = $path;
        }

        // 2. Read File & Detect Delimiter
        $content = file_get_contents($importPath);
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        $lines = explode("\n", str_replace("\r", "", $content));
        $firstLine = $lines[0] ?? '';
        $semicolons = substr_count($firstLine, ';');
        $commas = substr_count($firstLine, ',');
        $delimiter = ($semicolons > $commas) ? ';' : ',';

        $handle = fopen($importPath, 'r');
        $rawHeader = fgetcsv($handle, 0, $delimiter);

        if (!$rawHeader) {
            if (isset($tempCsv)) @unlink($tempCsv);
            return redirect()->back()->with('error', 'Could not read file header.');
        }

        // 3. Smart Header Mapping (Bilingual & Robust)
        $normalize = function ($str) {
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
            $str = strtolower(trim($str));
            return preg_replace('/[^a-z0-9]/', '', $str);
        };

        $header = array_map($normalize, $rawHeader);
        $actualMap = [];

        foreach ($header as $index => $norm) {
            if (empty($norm)) continue;

            // Map common Indonesian and English headers
            if (in_array($norm, ['name', 'nama', 'fullname', 'namalengkap'])) $actualMap['name'] = $index;
            if (in_array($norm, ['phone', 'telp', 'telepon', 'nohp', 'phone_number', 'mobile'])) $actualMap['phone'] = $index;
            if (in_array($norm, ['email', 'surel'])) $actualMap['email'] = $index;
            if (in_array($norm, ['city', 'kota', 'domisili', 'location'])) $actualMap['city'] = $index;
            if (str_contains($norm, 'birth') || str_contains($norm, 'lahir')) $actualMap['birthdate'] = $index;
            if (str_contains($norm, 'educat') || str_contains($norm, 'pendidikan')) $actualMap['educational'] = $index;
            if (str_contains($norm, 'history') || str_contains($norm, 'pengalaman') || str_contains($norm, 'job')) $actualMap['job_history'] = $index;
            if (str_contains($norm, 'skill') || str_contains($norm, 'keahlian') || str_contains($norm, 'kemampuan')) $actualMap['skills'] = $index;
            if (str_contains($norm, 'summa') || str_contains($norm, 'ringkasan')) $actualMap['summarize'] = $index;
            if (str_contains($norm, 'vote') || str_contains($norm, 'nilai')) $actualMap['vote'] = $index;
            if (str_contains($norm, 'consider') || str_contains($norm, 'pertimbangan')) $actualMap['consideration'] = $index;
            if (str_contains($norm, 'cv') || str_contains($norm, 'link') || str_contains($norm, 'file')) $actualMap['cv_link'] = $index;
        }

        if (!isset($actualMap['name'])) {
            if (isset($tempCsv)) @unlink($tempCsv);
            $found = implode(', ', array_filter($rawHeader));
            return redirect()->back()->with('error', "Kolom 'NAME' tidak ditemukan. Sistem mendeteksi kolom: [$found]. Mohon pastikan judul kolom benar.");
        }

        // 4. Import Data
        $imported = 0;
        $skipped = 0;
        while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            if (empty($data) || count($data) < 1) continue;

            $candidateData = [];
            foreach ($actualMap as $field => $index) {
                if (isset($data[$index])) {
                    $val = trim($data[$index]);
                    if ($val === '') {
                        $candidateData[$field] = null;
                    } elseif ($field === 'birthdate') {
                        $timestamp = strtotime($val);
                        $candidateData[$field] = $timestamp ? date('Y-m-d', $timestamp) : null;
                    } else {
                        $candidateData[$field] = $val;
                    }
                }
            }

            if (empty($candidateData['name'])) {
                $skipped++;
                continue;
            }

            $candidateData['job_id'] = Job::value('id');
            CandidateData::create($candidateData);
            $imported++;
        }

        fclose($handle);
        if (isset($tempCsv)) @unlink($tempCsv);

        if ($imported === 0) {
            return redirect()->back()->with('error', "Import gagal: Ditemukan $skipped baris tapi kolom 'NAME' kosong semua.");
        }

        return redirect()->back()->with('success', "Berhasil mengimport $imported kandidat.");
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No records selected.');
        }

        CandidateData::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' candidates deleted successfully.');
    }
}
