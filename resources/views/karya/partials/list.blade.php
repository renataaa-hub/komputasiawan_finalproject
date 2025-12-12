<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($items as $karya)
        @php
            $statusColor = $karya->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
        @endphp
        <div class="bg-white rounded-xl shadow hover:shadow-xl border border-gray-200 p-5 transition transform hover:-translate-y-1">

            <h2 class="text-xl font-semibold mb-1 text-gray-800">{{ $karya->judul }}</h2>
            <p class="text-sm text-gray-500 capitalize">{{ $karya->jenis }}</p>

            <p class="mt-3 text-gray-700 text-sm line-clamp-3">
                {{ $karya->deskripsi ?? 'Tidak ada deskripsi.' }}
            </p>

            <span class="inline-block mt-3 text-xs px-2 py-1 rounded {{ $statusColor }}">
                {{ ucfirst($karya->status) }}
            </span>

            <div class="mt-4 flex justify-between">
                <a href="#" class="text-blue-600 text-sm hover:underline font-medium">Lihat</a>
                <a href="#" class="text-yellow-600 text-sm hover:underline font-medium">Edit</a>
            </div>

        </div>
    @empty
        <p class="text-gray-500">Tidak ada data.</p>
    @endforelse
</div>
