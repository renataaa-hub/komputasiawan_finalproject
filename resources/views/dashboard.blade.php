<x-app-layout>

    <div class="pb-20 pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                        @php
                            $planBadge = Auth::user()->hasActiveSubscription()
                                ? ucfirst(Auth::user()->activeSubscription->plan)
                                : 'Basic (Free)';
                            $badgeColor = Auth::user()->hasActiveSubscription()
                                ? (Auth::user()->activeSubscription->plan === 'pro'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-purple-100 text-purple-700')
                                : 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-3 py-1 {{ $badgeColor }} rounded-full text-sm font-bold">
                            {{ $planBadge }}
                        </span>
                    </div>
                    <p class="text-gray-600">
                        "Selamat datang kembali! Lanjutkan petualangan tulisanmu, semua idemu tersimpan aman di awan."
                    </p>
                </div>

                @if (!Auth::user()->hasActiveSubscription())
                    <a href="{{ route('subscription') }}"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-medium shadow-md">
                        ⭐ Upgrade ke Pro/Premium
                    </a>
                @endif
            </div>

            <!-- Search Bar -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="flex gap-4 items-center flex-col sm:flex-row">
                        <div class="flex-1 relative w-full">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari karya yang dipublikasikan (judul, jenis, kategori)..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 justify-center flex-1 sm:flex-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari
                            </button>

                            @if ($search)
                                <a href="{{ route('dashboard') }}"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reset
                                </a>
                            @endif

                            <a href="{{ route('karya.create') }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 justify-center flex-1 sm:flex-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Karya
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Hasil Pencarian -->
            @if ($search)
                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-blue-800">
                            Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                            <span class="text-blue-600">({{ $karya->total() }} karya ditemukan)</span>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Karya (Hasil Pencarian atau Karya Saya) -->
            <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $search ? 'Hasil Pencarian Karya Publik' : 'Karya Terbaru Saya' }}
                    </h2>
                    @if (!$search)
                        <a href="{{ route('karya.index') }}"
                            class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Lihat Semua →
                        </a>
                    @endif
                </div>

                @if ($karya->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($karya as $item)
                            @php
                                $statusColor =
                                    $item->status == 'draft'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-green-100 text-green-800';
                                $isMyKarya = $item->user_id == Auth::id();
                            @endphp
                            <div
                                class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-lg transition transform hover:-translate-y-1">

                                @if ($item->cover)
                                    <img src="{{ asset('storage/' . $item->cover) }}" alt="{{ $item->judul }}"
                                        class="w-full h-40 object-cover rounded-lg mb-3">
                                @endif

                                <h3 class="text-xl font-semibold mb-1 text-gray-800">{{ $item->judul }}</h3>

                                <div class="flex items-center gap-2 mb-2">
                                    <p class="text-sm text-gray-500 capitalize">{{ $item->jenis ?? 'Tidak ada jenis' }}
                                    </p>
                                    @if ($search)
                                        <span class="text-xs text-gray-400">•</span>
                                        <p class="text-sm text-gray-500">oleh
                                            {{ $isMyKarya ? 'Saya' : $item->user->name }}</p>
                                    @endif
                                </div>

                                <p class="text-gray-700 text-sm line-clamp-2 mb-3">
                                    {{ Str::limit(strip_tags($item->konten), 100) }}
                                </p>

                                @if ($item->kategori)
                                    <div class="mb-3">
                                        @foreach (explode(',', $item->kategori) as $tag)
                                            <span
                                                class="inline-block text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded mr-1 mb-1">
                                                {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs px-2 py-1 rounded {{ $statusColor }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $item->updated_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="flex gap-3 pt-3 border-t border-gray-200">
                                    <a href="{{ route('karya.show', $item->id) }}"
                                        class="flex-1 text-center text-blue-600 text-sm hover:underline font-medium">
                                        Lihat
                                    </a>
                                    @if ($isMyKarya)
                                        <a href="{{ route('karya.edit', $item->id) }}"
                                            class="flex-1 text-center text-yellow-600 text-sm hover:underline font-medium">
                                            Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $karya->appends(['search' => $search])->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg mb-2">
                            {{ $search ? 'Tidak ada karya yang cocok dengan pencarian' : 'Belum ada karya' }}
                        </p>
                        @if ($search)
                            <p class="text-gray-400 text-sm mb-3">Coba kata kunci lain atau lihat karya lainnya</p>
                            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Kembali ke
                                dashboard</a>
                        @else
                            <a href="{{ route('karya.create') }}" class="text-blue-600 hover:underline">Buat karya
                                pertama Anda</a>
                        @endif
                    </div>
                @endif
            </div>

            @include('dashboard.statistik-card')
            @include('dashboard.trending')
            @include('dashboard.monetisasi-card')
            @include('dashboard.subscription-card')
        </div>
    </div>

</x-app-layout>
