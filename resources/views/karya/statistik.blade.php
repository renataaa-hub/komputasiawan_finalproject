<x-app-layout>
    <div class="p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Statistik Karya</h1>
            <p class="text-gray-600">Lihat performa karya-karyamu dan data penting lainnya di sini.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

            <!-- Total Karya -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm text-gray-500">Total Karya</p>
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalKarya ?? 0 }}</p>
            </div>

            <!-- Draft -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm text-gray-500">Draft</p>
                    <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalDraft ?? 0 }}</p>
            </div>

            <!-- Dipublikasikan -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm text-gray-500">Dipublikasikan</p>
                    <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalPublished ?? 0 }}</p>
            </div>

            <!-- Total Views -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm text-gray-500">Total Views</p>
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalViews ?? 0 }}</p>
            </div>

            <!-- Total Likes -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm text-gray-500">Total Likes</p>
                    <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalLikes ?? 0 }}</p>
            </div>

            <!-- Total Comments -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm text-gray-500">Total Komentar</p>
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalComments ?? 0 }}</p>
            </div>

        </div>

        <!-- Table Statistik Karya -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Karya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Likes</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($karya as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <a href="{{ route('karya.show', $item->id) }}" class="hover:text-blue-600">
                                    {{ Str::limit($item->judul, 40) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 capitalize">
                                {{ $item->jenis ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($item->status == 'publish')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Publikasi</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ $item->views ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    {{ $item->likes_count ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    {{ $item->comments_count ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <a href="{{ route('karya.show', $item->id) }}" 
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data statistik karya.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>