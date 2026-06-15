@extends('layouts.admin')

@section('page-title', 'Candidate Detail')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Candidate Detail</h3>
                <p class="text-sm text-gray-500">Full information for {{ $candidate->name }}</p>
            </div>
            <a href="{{ route('admin.candidate-data.index') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">
                Back to List
            </a>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Name</p>
                    <p class="text-base font-semibold text-gray-900">{{ $candidate->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Email</p>
                    <p class="text-base text-gray-700">{{ $candidate->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Phone</p>
                    <p class="text-base text-gray-700">{{ $candidate->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">City</p>
                    <p class="text-base text-gray-700">{{ $candidate->city ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Birthdate</p>
                    <p class="text-base text-gray-700">{{ $candidate->birthdate ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Applied Job</p>
                    <p class="text-base text-gray-700">{{ $candidate->job?->title ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Vote</p>
                    <p class="text-base text-gray-700">{{ $candidate->vote ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">CV Link</p>
                    @if($candidate->cv_link)
                        <a href="{{ $candidate->cv_link }}" target="_blank"
                            class="text-brand font-semibold hover:underline break-all">{{ $candidate->cv_link }}</a>
                    @else
                        <p class="text-base text-gray-700">-</p>
                    @endif
                </div>
            </div>

            <div class="mt-8 space-y-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Educational</p>
                    <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-700 whitespace-pre-line">
                        {{ $candidate->educational ?? '-' }}</div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Job History</p>
                    <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-700 whitespace-pre-line">
                        {{ $candidate->job_history ?? '-' }}</div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Skills</p>
                    <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-700 whitespace-pre-line">
                        {{ $candidate->skills ?? '-' }}</div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Summarize</p>
                    <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-700 whitespace-pre-line">
                        {{ $candidate->summarize ?? '-' }}</div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Consideration</p>
                    <div class="p-4 bg-gray-50 rounded-2xl text-sm text-gray-700 whitespace-pre-line">
                        {{ $candidate->consideration ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection