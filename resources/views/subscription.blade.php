<x-app-layout>
    <div class="p-6">

        <!-- Header -->
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Subscription</h1>
            <p class="text-gray-600">Pilih paket langganan yang sesuai untuk menikmati fitur premium.</p>
        </div>

        <!-- Subscription Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Basic Plan -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-lg transition transform hover:scale-105">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Basic</h2>
                <p class="text-gray-500 mb-4">Cocok untuk pengguna baru.</p>
                <p class="text-3xl font-bold text-gray-900 mb-4">Rp 50.000 / bulan</p>
                <ul class="text-gray-600 mb-6 space-y-2">
                    <li>✓ Akses terbatas</li>
                    <li>✓ Monetisasi dasar</li>
                    <li>✓ Statistik dasar karya</li>
                </ul>
                <button class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Berlangganan</button>
            </div>

            <!-- Pro Plan -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-lg transition transform hover:scale-105">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Pro</h2>
                <p class="text-gray-500 mb-4">Untuk penulis aktif dan kreator.</p>
                <p class="text-3xl font-bold text-gray-900 mb-4">Rp 150.000 / bulan</p>
                <ul class="text-gray-600 mb-6 space-y-2">
                    <li>✓ Akses penuh</li>
                    <li>✓ Monetisasi lanjutan</li>
                    <li>✓ Statistik lengkap karya</li>
                    <li>✓ Dukungan prioritas</li>
                </ul>
                <button class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">Berlangganan</button>
            </div>

            <!-- Premium Plan -->
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-lg transition transform hover:scale-105">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Premium</h2>
                <p class="text-gray-500 mb-4">Untuk kreator profesional dan bisnis.</p>
                <p class="text-3xl font-bold text-gray-900 mb-4">Rp 300.000 / bulan</p>
                <ul class="text-gray-600 mb-6 space-y-2">
                    <li>✓ Semua fitur Pro</li>
                    <li>✓ Monetisasi premium</li>
                    <li>✓ Statistik mendalam & laporan</li>
                    <li>✓ Prioritas dukungan & konsultasi</li>
                </ul>
                <button class="w-full bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700 transition">Berlangganan</button>
            </div>

        </div>

        <!-- Info tambahan -->
        <div class="mt-8 text-center text-gray-500">
            <p>Batalkan kapan saja. Semua pembayaran aman dan terproteksi.</p>
        </div>

    </div>
</x-app-layout>
