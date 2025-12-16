<x-app-layout>
    <div class="p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Karya Saya</h1>
            <p class="text-gray-600">"Lanjutkan petualangan tulisanmu, semua idemu tersimpan aman di awan."</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-gray-200 mb-6">
            <div class="px-4">
                <nav class="flex gap-8">
                    <button onclick="showTab('semua')" id="tab-semua" class="py-4 border-b-2 border-blue-600 text-blue-600 font-medium">
                        Semua Karya
                    </button>
                    <button onclick="showTab('draft')" id="tab-draft" class="py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                        Draft
                    </button>
                    <button onclick="showTab('published')" id="tab-published" class="py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                        Dipublikasikan
                    </button>
                </nav>
            </div>
        </div>

        <!-- Search / Filter / Button -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari karya, draft, atau topik..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
            </div>
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                <option>Semua Kategori</option>
                <option>Puisi</option>
                <option>Cerpen</option>
                <option>Novel</option>
                <option>Esai</option>
                <option>Artikel</option>
            </select>
            <button onclick="window.location.href='{{ route('karya.create') }}'" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 justify-center transition">
                Tambah Karya
            </button>
        </div>

        <!-- Tabs Content -->
        <div id="content-semua" class="space-y-4">
            @include('karya.partials.list', ['items' => $karya])
        </div>

        <div id="content-draft" class="hidden space-y-4">
            @include('karya.partials.list', ['items' => $draft])
        </div>

        <div id="content-published" class="hidden space-y-4">
            @include('karya.partials.list', ['items' => $published])
        </div>

    </div>

    <script>
        function showTab(tab) {
            ['semua','draft','published'].forEach(t => {
                document.getElementById('content-' + t).classList.add('hidden');
                document.getElementById('tab-' + t).classList.remove('border-blue-600','text-blue-600');
            });
            document.getElementById('content-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.add('border-blue-600','text-blue-600');
        }
    </script>
</x-app-layout>