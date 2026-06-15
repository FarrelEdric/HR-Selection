@extends('layouts.admin')

@section('page-title', 'Candidate')

@section('content')
    <!-- Filter Section -->
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Candidate Repository</h3>
                <p class="text-sm text-gray-500">Manage and filter through all analyzed candidate data.</p>
            </div>
            <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full lg:w-auto">
                <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="flex-1 sm:flex-none px-5 py-2.5 bg-gray-50 text-gray-700 text-sm font-bold rounded-xl border border-gray-200 hover:bg-gray-100 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import Excel
                </button>
                <!-- Export Dropdown -->
                <div class="relative inline-block text-left flex-1 sm:flex-none" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="w-full px-5 py-2.5 bg-white text-gray-700 text-sm font-bold rounded-xl border border-gray-200 hover:border-brand hover:text-brand transition-all flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export Data
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                        class="absolute right-0 mt-3 w-56 bg-white rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 z-[100] overflow-hidden origin-top-right">
                        <div class="p-2">
                            <a href="{{ route('admin.candidate-data.export', request()->all()) }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 rounded-xl transition-colors group">
                                <div
                                    class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600 group-hover:bg-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-bold text-gray-800">Export Excel</span>
                                    <span class="text-[11px] text-gray-400">CSV Spreadsheet</span>
                                </div>
                            </a>
                            <div class="h-px bg-gray-50 my-1"></div>
                            <a href="{{ route('admin.candidate-data.export-pdf', request()->all()) }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-red-50 rounded-xl transition-colors group">
                                <div
                                    class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-600 group-hover:bg-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-bold text-gray-800">Export PDF</span>
                                    <span class="text-[11px] text-gray-400">Document File</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <form action="{{ route('admin.candidate-data.index') }}" method="GET"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end border-t border-gray-50 pt-8">
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, City..."
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-brand text-white text-sm font-bold rounded-xl shadow-lg shadow-brand/20 hover:bg-brand-600 transition-all">
                    Apply Filters
                </button>
                <a href="{{ route('admin.candidate-data.index') }}"
                    class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all"
                    title="Reset Filters">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" id="selectAll"
                        class="w-5 h-5 rounded-md border-gray-300 text-brand focus:ring-brand transition-all cursor-pointer">
                    <span class="text-sm font-bold text-gray-600 group-hover:text-gray-900 transition-colors">Select
                        All</span>
                </label>
            </div>

            <!-- Bulk Action Button (Hidden by default, slides in) -->
            <div id="bulkActionContainer" class="hidden transform transition-all duration-300 translate-y-2 opacity-0">
                <button type="button" onclick="confirmBulkDelete()"
                    class="px-5 py-2.5 bg-rose-50 text-rose-600 text-sm font-bold rounded-xl border border-rose-100 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Delete Selected (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100 text-nowrap">
                    <tr>
                        <th class="px-6 py-4 w-10"></th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">City</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Birthdate</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Vote</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">CV Link</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Summarize</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($candidates as $candidate)
                        <tr class="hover:bg-gray-50/50 transition-colors text-nowrap group">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $candidate->id }}"
                                    class="row-checkbox w-5 h-5 rounded-md border-gray-300 text-brand focus:ring-brand transition-all cursor-pointer">
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">{{ $candidate->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $candidate->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $candidate->phone }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $candidate->city }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $candidate->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $candidate->birthdate }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-full border border-yellow-100">
                                    {{ $candidate->vote ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($candidate->cv_link)
                                    <a href="{{ $candidate->cv_link }}" target="_blank"
                                        class="text-brand hover:underline text-sm font-medium">View CV</a>
                                @else
                                    <span class="text-gray-400 text-xs">No Link</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="max-w-xs truncate text-sm text-gray-500" title="{{ $candidate->summarize }}">
                                        {{ $candidate->summarize ?? '-' }}
                                    </div>
                                    @if($candidate->summarize)
                                        <button type="button" class="text-sm text-brand hover:underline"
                                            data-summary='@json($candidate->summarize)' onclick="openSummaryModalFromBtn(this)"
                                            aria-label="View summary details">
                                            Detail
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-20 text-center text-gray-500 font-medium italic">No candidate data
                                found.</td>
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

    <!-- Hidden Forms for Actions -->
    <form id="singleDeleteForm" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="bulkDeleteForm" action="{{ route('admin.candidate-data.bulk-delete') }}" method="POST" class="hidden">
        @csrf
        <div id="bulkDeleteInputs"></div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selectAll = document.getElementById('selectAll');
                const rowCheckboxes = document.querySelectorAll('.row-checkbox');
                const bulkActionContainer = document.getElementById('bulkActionContainer');
                const selectedCount = document.getElementById('selectedCount');

                function updateBulkUI() {
                    const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                    selectedCount.textContent = checkedCount;

                    if (checkedCount > 0) {
                        bulkActionContainer.classList.remove('hidden');
                        setTimeout(() => {
                            bulkActionContainer.classList.remove('translate-y-2', 'opacity-0');
                        }, 10);
                    } else {
                        bulkActionContainer.classList.add('translate-y-2', 'opacity-0');
                        setTimeout(() => {
                            bulkActionContainer.classList.add('hidden');
                        }, 300);
                    }
                }

                if (selectAll) {
                    selectAll.addEventListener('change', function () {
                        rowCheckboxes.forEach(cb => {
                            cb.checked = selectAll.checked;
                        });
                        updateBulkUI();
                    });
                }

                rowCheckboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        updateBulkUI();
                        const total = rowCheckboxes.length;
                        const checked = document.querySelectorAll('.row-checkbox:checked').length;
                        if (selectAll) selectAll.checked = total === checked;
                    });
                });
            });

            function confirmDelete(url, name) {
                Swal.fire({
                    title: 'Delete Candidate?',
                    text: `Are you sure you want to delete ${name}? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // rose-600
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    borderRadius: '1.5rem',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'px-6 py-3 font-bold rounded-xl',
                        cancelButton: 'px-6 py-3 font-bold rounded-xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('singleDeleteForm');
                        form.action = url;
                        form.submit();
                    }
                });
            }

            function confirmBulkDelete() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const count = checkedBoxes.length;

                Swal.fire({
                    title: 'Delete All Selected?',
                    text: `You are about to delete ${count} selected candidate(s). This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: `Yes, delete ${count} records!`,
                    borderRadius: '1.5rem',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('bulkDeleteForm');
                        const container = document.getElementById('bulkDeleteInputs');
                        container.innerHTML = '';

                        checkedBoxes.forEach(cb => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = cb.value;
                            container.appendChild(input);
                        });

                        form.submit();
                    }
                });
            }

            // Open summary modal with SweetAlert2 if available, fallback to native alert
            function openSummaryModalFromBtn(btn) {
                const raw = btn.dataset.summary;
                try {
                    const parsed = JSON.parse(raw);
                    openSummaryModal(parsed);
                } catch (e) {
                    openSummaryModal(raw);
                }
            }

            function escapeHtml(unsafe) {
                if (unsafe === null || unsafe === undefined) return '';
                return String(unsafe)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function openSummaryModal(summary) {
                const content = escapeHtml(summary).replace(/\n/g, '<br>');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Summary',
                        html: `<div style="white-space:pre-line; text-align:left;">${content}</div>`,
                        width: 700,
                        confirmButtonText: 'Close',
                        customClass: { popup: 'rounded-[1.25rem]' }
                    });
                } else {
                    // Fallback: simple alert preserving new lines
                    window.alert(summary);
                }
            }
        </script>
    @endpush

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')">
        </div>
        <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden pointer-events-auto">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Import Candidate Data</h3>
                    <button onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.candidate-data.import') }}" method="POST" enctype="multipart/form-data"
                    class="p-8">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Select CSV or Excel File</label>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="flex-1 px-6 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">Cancel</button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:bg-brand-600 transition-all">Import
                            Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection