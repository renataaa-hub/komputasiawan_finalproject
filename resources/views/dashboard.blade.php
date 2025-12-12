<x-app-layout>

    <div class="pb-20 pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
                <p class="text-gray-600">
                    "Selamat datang kembali! Lanjutkan petualangan tulisanmu, semua idemu tersimpan aman di awan."
                </p>
            </div>

            <!-- Search Bar -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
                <div class="flex gap-4 items-center flex-col sm:flex-row">
                    <div class="flex-1 relative w-full">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Cari karya..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <a href="{{ route('karya.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Karya
                    </a>
                </div>
            </div>

            @include('dashboard.statistik-card')
            @include('dashboard.trending')
            @include('dashboard.monetisasi-card')
            @include('dashboard.subscription-card')
        </div>
    </div>

</x-app-layout>
