<x-app-layout>
    <div class="p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Statistik Karya</h1>
            <p class="text-gray-600">Lihat performa karya-karyamu dan data penting lainnya di sini.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            <!-- Total Karya -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Karya</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalKarya ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                    <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>

            <!-- Draft -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Draft</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalDraft ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3z"/>
                    </svg>
                </div>
            </div>

            <!-- Dipublikasikan -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Dipublikasikan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalPublished ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 text-green-600 rounded-full">
                    <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <!-- Total Views -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalViews ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 text-purple-600 rounded-full">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 10-4 0v6"/>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Table Statistik Karya -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Karya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Buat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($karya as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($item->status == 'published')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Publikasi</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->views ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->rating ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($karya->isEmpty())
                <p class="p-6 text-gray-500">Belum ada data statistik karya.</p>
            @endif
        </div>

    </div>
</x-app-layout>
