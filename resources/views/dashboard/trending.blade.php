<div class="mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Trending Karya Minggu Ini</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($trending as $item)
            <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    {{ $item->title }}
                </h3>

                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                    {{ $item->deskripsi }}
                </p>

                <div class="flex items-center gap-2 text-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 15l7-12 7 12H5z" />
                    </svg>
                    <span>{{ $item->rating }} ★</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
