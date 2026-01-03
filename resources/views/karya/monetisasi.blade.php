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
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
</tr>
</thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($karya as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
    <form action="{{ route('karya.monetisasi.update', $item->id) }}" method="POST" class="flex gap-2 items-center">
        @csrf

        <select name="status_monetisasi" class="border rounded px-2 py-1 text-xs">
            <option value="inactive" @selected($item->status_monetisasi !== 'active')>Nonaktif</option>
            <option value="active" @selected($item->status_monetisasi === 'active')>Aktif</option>
        </select>

        <input type="number" name="harga" min="0" value="{{ $item->harga ?? 0 }}"
            class="w-28 border rounded px-2 py-1 text-xs"
            placeholder="Harga">

        <button type="submit" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
            Simpan
        </button>
    </form>

    @if($item->status !== 'publish')
        <p class="text-xs text-red-500 mt-1">* Harus publish dulu</p>
    @endif
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
