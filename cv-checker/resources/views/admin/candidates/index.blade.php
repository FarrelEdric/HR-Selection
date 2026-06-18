@extends('layouts.admin')

@section('page-title', 'Applicants')

@section('content')
<!-- Filter Section -->
<div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm mb-8">
    <form action="{{ route('admin.candidates.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Search Name / Email</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Type name..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Filter Position</label>
            <select name="job_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm appearance-none">
                <option value="">All Positions</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Filter Date</label>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-6 py-3 bg-brand text-white text-sm font-bold rounded-xl shadow-lg shadow-brand/20 hover:bg-brand-600 transition-all">
                Apply
            </button>
            <a href="{{ route('admin.candidates.index') }}" class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all" title="Reset Filters">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Applicant List</h3>
            <p class="text-sm text-gray-500">
                @if(request()->anyFilled(['search', 'job_id', 'date']))
                    Showing filter results ({{ $candidates->total() }} applicants found)
                @else
                    View and manage all incoming applications.
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Show</span>
            <form action="{{ route('admin.candidates.index') }}" method="GET" class="inline-flex items-center">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('job_id'))
                    <input type="hidden" name="job_id" value="{{ request('job_id') }}">
                @endif
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <div class="relative">
                    <select name="per_page" onchange="this.form.submit()" class="pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-xs font-bold text-gray-600 appearance-none cursor-pointer">
                        @foreach([10, 25, 50, 100] as $count)
                            <option value="{{ $count }}" {{ (request('per_page') ?? 10) == $count ? 'selected' : '' }}>{{ $count }} Rows</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Candidate</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Position</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Documents</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Apply Date</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($candidates as $candidate)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-brand/10 rounded-full flex items-center justify-center text-brand font-bold">
                                {{ strtoupper(substr($candidate->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $candidate->name }}</p>
                                <p class="text-xs text-gray-500">{{ $candidate->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-sm font-semibold text-gray-700">{{ $candidate->job->title }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <p class="text-sm text-gray-600">{{ $candidate->phone }}</p>
                        @if($candidate->linkedin)
                        <a href="{{ $candidate->linkedin }}" target="_blank" class="text-xs text-blue-500 hover:underline">LinkedIn Profile</a>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <button onclick="openDocModal({{ $candidate->id }})" class="flex items-center gap-2 px-4 py-2 bg-brand/5 text-brand text-xs font-bold rounded-xl hover:bg-brand hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Documents
                        </button>
                    </td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-brand/10 text-brand text-[10px] font-bold rounded-full uppercase">{{ $candidate->status }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-xs text-gray-500 font-medium">{{ $candidate->created_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <form id="delete-form-{{ $candidate->id }}" action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('{{ $candidate->id }}')" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Document Modal -->
                <div id="docModal-{{ $candidate->id }}" class="fixed inset-0 z-[100] hidden">
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeDocModal({{ $candidate->id }})"></div>
                    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                        <div class="bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden pointer-events-auto flex flex-col max-h-[90vh]">
                            <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Candidate Documents</h2>
                                    <p class="text-sm text-gray-500">{{ $candidate->name }} - {{ $candidate->job->title }}</p>
                                </div>
                                <button onclick="closeDocModal({{ $candidate->id }})" class="p-2 hover:bg-gray-200 rounded-full transition-colors">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto p-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- CV -->
                                    <div class="p-6 bg-white border border-gray-100 rounded-3xl shadow-sm hover:border-brand/30 transition-all group">
                                        <div class="flex justify-between items-start mb-6">
                                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'cv']) }}" target="_blank" class="px-4 py-2 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl hover:bg-brand hover:text-white transition-all">View</a>
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'cv', 'download' => 1]) }}" class="p-2 bg-gray-50 text-gray-700 rounded-xl hover:bg-brand hover:text-white transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Curriculum Vitae (CV)</h4>
                                        <p class="text-xs text-gray-500">Required document</p>
                                    </div>

                                    <!-- Portfolio -->
                                    <div class="p-6 bg-white border border-gray-100 rounded-3xl shadow-sm hover:border-brand/30 transition-all group {{ !$candidate->portfolio_file ? 'opacity-50 grayscale' : '' }}">
                                        <div class="flex justify-between items-start mb-6">
                                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            @if($candidate->portfolio_file)
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'portfolio']) }}" target="_blank" class="px-4 py-2 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl hover:bg-brand hover:text-white transition-all">View</a>
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'portfolio', 'download' => 1]) }}" class="p-2 bg-gray-50 text-gray-700 rounded-xl hover:bg-brand hover:text-white transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Portfolio</h4>
                                        <p class="text-xs text-gray-500">{{ $candidate->portfolio_file ? 'File available' : 'Not attached' }}</p>
                                    </div>

                                    <!-- KTP -->
                                    <div class="p-6 bg-white border border-gray-100 rounded-3xl shadow-sm hover:border-brand/30 transition-all group {{ !$candidate->ktp_file ? 'opacity-50 grayscale' : '' }}">
                                        <div class="flex justify-between items-start mb-6">
                                            <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            @if($candidate->ktp_file)
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'ktp']) }}" target="_blank" class="px-4 py-2 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl hover:bg-brand hover:text-white transition-all">View</a>
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'ktp', 'download' => 1]) }}" class="p-2 bg-gray-50 text-gray-700 rounded-xl hover:bg-brand hover:text-white transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Identity Card (KTP)</h4>
                                        <p class="text-xs text-gray-500">{{ $candidate->ktp_file ? 'File available' : 'Not attached' }}</p>
                                    </div>

                                    <!-- KK -->
                                    <div class="p-6 bg-white border border-gray-100 rounded-3xl shadow-sm hover:border-brand/30 transition-all group {{ !$candidate->kk_file ? 'opacity-50 grayscale' : '' }}">
                                        <div class="flex justify-between items-start mb-6">
                                            <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            @if($candidate->kk_file)
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'kk']) }}" target="_blank" class="px-4 py-2 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl hover:bg-brand hover:text-white transition-all">View</a>
                                                <a href="{{ route('admin.candidates.view-file', ['candidate' => $candidate->id, 'type' => 'kk', 'download' => 1]) }}" class="p-2 bg-gray-50 text-gray-700 rounded-xl hover:bg-brand hover:text-white transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-1">Family Card (KK)</h4>
                                        <p class="text-xs text-gray-500">{{ $candidate->kk_file ? 'File available' : 'Not attached' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center text-gray-500 font-medium">No applicants found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-8 bg-gray-50 border-t border-gray-100">
        {{ $candidates->links() }}
    </div>
</div>

@push('scripts')
<script>
    function openDocModal(id) {
        document.getElementById('docModal-' + id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDocModal(id) {
        document.getElementById('docModal-' + id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This applicant record will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fb7185',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            borderRadius: '1.5rem'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection
