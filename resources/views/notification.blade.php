<x-app-layout>
    <div class="p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifications</h1>
            <p class="text-gray-600">Lihat semua pemberitahuan terbaru terkait karya dan akunmu.</p>
        </div>

        <!-- Notification List -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <ul>
                @forelse($notifications as $notification)
                <li class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition flex items-center justify-between">
                    <div>
                        <p class="text-gray-900 font-medium">{{ $notification->title }}</p>
                        <p class="text-gray-500 text-sm">{{ $notification->message }}</p>
                        <p class="text-gray-400 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-gray-400">
                        <!-- Icon animasi ringan -->
                        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1 4v4m0-4h1m-1 0h-1m1 0V4m0 0H9m4 0h3" />
                        </svg>
                    </div>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500">Belum ada notifikasi.</li>
                @endforelse
            </ul>
        </div>

    </div>
</x-app-layout>
