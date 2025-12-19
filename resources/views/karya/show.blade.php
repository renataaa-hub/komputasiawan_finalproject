<x-app-layout>
    <div class="p-6 ml-64">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">{{ $karya->judul }}</h1>

                <!-- Info Penulis -->
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-gray-500 text-sm">oleh</span>
                    <span class="font-medium text-gray-700">{{ $karya->user->name }}</span>
                    @if ($karya->user_id == Auth::id())
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Karya Saya</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-3">
                @if ($karya->user_id == Auth::id())
                    <a href="{{ route('karya.edit', $karya->id) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                        Edit Karya
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white p-8 rounded-xl shadow-md mb-6">

            <!-- Meta Info -->
            <div class="flex flex-wrap gap-4 mb-6 pb-6 border-b">
                <div class="flex items-center gap-2">
                    <span class="text-gray-500 text-sm">Jenis:</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ ucfirst($karya->jenis ?? 'Tidak ada jenis') }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-gray-500 text-sm">Status:</span>
                    <span
                        class="px-3 py-1 {{ $karya->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} rounded-full text-sm font-medium">
                        {{ ucfirst($karya->status) }}
                    </span>
                </div>

                @if ($karya->kategori)
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 text-sm">Tag:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach (explode(',', $karya->kategori) as $tag)
                                <span
                                    class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span class="text-sm text-gray-500">{{ $karya->views ?? 0 }} views</span>
                </div>

                <div class="flex items-center gap-2 ml-auto text-sm text-gray-500">
                    Dipublikasikan: {{ $karya->created_at->format('d M Y') }}
                </div>
            </div>

            <!-- Content -->
            <div class="prose max-w-none mb-8">
                {!! $karya->konten !!}
            </div>

            <!-- Like & Comment Actions -->
            <div class="flex items-center gap-6 pt-6 border-t">
                <!-- Like Button -->
                <button id="likeButton" onclick="toggleLike({{ $karya->id }})"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg transition {{ $karya->isLikedBy(Auth::user()) ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <svg class="w-5 h-5" fill="{{ $karya->isLikedBy(Auth::user()) ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span id="likeCount">{{ $karya->likesCount() }}</span>
                    <span id="likeText">{{ $karya->isLikedBy(Auth::user()) ? 'Liked' : 'Like' }}</span>
                </button>

                <!-- Comment Count -->
                <div class="flex items-center gap-2 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>{{ $karya->commentsCount() }} Komentar</span>
                </div>
            </div>

        </div>

        <!-- Comments Section -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-bold mb-4">Komentar ({{ $karya->commentsCount() }})</h3>

            <!-- Comment Form -->
            <form action="{{ route('karya.comment', $karya->id) }}" method="POST" class="mb-6">
                @csrf
                <div class="flex gap-3">
                    <textarea name="comment" rows="3" required
                        class="flex-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tulis komentar Anda..."></textarea>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 h-fit">
                        Kirim
                    </button>
                </div>
            </form>

            <!-- Comments List -->
            <div class="space-y-4">
                @forelse($karya->comments as $comment)
                    <div class="border-b border-gray-100 pb-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                                    <span
                                        class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    @if ($comment->user_id == $karya->user_id)
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Penulis</span>
                                    @endif
                                </div>
                                <p class="text-gray-700">{{ $comment->comment }}</p>
                            </div>

                            @if ($comment->user_id == Auth::id())
                                <form action="{{ route('comment.destroy', $comment->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        function toggleLike(karyaId) {
            const button = document.getElementById('likeButton');
            button.disabled = true; // Prevent double click

            fetch(`/karya/${karyaId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    const count = document.getElementById('likeCount');
                    const text = document.getElementById('likeText');
                    const svg = button.querySelector('svg');

                    count.textContent = data.likes_count;

                    if (data.liked) {
                        button.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                        button.classList.add('bg-red-100', 'text-red-600');
                        svg.setAttribute('fill', 'currentColor');
                        text.textContent = 'Liked';
                    } else {
                        button.classList.remove('bg-red-100', 'text-red-600');
                        button.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                        svg.setAttribute('fill', 'none');
                        text.textContent = 'Like';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                })
                .finally(() => {
                    button.disabled = false;
                });
        }
    </script>
</x-app-layout>
