@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6 tracking-tight">
                Find Your <span class="text-brand">Dream Career</span> Here
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
                Join our team and build a better future together with an innovative and fast-growing company.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#jobs" class="px-8 py-4 bg-brand text-white font-bold rounded-2xl shadow-xl shadow-brand/30 hover:bg-brand-600 hover:scale-105 transition-all">View Vacancies</a>
                <a href="#" class="px-8 py-4 bg-gray-50 text-gray-700 font-bold rounded-2xl hover:bg-gray-100 transition-all">About Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Jobs Section -->
<section id="jobs" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Latest Vacancies</h2>
                <p class="text-gray-500">Choose the position that matches your skills and passion.</p>
            </div>
            <div class="hidden md:block">
                <span class="text-sm font-semibold text-brand bg-brand/10 px-4 py-2 rounded-full">{{ $jobs->count() }} Positions Available</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jobs as $job)
                <div class="group p-8 bg-white border border-gray-100 rounded-3xl hover:border-brand/30 hover:shadow-2xl hover:shadow-brand/5 transition-all duration-300 relative">
                    <div class="mb-6 flex justify-between items-start">
                        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-brand-50 transition-colors">
                            <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full">Full-time</span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-brand transition-colors">{{ $job->title }}</h3>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $job->location }}
                        </div>
                        <div class="flex items-center gap-1 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            New
                        </div>
                    </div>

                    <p class="text-gray-500 text-sm mb-8 line-clamp-2">
                        {{ Str::limit($job->description, 100) }}
                    </p>

                    <a href="{{ route('job.show', $job->id) }}" class="flex items-center justify-center gap-2 w-full py-4 bg-gray-50 text-gray-900 font-bold rounded-2xl hover:bg-brand hover:text-white transition-all group/btn">
                        Position Details
                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3"></path>
                        </svg>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No active vacancies at this time.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
