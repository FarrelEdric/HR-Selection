@extends('layouts.admin')

@section('page-title', 'Tambah Lowongan')

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.jobs.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-brand mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar
    </a>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('admin.jobs.store') }}" method="POST" class="p-10 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Judul Lowongan</label>
                    <input type="text" name="title" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all" placeholder="Contoh: Senior Backend Developer">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Lokasi</label>
                    <input type="text" name="location" required class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all" placeholder="Contoh: Jakarta / Remote">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700">Deskripsi Pekerjaan</label>
                <textarea name="description" required rows="6" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all" placeholder="Tuliskan jobdesk secara detail..."></textarea>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700">Kualifikasi</label>
                <textarea name="qualification" required rows="6" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all" placeholder="Minimal pendidikan, pengalaman, skill..."></textarea>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700">Benefit (Optional)</label>
                <textarea name="benefit" rows="4" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all" placeholder="Gaji, asuransi, lingkungan kerja..."></textarea>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-gray-50">
                <button type="reset" class="px-8 py-3 bg-gray-50 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-100 transition-all">Reset</button>
                <button type="submit" class="px-10 py-3 bg-brand text-white text-sm font-bold rounded-xl shadow-lg shadow-brand/20 hover:bg-brand-600 transition-all">Simpan Lowongan</button>
            </div>
        </form>
    </div>
</div>
@endsection
