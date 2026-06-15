@extends('layouts.admin')

@section('page-title', isset($candidate) ? 'Edit Candidate' : 'Add Candidate')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white p-8 lg:p-12 rounded-[2.5rem] border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ isset($candidate) ? 'Edit Candidate Profile' : 'Create New Candidate' }}</h3>
                <p class="text-sm text-gray-500">Fill in the details below to {{ isset($candidate) ? 'update' : 'add' }} candidate information.</p>
            </div>
            <a href="{{ route('admin.candidate-data.index') }}" class="px-5 py-2.5 bg-gray-50 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-100 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>

        <form action="{{ isset($candidate) ? route('admin.candidate-data.update', $candidate) : route('admin.candidate-data.store') }}" method="POST" class="space-y-8">
            @csrf
            @if(isset($candidate)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h4 class="text-xs font-bold text-brand uppercase tracking-widest border-b border-brand/10 pb-2">Basic Information</h4>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $candidate->name ?? '') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $candidate->email ?? '') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $candidate->phone ?? '') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">City</label>
                            <input type="text" name="city" value="{{ old('city', $candidate->city ?? '') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">Birthdate</label>
                            <input type="date" name="birthdate" value="{{ old('birthdate', $candidate->birthdate ?? '') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                        </div>
                    </div>
                </div>

                <!-- HR Specifics -->
                <div class="space-y-6">
                    <h4 class="text-xs font-bold text-brand uppercase tracking-widest border-b border-brand/10 pb-2">Recruitment Details</h4>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Applied For <span class="text-red-500">*</span></label>
                        <select name="job_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm appearance-none">
                            <option value="">Select Position</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ old('job_id', $candidate->job_id ?? '') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Vote / Score</label>
                        <input type="text" name="vote" value="{{ old('vote', $candidate->vote ?? '') }}" placeholder="e.g. 85 or Recommended" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">CV External Link</label>
                        <input type="url" name="cv_link" value="{{ old('cv_link', $candidate->cv_link ?? '') }}" placeholder="https://..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                    </div>
                </div>
            </div>

            <!-- Full Width Areas -->
            <div class="space-y-6 pt-6">
                <h4 class="text-xs font-bold text-brand uppercase tracking-widest border-b border-brand/10 pb-2">Analysis & Background</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Educational Background</label>
                        <textarea name="educational" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">{{ old('educational', $candidate->educational ?? '') }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Job History</label>
                        <textarea name="job_history" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">{{ old('job_history', $candidate->job_history ?? '') }}</textarea>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Skills</label>
                    <textarea name="skills" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm" placeholder="List skills separated by commas...">{{ old('skills', $candidate->skills ?? '') }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Summary / Summarize</label>
                    <textarea name="summarize" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">{{ old('summarize', $candidate->summarize ?? '') }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Consideration / Notes</label>
                    <textarea name="consideration" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">{{ old('consideration', $candidate->consideration ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-10 border-t border-gray-50">
                <a href="{{ route('admin.candidate-data.index') }}" class="px-8 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all">Cancel</a>
                <button type="submit" class="px-10 py-3 bg-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:bg-brand-600 transition-all">
                    {{ isset($candidate) ? 'Update Candidate' : 'Save Candidate' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
