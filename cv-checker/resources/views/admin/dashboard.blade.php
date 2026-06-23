@extends('layouts.admin')

@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-brand/10 rounded-2xl flex items-center justify-center text-brand">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Candidates</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalCandidates }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Active Jobs</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalJobs }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <h3 class="text-lg font-bold text-gray-800">Applicant Statistics</h3>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-xl px-3 py-1.5">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">From</span>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-transparent border-none text-xs font-semibold text-gray-700 outline-none focus:ring-0 p-0">
                </div>
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-xl px-3 py-1.5">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">To</span>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-transparent border-none text-xs font-semibold text-gray-700 outline-none focus:ring-0 p-0">
                </div>
                <button type="submit" class="p-2 bg-brand text-white rounded-xl hover:bg-brand-600 transition-colors shadow-lg shadow-brand/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
        <div class="h-[300px]">
            <canvas id="statsChart"></canvas>
        </div>

        <!-- Job Interest Breakdown -->
        <div class="mt-12 border-t border-gray-100 pt-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Job Interest Breakdown</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                @foreach($jobInterest as $job)
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700">{{ $job->title }}</span>
                            <span class="text-xs font-bold text-brand">{{ $job->candidates_count }} Applicants</span>
                        </div>
                        <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand rounded-full transition-all duration-1000" style="width: {{ $totalCandidates > 0 ? ($job->candidates_count / $totalCandidates) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Candidates -->
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Recent Applicants</h3>
        <div class="space-y-6">
            @forelse($recentCandidates as $candidate)
                <div class="flex items-center gap-4 group">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-brand font-bold text-xs group-hover:bg-brand group-hover:text-white transition-colors">
                        {{ strtoupper(substr($candidate->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $candidate->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $candidate->job->title }}</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 bg-brand/10 text-brand rounded-full uppercase">{{ $candidate->status }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-10">No applicants yet.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.candidates.index') }}" class="block text-center mt-8 text-sm font-bold text-brand hover:underline">View All Candidates</a>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('statsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Applicants Count',
                data: {!! json_encode($chartData['data']) !!},
                borderColor: '#FF6B00',
                backgroundColor: 'rgba(255, 107, 0, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#FF6B00',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        display: true,
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
