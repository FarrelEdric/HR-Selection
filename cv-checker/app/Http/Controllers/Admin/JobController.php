<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::withCount('candidates');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 10);
        $jobs = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'qualification' => 'required|string',
            'benefit' => 'nullable|string',
            'location' => 'required|string|max:255',
        ]);

        Job::create($request->all());

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil ditambahkan');
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'qualification' => 'required|string',
            'benefit' => 'nullable|string',
            'location' => 'required|string|max:255',
        ]);

        $job->update($request->all());

        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil diupdate');
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Lowongan berhasil dihapus');
    }
}
