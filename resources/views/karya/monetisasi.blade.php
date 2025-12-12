<x-app-layout>
    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Monetisasi</h1>
            <p class="text-gray-600">Pantau penghasilan dan performa karyamu dengan mudah.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <p class="text-sm text-gray-500">Saldo</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    Rp {{ number_format($karya->sum('pendapatan'), 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <p class="text-sm text-gray-500">Karya Berbayar</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $karya->where('status_monetisasi', 'active')->count() }}
                </p>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <p class="text-sm text-gray-500">Total Karya</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $karya->count() }}
                </p>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <p class="text-sm text-gray-500">Total Views</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    12.4K
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Karya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Monetisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($karya as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($item->status_monetisasi == 'active')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                Rp {{ number_format($item->harga,0,',','.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                Rp {{ number_format($item->pendapatan,0,',','.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada karya untuk monetisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
