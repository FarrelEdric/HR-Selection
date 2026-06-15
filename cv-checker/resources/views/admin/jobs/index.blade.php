@extends('layouts.admin')

@section('page-title', 'Manajemen Lowongan')

@section('content')
<!-- Filter Section -->
<div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm mb-8">
    <form action="{{ route('admin.jobs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="md:col-span-2 space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cari Judul / Lokasi</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul lowongan..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Per Halaman</label>
            <select name="per_page" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand outline-none transition-all text-sm appearance-none">
                @foreach([10, 20, 50, 100] as $count)
                    <option value="{{ $count }}" {{ request('per_page') == $count ? 'selected' : '' }}>{{ $count }} Data</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-6 py-3 bg-brand text-white text-sm font-bold rounded-xl shadow-lg shadow-brand/20 hover:bg-brand-600 transition-all">
                Cari
            </button>
            <a href="{{ route('admin.jobs.index') }}" class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all" title="Reset">
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
            <h3 class="text-lg font-bold text-gray-800">Job List</h3>
            <p class="text-sm text-gray-500">Manage all active job positions.</p>
        </div>
        <a href="{{ route('admin.jobs.create') }}" class="px-6 py-3 bg-brand text-white text-sm font-bold rounded-xl shadow-lg shadow-brand/20 hover:bg-brand-600 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Job
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Job Title</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Applicants</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Created At</th>
                    <th class="px-8 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($jobs as $job)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-6">
                        <p class="font-bold text-gray-900">{{ $job->title }}</p>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-sm text-gray-600 flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $job->location }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-brand/5 text-brand text-xs font-bold rounded-full">
                            {{ $job->candidates_count }} Pelamar
                        </span>
                    </td>
                    <td class="px-8 py-6 text-sm text-gray-500">
                        {{ $job->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-brand hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-gray-100 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-gray-500 font-medium">Belum ada lowongan pekerjaan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-8 bg-gray-50 border-t border-gray-100">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
