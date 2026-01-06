<x-app-layout>
    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Monetisasi</h1>
            <p class="text-gray-600">Pantau penghasilan dan performa karyamu dengan mudah.</p>
        </div>

        @php
            // Hindari fn() dan ?-> biar aman di PHP lama
            $totalViews = 0;
            $totalSaldo = 0;
            $totalKaryaBerbayar = 0;

            foreach ($karya as $k) {
                $viewsItem = 0;
                if (isset($k->views_count)) $viewsItem = (int) $k->views_count;
                else if (isset($k->views)) $viewsItem = (int) $k->views;

                $totalViews += $viewsItem;

                // Total saldo: idealnya dari totalEarnedAmount() (kelipatan 1000 x 50.000)
                // fallback ke pendapatan kalau method belum ada
                if (method_exists($k, 'totalEarnedAmount')) {
                    $totalSaldo += (int) $k->totalEarnedAmount();
                } else {
                    $totalSaldo += (int) ($k->pendapatan ?? 0);
                }

                $isActive = false;
                if (isset($k->monetization_active)) $isActive = (bool) $k->monetization_active;
                else $isActive = (($k->status_monetisasi ?? '') === 'active');

                if ($isActive) $totalKaryaBerbayar++;
            }
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Karya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Monetisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bisa Diklaim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($karya as $item)
                        @php
                            $isPublished = (($item->status ?? '') === 'publish');

                            $isMonetActive = false;
                            if (isset($item->monetization_active)) $isMonetActive = (bool) $item->monetization_active;
                            else $isMonetActive = (($item->status_monetisasi ?? '') === 'active');

                            $viewsItem = 0;
                            if (isset($item->views_count)) $viewsItem = (int) $item->views_count;
                            else if (isset($item->views)) $viewsItem = (int) $item->views;

                            $price = 0;
                            if (isset($item->price)) $price = (int) $item->price;
                            else if (isset($item->harga)) $price = (int) $item->harga;

                            $totalEarned = method_exists($item, 'totalEarnedAmount') ? (int) $item->totalEarnedAmount() : (int) ($item->pendapatan ?? 0);
                            $claimable = method_exists($item, 'claimableAmount') ? (int) $item->claimableAmount() : 0;
                            $claimableBlocks = method_exists($item, 'claimableBlocks') ? (int) $item->claimableBlocks() : 0;
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
                                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Aktif</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">Nonaktif</span>
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
                                    <div class="text-xs text-gray-500 mt-1">({{ $claimableBlocks }}x klaim)</div>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="flex flex-col gap-2">

                                    {{-- Update status + harga --}}
                                    <form action="{{ route('karya.monetisasi.update', $item->id) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf

                                        <select name="status_monetisasi" class="border rounded px-2 py-1 text-xs" {{ !$isPublished ? 'disabled' : '' }}>
                                            <option value="inactive" {{ (($item->status_monetisasi ?? 'inactive') !== 'active') ? 'selected' : '' }}>Nonaktif</option>
                                            <option value="active" {{ (($item->status_monetisasi ?? 'inactive') === 'active') ? 'selected' : '' }}>Aktif</option>
                                        </select>

                                        <input type="number" name="harga" min="0" value="{{ $price }}" class="w-28 border rounded px-2 py-1 text-xs" {{ !$isPublished ? 'disabled' : '' }}>

                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 {{ !$isPublished ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ !$isPublished ? 'disabled' : '' }}>
                                            Simpan
                                        </button>
                                    </form>

                                    {{-- Tombol Klaim --}}
                                    <form method="POST" action="{{ route('monetisasi.claim', $item->id) }}">
                                        @csrf

                                        @php
                                            $disableClaim = ($claimableBlocks <= 0) || !$isMonetActive || !$isPublished;
                                        @endphp

                                        <button type="submit"
                                            class="px-3 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700 {{ $disableClaim ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $disableClaim ? 'disabled' : '' }}>
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
