<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="YOUR_CSRF_TOKEN_HERE">
    <title>Tambah Karya - PenaAwan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/dashboard" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Karya Baru</h1>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="handleSubmit('draft')" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Simpan Draft
                </button>
                <button onclick="handleSubmit('published')" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    Publikasikan
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Judul -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Karya <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        placeholder="Masukkan judul karya Anda..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                    />
                </div>

                <!-- Cover Image -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cover Image
                    </label>
                    <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                        <div id="upload-placeholder">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 mb-2">Upload cover image</p>
                            <input
                                type="file"
                                accept="image/*"
                                id="cover-upload"
                                class="hidden"
                                onchange="handleImageUpload(event)"
                            />
                            <label
                                for="cover-upload"
                                class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg cursor-pointer hover:bg-blue-700"
                            >
                                Pilih File
                            </label>
                        </div>
                        <div id="image-preview" class="hidden relative">
                            <img id="preview-img" src="" alt="Preview" class="max-h-64 mx-auto rounded-lg" />
                            <button
                                onclick="removeImage()"
                                class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Singkat
                    </label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="3"
                        placeholder="Tulis deskripsi singkat tentang karya Anda..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none"
                    ></textarea>
                </div>

                <!-- Konten -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konten Karya <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="konten"
                        name="konten"
                        rows="15"
                        placeholder="Mulai menulis karya Anda di sini..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none font-serif"
                        oninput="updateCharCount()"
                    ></textarea>
                    <p class="text-sm text-gray-500 mt-2">
                        <span id="char-count">0</span> karakter
                    </p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Kategori -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Kategori
                    </label>
                    <select
                        id="kategori"
                        name="kategori"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                        onchange="updateStats()"
                    >
                        <option value="">Pilih Kategori</option>
                        <option value="Puisi">Puisi</option>
                        <option value="Cerpen">Cerpen</option>
                        <option value="Novel">Novel</option>
                        <option value="Esai">Esai</option>
                        <option value="Artikel">Artikel</option>
                        <option value="Review">Review</option>
                        <option value="Opini">Opini</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Tags
                    </label>
                    <input
                        type="text"
                        id="tag-input"
                        placeholder="Ketik tag dan tekan Enter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all mb-3"
                        onkeydown="handleTagInput(event)"
                    />
                    <div id="tags-container" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Tips -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-sm p-6 border border-blue-100">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Tips Menulis
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 mt-1">•</span>
                            <span>Gunakan judul yang menarik dan deskriptif</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 mt-1">•</span>
                            <span>Pilih kategori yang sesuai dengan karya Anda</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 mt-1">•</span>
                            <span>Tambahkan tags untuk memudahkan pencarian</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 mt-1">•</span>
                            <span>Periksa ejaan dan tata bahasa sebelum publikasi</span>
                        </li>
                    </ul>
                </div>

                <!-- Preview Stats -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview Stats
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Judul:</span>
                            <span id="stat-judul" class="font-medium">✗</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kategori:</span>
                            <span id="stat-kategori" class="font-medium">✗</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Konten:</span>
                            <span id="stat-konten" class="font-medium">✗</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tags:</span>
                            <span id="stat-tags" class="font-medium">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // State management
        let tags = [];
        let coverImage = null;

        // Character count
        function updateCharCount() {
            const konten = document.getElementById('konten').value;
            document.getElementById('char-count').textContent = konten.length;
            updateStats();
        }

        // Tag management
        function handleTagInput(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const input = document.getElementById('tag-input');
                const tag = input.value.trim();
                
                if (tag && !tags.includes(tag)) {
                    tags.push(tag);
                    renderTags();
                    input.value = '';
                    updateStats();
                }
            }
        }

        function renderTags() {
            const container = document.getElementById('tags-container');
            container.innerHTML = tags.map(tag => `
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                    ${tag}
                    <button onclick="removeTag('${tag}')" class="hover:text-blue-900">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </span>
            `).join('');
        }

        function removeTag(tagToRemove) {
            tags = tags.filter(tag => tag !== tagToRemove);
            renderTags();
            updateStats();
        }

        // Image upload
        function handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                coverImage = file;
                const reader = new FileReader();
                reader.onloadend = function() {
                    document.getElementById('preview-img').src = reader.result;
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            coverImage = null;
            document.getElementById('cover-upload').value = '';
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('image-preview').classList.add('hidden');
        }

        // Update stats
        function updateStats() {
            const judul = document.getElementById('judul').value;
            const kategori = document.getElementById('kategori').value;
            const konten = document.getElementById('konten').value;

            document.getElementById('stat-judul').textContent = judul ? '✓' : '✗';
            document.getElementById('stat-kategori').textContent = kategori ? '✓' : '✗';
            document.getElementById('stat-konten').textContent = konten ? '✓' : '✗';
            document.getElementById('stat-tags').textContent = tags.length;
        }

        // Form submission
        async function handleSubmit(status) {
            const judul = document.getElementById('judul').value;
            const kategori = document.getElementById('kategori').value;
            const deskripsi = document.getElementById('deskripsi').value;
            const konten = document.getElementById('konten').value;

            if (!judul || !kategori || !konten) {
                alert('Mohon lengkapi field yang wajib diisi (Judul, Kategori, dan Konten)');
                return;
            }

            const formData = new FormData();
            formData.append('judul', judul);
            formData.append('kategori', kategori);
            formData.append('deskripsi', deskripsi);
            formData.append('konten', konten);
            formData.append('status', status);
            formData.append('tags', JSON.stringify(tags));
            
            if (coverImage) {
                formData.append('cover_image', coverImage);
            }

            try {
                const response = await fetch('/karya', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                });
                
                if (response.ok) {
                    alert(`Karya berhasil ${status === 'draft' ? 'disimpan sebagai draft' : 'dipublikasikan'}!`);
                    window.location.href = '/dashboard';
                } else {
                    const error = await response.json();
                    alert('Error: ' + (error.message || 'Terjadi kesalahan'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan karya');
            }
        }

        // Event listeners
        document.getElementById('judul').addEventListener('input', updateStats);
        document.getElementById('konten').addEventListener('input', updateStats);
    </script>
</body>
</html>