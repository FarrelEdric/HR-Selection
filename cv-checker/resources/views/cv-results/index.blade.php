@extends('layouts.admin')

@section('page-title', 'Hasil Analisis CV')

@section('content')
<div x-data="{ 
    open: false, 
    candidate: {} 
}" class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Hasil Analisis CV</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar kandidat yang telah dievaluasi secara otomatis oleh AI CV Checker.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <!-- Counter Badge -->
            <div class="bg-brand-50 text-brand px-4 py-2.5 rounded-xl text-sm font-semibold border border-brand-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Total: {{ $totalCandidates }} Kandidat
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
        <form action="{{ route('admin.cv-results.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Search Input -->
                <div class="relative sm:col-span-2 lg:col-span-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $selectedSearch }}" placeholder="Cari nama, email, keahlian..." 
                           class="w-full pl-9 pr-3 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-xs text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all">
                </div>

                <!-- Position Dropdown -->
                <div>
                    <select name="position" onchange="this.form.submit()" 
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand shadow-sm cursor-pointer">
                        <option value="">Semua Jabatan</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos }}" {{ $selectedPosition == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City (Tinggal) Dropdown -->
                <div>
                    <select name="city" onchange="this.form.submit()" 
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand shadow-sm cursor-pointer">
                        <option value="">Semua Kota (Tinggal)</option>
                        @foreach($cities as $ct)
                            <option value="{{ $ct }}" {{ $selectedCity == $ct ? 'selected' : '' }}>{{ $ct }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Dropdown -->
                <div>
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand shadow-sm cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="recommended" {{ $selectedStatus == 'recommended' ? 'selected' : '' }}>Recommended</option>
                        <option value="consider" {{ $selectedStatus == 'consider' ? 'selected' : '' }}>Consider</option>
                        <option value="not_recommended" {{ $selectedStatus == 'not_recommended' ? 'selected' : '' }}>Not Recommended</option>
                    </select>
                </div>

                <!-- Date Input -->
                <div>
                    <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                           class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand shadow-sm cursor-pointer">
                </div>

                <!-- Sort Dropdown -->
                <div>
                    <select name="sort" onchange="this.form.submit()" 
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs font-semibold text-brand focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand shadow-sm cursor-pointer">
                        <option value="score_date" {{ $selectedSort == 'score_date' ? 'selected' : '' }}>Score & Terbaru</option>
                        <option value="date_desc" {{ $selectedSort == 'date_desc' ? 'selected' : '' }}>Terbaru (Hari Ini)</option>
                        <option value="score_desc" {{ $selectedSort == 'score_desc' ? 'selected' : '' }}>Score Tertinggi</option>
                        <option value="score_asc" {{ $selectedSort == 'score_asc' ? 'selected' : '' }}>Score Terendah</option>
                    </select>
                </div>
            </div>

            <!-- Filter Action Bar (Submit & Reset) -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                <div class="text-xs text-gray-400">
                    @if($hasActiveFilters)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-brand-50 text-brand font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand animate-pulse"></span>
                            Filter Aktif
                        </span>
                    @else
                        Tampilkan data sesuai filter & pencarian.
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @if($hasActiveFilters)
                        <a href="{{ route('admin.cv-results.index') }}" 
                           class="inline-flex items-center gap-1 px-4 py-2 text-xs text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-all font-semibold border border-gray-100 bg-white">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3.58" />
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit" 
                            class="inline-flex items-center gap-1 px-4 py-2 bg-brand hover:bg-brand-600 text-white rounded-xl shadow-sm hover:shadow transition-all text-xs font-bold cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari & Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/75 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">City</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Birthdate</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Vote</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">CV Link</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Summarize</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($results as $result)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <!-- Date -->
                            <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $result->processed_at ? $result->processed_at->format('d M Y, H:i') : $result->created_at->format('d M Y, H:i') }}
                            </td>
                            <!-- Name -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $result->name }}</div>
                                <div class="text-xs text-gray-400 font-medium">{{ $result->position }}</div>
                            </td>
                            <!-- Phone -->
                            <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $result->phone }}
                            </td>
                            <!-- City -->
                            <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $result->city }}
                            </td>
                            <!-- Email -->
                            <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $result->email }}
                            </td>
                            <!-- Birthdate -->
                            <td class="px-4 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $result->birthdate ?? '-' }}
                            </td>
                            <!-- Vote -->
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $result->status_badge_color }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $result->score }}/10 - {{ $result->status_label }}
                                </span>
                            </td>
                            <!-- CV Link -->
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <a href="{{ $result->cv_link }}" target="_blank" 
                                    class="inline-flex items-center gap-1 px-3.5 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand text-xs font-semibold rounded-xl border border-brand-200/30 transition-all shadow-sm">
                                    Lihat CV
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </td>
                            <!-- Summarize -->
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <button type="button" 
                                    @click="candidate = @js($result); candidate.formatted_date = '{{ $result->processed_at ? $result->processed_at->format('d M Y, H:i') : $result->created_at->format('d M Y, H:i') }}'; open = true;"
                                    class="px-3.5 py-1.5 bg-gray-50 hover:bg-brand hover:text-white text-gray-700 text-xs font-semibold rounded-xl border border-gray-200/60 transition-all cursor-pointer shadow-sm">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-24 text-center text-gray-400 font-medium italic">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-16 h-16 bg-gray-50 flex items-center justify-center rounded-2xl border border-gray-100 text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500">Belum ada data hasil analisis CV.</p>
                                    @if($hasActiveFilters)
                                        <a href="{{ route('admin.cv-results.index') }}" class="text-xs text-brand hover:underline font-semibold mt-1">Hapus filter pencarian</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if($results->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
            {{ $results->links() }}
        </div>
        @endif
    </div>

    <!-- Interactive Alpine Detail Modal -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
         style="display: none;"
         x-cloak>
        
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>

        <!-- Modal Content Container -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden border border-gray-100 flex flex-col max-h-[90vh] z-10">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-800" x-text="candidate.name">Detail Kandidat</h3>
                    <p class="text-sm font-semibold text-brand mt-0.5" x-text="candidate.position">Posisi Jabatan</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dynamic Badge inside Modal Header -->
                    <span :class="candidate.status_badge_color" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        <span x-text="candidate.score + '/10'"></span>
                        <span class="mx-0.5">•</span>
                        <span x-text="candidate.status_label"></span>
                    </span>
                    <!-- Close button -->
                    <button @click="open = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Left Column: Personal Info & AI Analysis -->
                    <div class="space-y-6">
                        <!-- Personal Information -->
                        <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-5 space-y-4">
                            <h4 class="text-sm font-bold text-gray-800 border-b border-gray-200/60 pb-2">Informasi Kontak</h4>
                            
                            <div class="grid grid-cols-1 gap-3.5">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg border border-gray-200/60 text-gray-400 mt-0.5 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Email</div>
                                        <div class="text-sm text-gray-700 font-medium" x-text="candidate.email"></div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg border border-gray-200/60 text-gray-400 mt-0.5 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Telepon</div>
                                        <div class="text-sm text-gray-700 font-medium" x-text="candidate.phone"></div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg border border-gray-200/60 text-gray-400 mt-0.5 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Kota</div>
                                        <div class="text-sm text-gray-700 font-medium" x-text="candidate.city"></div>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg border border-gray-200/60 text-gray-400 mt-0.5 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Tanggal Lahir</div>
                                        <div class="text-sm text-gray-700 font-medium" x-text="candidate.birthdate || '-'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Summary -->
                        <div class="border border-gray-100 rounded-2xl p-5 space-y-3 bg-brand-50/30">
                            <div class="flex items-center gap-2 text-brand font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 .364l-.707 .707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548 .547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h0a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                Ringkasan AI HR
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap" x-text="candidate.summary"></p>
                        </div>
                    </div>

                    <!-- Right Column: Skills, Education, Job History, Consideration -->
                    <div class="space-y-6">
                        <!-- AI Consideration (Reasoning) -->
                        <div class="border border-amber-200/60 rounded-2xl p-5 space-y-3 bg-amber-50/30 text-amber-900">
                            <div class="flex items-center gap-2 font-bold text-sm text-amber-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pertimbangan Rekrutmen (AI)
                            </div>
                            <p class="text-sm leading-relaxed whitespace-pre-wrap" x-text="candidate.consideration"></p>
                        </div>

                        <!-- Skills -->
                        <div class="border border-gray-100 rounded-2xl p-5 space-y-3 bg-gray-50/30">
                            <h4 class="text-sm font-bold text-gray-800">Keahlian (Skills)</h4>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap" x-text="candidate.skills"></p>
                        </div>

                        <!-- Education -->
                        <div class="border border-gray-100 rounded-2xl p-5 space-y-3 bg-gray-50/30">
                            <h4 class="text-sm font-bold text-gray-800">Pendidikan</h4>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap" x-text="candidate.education"></p>
                        </div>

                        <!-- Job History -->
                        <div class="border border-gray-100 rounded-2xl p-5 space-y-3 bg-gray-50/30">
                            <h4 class="text-sm font-bold text-gray-800">Riwayat Pekerjaan</h4>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap" x-text="candidate.job_history"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-xs text-gray-400 font-medium">
                    Diproses pada: <span x-text="candidate.formatted_date"></span>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button" @click="open = false" 
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition-all cursor-pointer shadow-sm">
                        Tutup
                    </button>
                    <a :href="candidate.cv_link" target="_blank" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-brand hover:bg-brand-600 text-white text-sm font-bold rounded-xl shadow-md shadow-brand/20 transition-all cursor-pointer">
                        Lihat CV di Google Drive
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
