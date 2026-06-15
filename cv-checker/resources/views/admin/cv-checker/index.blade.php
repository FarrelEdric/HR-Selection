@extends('layouts.admin')

@section('page-title', 'AI CV Checker')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-10 border-b border-gray-100 bg-brand/5">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-brand flex items-center justify-center rounded-3xl shadow-xl shadow-brand/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">AI CV Analysis</h3>
                        <p class="text-gray-500">Use AI (via n8n) to analyze a bulk of CVs in seconds.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.cv-checker.analyze') }}" method="POST" class="p-10 space-y-8">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        Google Drive Link
                        <span class="text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full uppercase">CV
                            Folder</span>
                    </label>
                    <input type="url" name="driveLink" required
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none transition-all"
                        placeholder="https://drive.google.com/drive/folders/...">
                    <p class="text-[11px] text-gray-400">Ensure the folder has "Anyone with the link" access.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Folder Name / Position</label>
                    <input type="text" name="folderName" required
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none transition-all"
                        placeholder="Example: Backend Developer Batch 1">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Candidate Criteria (Profile Wanted)</label>
                    <textarea name="profile_wanted" required rows="5"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none transition-all"
                        placeholder="Example: I am looking for a candidate with at least 2 years of Laravel experience, mastering REST API, and familiar with Docker..."></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" id="analyzeBtn"
                        class="w-full py-5 bg-brand text-white text-lg font-bold rounded-2xl shadow-xl shadow-brand/30 hover:bg-brand-600 transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Analyze CV (Execute n8n)
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelector('form').addEventListener('submit', function (e) {
                const btn = document.getElementById('analyzeBtn');
                btn.disabled = true;
                btn.innerHTML = `
                            <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing with AI... (Max 2 mins)
                        `;
            });
        </script>
    @endpush
@endsection