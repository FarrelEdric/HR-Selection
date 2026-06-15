@extends('layouts.app')

@section('content')
<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-brand mb-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Job List
        </a>

        <!-- Job Header -->
        <div class="bg-gray-50 p-10 rounded-[3rem] border border-gray-100 mb-12 flex flex-col md:flex-row justify-between items-center gap-8 shadow-sm">
            <div>
                <span class="inline-block px-4 py-1.5 bg-brand/10 text-brand text-xs font-bold rounded-full mb-4">Urgent Hiring</span>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $job->title }}</h1>
                <div class="flex flex-wrap items-center gap-6 text-gray-600 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $job->location }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Competitive Salary
                    </div>
                </div>
            </div>
            <button onclick="openModal()" class="w-full md:w-auto px-10 py-5 bg-brand text-white text-lg font-bold rounded-2xl shadow-2xl shadow-brand/30 hover:bg-brand-600 hover:scale-105 transition-all">
                Apply Now
            </button>
        </div>

        <!-- Job Content -->
        <div class="grid grid-cols-1 gap-12">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-8 bg-brand rounded-full"></span>
                    Job Description
                </h3>
                <div class="text-gray-600 leading-relaxed space-y-4 text-lg">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </div>

            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-8 bg-brand rounded-full"></span>
                    Qualifications
                </h3>
                <div class="text-gray-600 leading-relaxed space-y-4 text-lg">
                    {!! nl2br(e($job->qualification)) !!}
                </div>
            </div>

            @if($job->benefit)
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-8 bg-brand rounded-full"></span>
                    Benefits & Perks
                </h3>
                <div class="text-gray-600 leading-relaxed space-y-4 text-lg">
                    {!! nl2br(e($job->benefit)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Apply Modal -->
<div id="applyModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Application Form</h2>
                    <p class="text-gray-500 text-sm">Position: {{ $job->title }}</p>
                </div>
                <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="applyForm" action="{{ route('job.apply', $job->id) }}" method="POST" enctype="multipart/form-data" class="p-8 max-h-[70vh] overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Full Name</label>
                        <input type="text" name="name" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent outline-none transition-all" placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Email Address</label>
                        <input type="email" name="email" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent outline-none transition-all" placeholder="john@example.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Phone / WhatsApp</label>
                        <input type="text" name="phone" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent outline-none transition-all" placeholder="08123456789">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">LinkedIn Link</label>
                        <input type="url" name="linkedin" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent outline-none transition-all" placeholder="https://linkedin.com/in/...">
                    </div>
                </div>

                <div class="space-y-2 mb-8">
                    <label class="text-sm font-bold text-gray-700">Complete Address</label>
                    <textarea name="address" required rows="3" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand focus:border-transparent outline-none transition-all" placeholder="Street Name, City..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Upload CV (PDF)</label>
                        <input type="file" name="cv_file" required accept=".pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand/10 file:text-brand hover:file:bg-brand/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Upload Portfolio (Optional)</label>
                        <input type="file" name="portfolio_file" accept=".pdf,.zip" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Upload KTP</label>
                        <input type="file" name="ktp_file" accept="image/*,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Upload KK</label>
                        <input type="file" name="kk_file" accept="image/*,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="w-full py-5 bg-brand text-white font-bold rounded-2xl shadow-xl shadow-brand/20 hover:bg-brand-600 hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                    Submit Application
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal() {
        document.getElementById('applyModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('applyModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('applyForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('submitBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Sending...
        `;

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: result.message,
                    icon: 'success',
                    confirmButtonColor: '#FF6B00',
                    borderRadius: '2rem'
                }).then(() => {
                    closeModal();
                    this.reset();
                });
            } else {
                throw new Error(result.message || 'Failed to submit application');
            }
        } catch (error) {
            Swal.fire({
                title: 'Oops!',
                text: error.message,
                icon: 'error',
                confirmButtonColor: '#FF6B00',
                borderRadius: '2rem'
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
</script>
@endpush
@endsection
