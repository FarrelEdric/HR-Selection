@extends('layouts.admin')

@section('page-title', 'Hasil Analisis AI')

@section('content')
<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Analysis Results List</h3>
            <p class="text-sm text-gray-500">Results received from n8n automation.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100 text-nowrap">
                <tr>
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
                @forelse($results as $result)
                <tr class="hover:bg-gray-50/50 transition-colors text-nowrap group">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $result->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $result->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $result->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $result->city }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $result->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $result->birthdate }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-full border border-yellow-100">
                            {{ $result->vote ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($result->cv_link)
                            <a href="{{ $result->cv_link }}" target="_blank" class="text-brand hover:underline text-sm font-medium">View CV</a>
                        @else
                            <span class="text-gray-400 text-xs">No Link</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="max-w-xs truncate text-sm text-gray-500" title="{{ $result->summarize }}">
                                {{ $result->summarize ?? '-' }}
                            </div>
                            @if($result->summarize)
                                <button type="button" class="text-sm text-brand hover:underline"
                                    data-summary='@json($result->summarize)' onclick="openSummaryModalFromBtn(this)"
                                    aria-label="View summary details">
                                    Detail
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-20 text-center text-gray-500 font-medium italic">Belum ada hasil analisis. Jalankan CV Checker terlebih dahulu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-8 bg-gray-50 border-t border-gray-100">
        {{ $results->links() }}
    </div>
</div>

@push('scripts')
<script>
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
@endsection
