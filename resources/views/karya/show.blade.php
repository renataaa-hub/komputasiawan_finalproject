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
                    @if($karya->user_id == Auth::id())
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">Karya Saya</span>
                    @endif
                </div>
            </div>
            
            <div class="flex gap-3">
                @if($karya->user_id == Auth::id())
                <a href="{{ route('karya.edit', $karya->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    Edit Karya
                </a>
                @endif
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white p-8 rounded-xl shadow-md">
            
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
                    <span class="px-3 py-1 {{ $karya->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} rounded-full text-sm font-medium">
                        {{ ucfirst($karya->status) }}
                    </span>
                </div>

                @if($karya->kategori)
                <div class="flex items-center gap-2">
                    <span class="text-gray-500 text-sm">Tag:</span>
                    <div class="flex flex-wrap gap-1">
                        @foreach(explode(',', $karya->kategori) as $tag)
                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="text-sm text-gray-500">{{ $karya->views ?? 0 }} views</span>
                </div>

                <div class="flex items-center gap-2 ml-auto text-sm text-gray-500">
                    Dipublikasikan: {{ $karya->created_at->format('d M Y') }}
                </div>
            </div>

            <!-- Content -->
            <div class="prose max-w-none">
                {!! $karya->konten !!}
            </div>

        </div>

    </div>
</x-app-layout>