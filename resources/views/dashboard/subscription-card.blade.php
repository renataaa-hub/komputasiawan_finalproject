<div class="bg-white rounded-xl shadow-sm p-6 mt-10">

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Subscription Kamu</h2>

        <a href="{{ route('subscription') }}"
           class="text-blue-600 text-sm font-medium hover:underline">
           Lihat Semua
        </a>
    </div>

    <div class="space-y-4">

        {{-- Subscriber Card --}}
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 flex items-center justify-center rounded-full font-bold text-lg">
                1
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Paket Premium</h3>
                <p class="text-gray-600 text-sm">Langganan aktif — diperbarui setiap bulan</p>
            </div>
            <div class="ml-auto text-green-600 font-medium">
                Aktif
            </div>
        </div>
    </div>
</div>
