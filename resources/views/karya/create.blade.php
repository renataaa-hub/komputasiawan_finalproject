<x-app-layout>

    <div class="p-6 ml-64">
        <h1 class="text-3xl font-bold mb-6">Tambah Karya</h1>

        <!-- Di bagian atas form, setelah h1 -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <p class="text-blue-800 font-medium mb-1">Paket Anda: {{ Auth::user()->getPlanName() }}</p>
                    <div class="text-blue-700 text-sm space-y-1">
                        <p>📝 Draft: {{ Auth::user()->getCurrentDrafts() }} / {{ Auth::user()->getDraftLimit() }}</p>
                        <p>📚 Publikasi: {{ Auth::user()->getCurrentPublications() }} /
                            {{ Auth::user()->getPublicationLimit() }}</p>
                    </div>
                    @if (!Auth::user()->hasActiveSubscription())
                        <a href="{{ route('subscription') }}"
                            class="text-blue-600 hover:underline text-sm font-medium mt-2 inline-block">
                            → Upgrade untuk unlimited
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md max-w-3xl">
            <form action="{{ route('karya.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- ID DRAFT -->
                <input type="hidden" id="karya_id" name="karya_id">

                <!-- Judul -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Judul Karya</label>
                    <input type="text" name="judul"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                        placeholder="Masukkan judul karya...">
                </div>

                <!-- Jenis Karya -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Jenis Karya</label>
                    <select name="jenis" class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Jenis Karya --</option>
                        <option value="buku">Buku</option>
                        <option value="novel">Novel</option>
                        <option value="cerpen">Cerita Pendek</option>
                        <option value="artikel">Artikel</option>
                    </select>
                </div>

                <!-- Deskripsi Singkat -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="3" class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                        placeholder="Tulis deskripsi singkat tentang karya ini..."></textarea>
                    <p class="text-sm text-gray-500 mt-1">Deskripsi ini akan muncul di preview karya</p>
                </div>

                <!-- TAG / Kategori -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Tag / Kategori</label>
                    <input type="text" name="tags"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                        placeholder="Contoh: fantasi, petualangan, romance">
                    <p class="text-sm text-gray-500 mt-1">Pisahkan dengan koma ( , )</p>
                </div>

                <!-- Cover -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Cover / Thumbnail</label>
                    <input type="file" name="cover" class="w-full p-2 border rounded-lg">
                </div>

                <!-- Isi -->
                <div class="mb-2">
                    <label class="block mb-1 font-semibold">Isi Karya</label>
                    <textarea id="editor" name="isi" rows="10"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                        placeholder="Mulai tulis karya Anda..."></textarea>
                </div>

                <!-- STATUS AUTOSAVE -->
                <p id="autosave-status" class="text-sm text-gray-500 mb-4"></p>

                <!-- Tombol -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="/karya" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan Karya
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- AUTOSAVE SCRIPT -->
    <script>
        let autosaveTimer = null;

        function autosave() {
            const status = document.getElementById('autosave-status');

            fetch("{{ route('karya.autosave') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        karya_id: document.getElementById('karya_id').value,
                        judul: document.querySelector('[name="judul"]').value,
                        isi: document.querySelector('[name="isi"]').value,
                        tags: document.querySelector('[name="tags"]').value,
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res && res.karya_id) {
                        document.getElementById('karya_id').value = res.karya_id;
                    }
                    status.innerText = "🟢 Draft tersimpan (" + (res.saved_at ?? '-') + ")";
                })
                .catch(() => {
                    status.innerText = "🔴 Gagal menyimpan draft";
                });
        }

        function scheduleAutosave() {
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(autosave, 3000);
        }

        // input biasa
        document.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('input', scheduleAutosave);
            el.addEventListener('change', scheduleAutosave);
        });
    </script>

    <!-- CKEDITOR -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [
                    'heading',
                    '|',
                    'bold', 'italic', 'underline',
                    '|',
                    'bulletedList', 'numberedList',
                    '|',
                    'blockQuote',
                    '|',
                    'undo', 'redo'
                ]
            })
            .then(editor => {
                // Sinkronkan ke textarea untuk autosave & submit
                editor.model.document.on('change:data', () => {
                    document.querySelector('#editor').value = editor.getData();

                    // ✅ ini biar autosave jalan walau ngetik di CKEditor
                    scheduleAutosave();
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>

</x-app-layout>
