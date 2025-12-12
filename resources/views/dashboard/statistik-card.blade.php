<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

    <!-- Total Karya -->
    <a href="{{ route('karya.statistik') }}" class="block bg-white shadow-sm rounded-lg p-6 border border-gray-100 hover:shadow-md hover:scale-105 transition transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Karya</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalKarya ?? 0 }}</h3>
            </div>
            <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Karya Publish -->
    <a href="{{ route('karya.statistik') }}" class="block bg-white shadow-sm rounded-lg p-6 border border-gray-100 hover:shadow-md hover:scale-105 transition transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Karya Publish</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $karyaPublish ?? 0 }}</h3>
            </div>
            <div class="p-3 bg-green-100 text-green-600 rounded-full">
                <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Karya Draft -->
    <a href="{{ route('karya.statistik') }}" class="block bg-white shadow-sm rounded-lg p-6 border border-gray-100 hover:shadow-md hover:scale-105 transition transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Karya Draft</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $karyaDraft ?? 0 }}</h3>
            </div>
            <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3z"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Jumlah Pembaca -->
    <a href="{{ route('karya.statistik') }}" class="block bg-white shadow-sm rounded-lg p-6 border border-gray-100 hover:shadow-md hover:scale-105 transition transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Pembaca</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalPembaca ?? 0 }}</h3>
            </div>
            <div class="p-3 bg-purple-100 text-purple-600 rounded-full">
                <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 10-4 0v6"/>
                </svg>
            </div>
        </div>
    </a>

</div>
