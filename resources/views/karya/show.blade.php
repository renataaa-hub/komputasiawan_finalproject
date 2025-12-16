<x-app-layout>
    <div class="p-6 ml-64">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">{{ $karya->judul }}</h1>
            <div class="flex gap-3">
                <a href="{{ route('karya.edit', $karya->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    Edit Karya
                </a>
                <a href="{{ route('karya.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
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
                    <span class="text-sm text-gray-700">{{ $karya->kategori }}</span>
                </div>
                @endif

                <div class="flex items-center gap-2 ml-auto text-sm text-gray-500">
                    Dibuat: {{ $karya->created_at->format('d M Y') }}
                </div>
            </div>

            <!-- Content -->
            <div class="prose max-w-none">
                {!! $karya->konten !!}
            </div>

        </div>

    </div>
</x-app-layout>