<x-app-layout>
    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Monetisasi</h1>
            <p class="text-gray-600">Pantau penghasilan dan performa karyamu dengan mudah.</p>
        </div>

        @php
            // Total views: ambil dari views_count kalau ada, fallback ke views
            $totalViews = (int) $karya->sum(fn($k) => (int) ($k->views_count ?? $k->views ?? 0));

            // Total saldo/penghasilan: dari method totalEarnedAmount() (kelipatan 1000 x 50.000)
            // Kalau method belum ada, ini akan 0 (tapi seharusnya kamu sudah bikin method di Model Karya)
            $totalSaldo = (int) $karya->sum(fn($k) => (int) ($k->totalEarnedAmount?->() ?? 0));

            // Karya berbayar/monetisasi aktif: pakai monetization_active kalau ada, fallback ke status_monetisasi
            $totalKaryaBerbayar = (int) $karya->filter(fn($k) => (bool) ($k->monetization_active ?? (($k->status_monetisasi ?? '') === 'active')))->count();
        @endphp

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <p class="text-sm text-gray-500">Saldo</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <p class="text-sm text-gray-500">Karya Berbayar</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $totalKaryaBerbayar }}
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
                    {{ number_format($totalViews, 0, ',', '.') }}
                </p>
            </div>

        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Judul Karya
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Views
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status Monetisasi
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Pendapatan
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bisa Diklaim
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($karya as $item)
                        @php
                            $isPublished = (($item->status ?? '') === 'publish');
                            $isMonetActive = (bool) ($item->monetization_active ?? (($item->status_monetisasi ?? '') === 'active'));
                            $viewsItem = (int) ($item->views_count ?? $item->views ?? 0);

                            // safe call ke method model, fallback 0 kalau belum ada
                            $totalEarned = (int) ($item->totalEarnedAmount?->() ?? 0);
                            $claimable = (int) ($item->claimableAmount?->() ?? 0);
                            $claimableBlocks = (int) ($item->claimableBlocks?->() ?? 0);

                            // Harga: pakai price kalau ada, fallback ke harga
                            $price = (int) ($item->price ?? $item->harga ?? 0);
                        @endphp

                        <tr>
                            {{-- Judul --}}
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                {{ $item->judul ?? '-' }}
                                @if(!$isPublished)
                                    <p class="text-xs text-red-500 mt-1">* Harus publish dulu</p>
                                @endif
                            </td>

                            {{-- Views --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ number_format($viewsItem, 0, ',', '.') }}
                            </td>

                            {{-- Status Monetisasi --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($isMonetActive && $isPublished)
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Harga --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                Rp {{ number_format($price, 0, ',', '.') }}
                            </td>

                            {{-- Total Pendapatan --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                Rp {{ number_format($totalEarned, 0, ',', '.') }}
                            </td>

                            {{-- Bisa Diklaim --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                Rp {{ number_format($claimable, 0, ',', '.') }}
                                @if($claimableBlocks > 0)
                                    <div class="text-xs text-gray-500 mt-1">
                                        ({{ $claimableBlocks }}x klaim)
                                    </div>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="flex flex-col gap-2">

                                    {{-- Update status + harga --}}
                                    <form action="{{ route('karya.monetisasi.update', $item->id) }}" method="POST"
                                        class="flex gap-2 items-center">
                                        @csrf

                                        <select name="status_monetisasi" class="border rounded px-2 py-1 text-xs"
                                            {{ !$isPublished ? 'disabled' : '' }}>
                                            <option value="inactive" @selected(($item->status_monetisasi ?? 'inactive') !== 'active')>
                                                Nonaktif
                                            </option>
                                            <option value="active" @selected(($item->status_monetisasi ?? 'inactive') === 'active')>
                                                Aktif
                                            </option>
                                        </select>

                                        <input type="number" name="harga" min="0" value="{{ $price }}"
                                            class="w-28 border rounded px-2 py-1 text-xs"
                                            {{ !$isPublished ? 'disabled' : '' }}>

                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700
                                            {{ !$isPublished ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ !$isPublished ? 'disabled' : '' }}>
                                            Simpan
                                        </button>
                                    </form>

                                    {{-- Tombol Klaim --}}
                                    <form method="POST" action="{{ route('monetisasi.claim', $item->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700
                                            {{ ($claimableBlocks <= 0 || !$isMonetActive || !$isPublished) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            @disabled($claimableBlocks <= 0 || !$isMonetActive || !$isPublished)
                                        >
                                            Klaim
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada karya untuk monetisasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
