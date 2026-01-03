<x-app-layout>
    <div class="p-6">

        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifikasi</h1>
                <p class="text-gray-600">Lihat semua pemberitahuan terkait karya dan akunmu.</p>
            </div>

            @if ($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notification.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <!-- Notification List -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <ul class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    <li
                        class="px-6 py-4 hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <!-- Icon berdasarkan type -->
                                    @if ($notification->type == 'like')
                                        <div class="p-2 bg-red-100 text-red-600 rounded-full">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </div>
                                    @elseif($notification->type == 'collaboration')
                                        <div class="p-2 bg-purple-100 text-purple-600 rounded-full">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H2v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm10 4a3 3 0 10-6 0 3 3 0 006 0z" />
                                            </svg>
                                        </div>
                                    @elseif($notification->type == 'comment')
                                        <div class="p-2 bg-blue-100 text-blue-600 rounded-full">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="p-2 bg-gray-100 text-gray-600 rounded-full">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <p class="text-gray-900 font-medium">{{ $notification->title }}</p>
                                        <p class="text-gray-600 text-sm">{{ $notification->message }}</p>
                                    </div>

                                    @if (is_null($notification->read_at))
                                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4 mt-2 ml-14">
                                    <p class="text-gray-400 text-xs">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                    @if ($notification->data && isset($notification->data['karya_id']))
                                        <a href="{{ route('karya.show', $notification->data['karya_id']) }}"
                                            class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                            Lihat Karya →
                                        </a>
                                    @endif
                                </div>
                                @if($notification->type == 'collaboration' && $notification->data && ($notification->data['action'] ?? null) === 'needs_response')
    <div class="flex items-center gap-2 mt-2 ml-14">
        <form action="{{ route('collaboration.accept', $notification->data['request_id']) }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                Terima
            </button>
        </form>

        <form action="{{ route('collaboration.reject', $notification->data['request_id']) }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                Tolak
            </button>
        </form>
    </div>
@endif

                            </div>

                            <!-- Mark as Read Button -->
                            @if (is_null($notification->read_at))
                                <form action="{{ route('notification.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs text-gray-500 hover:text-gray-700 whitespace-nowrap">
                                        Tandai Dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-gray-500 text-lg">Belum ada notifikasi</p>
                        <p class="text-gray-400 text-sm mt-1">Notifikasi akan muncul saat ada aktivitas pada karya Anda
                        </p>
                    </li>
                @endforelse
            </ul>
        </div>

    </div>
</x-app-layout>
