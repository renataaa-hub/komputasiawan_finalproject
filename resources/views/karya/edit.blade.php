<x-app-layout>

    <div class="p-6 ml-64">
        <h1 class="text-3xl font-bold mb-6">Edit Karya</h1>

        <div class="bg-white p-6 rounded-xl shadow-md max-w-3xl">
            <form action="{{ route('karya.update', $karya->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Judul Karya</label>
                    <input type="text" name="judul" value="{{ old('judul', $karya->judul) }}"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                        placeholder="Masukkan judul karya..." required>
                </div>

                <!-- Jenis Karya -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Jenis Karya</label>
                    <select name="jenis" class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200" required>
                        <option value="">-- Pilih Jenis Karya --</option>
                        <option value="buku" {{ old('jenis', $karya->jenis) == 'buku' ? 'selected' : '' }}>Buku
                        </option>
                        <option value="novel" {{ old('jenis', $karya->jenis) == 'novel' ? 'selected' : '' }}>Novel
                        </option>
                        <option value="cerpen" {{ old('jenis', $karya->jenis) == 'cerpen' ? 'selected' : '' }}>Cerita
                            Pendek</option>
                        <option value="artikel" {{ old('jenis', $karya->jenis) == 'artikel' ? 'selected' : '' }}>Artikel
                        </option>
                    </select>
                </div>
                <!-- Deskripsi Singkat -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="3" class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                        placeholder="Tulis deskripsi singkat tentang karya ini...">{{ old('deskripsi', $karya->deskripsi) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Deskripsi ini akan muncul di preview karya</p>
                </div>
                <!-- TAG / Kategori -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Tag / Kategori</label>
                    <input type="text" name="tags" value="{{ old('tags', $karya->kategori) }}"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                        placeholder="Contoh: fantasi, petualangan, romance">
                    <p class="text-sm text-gray-500 mt-1">Pisahkan dengan koma ( , )</p>
                </div>

                <!-- Cover -->
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Cover / Thumbnail</label>
                    <input type="file" name="cover" class="w-full p-2 border rounded-lg">
                    @if ($karya->cover)
                        <p class="text-sm text-gray-500 mt-1">Cover saat ini: {{ basename($karya->cover) }}</p>
                    @endif
                </div>

                <!-- Isi -->
                <div class="mb-2">
                    <label class="block mb-1 font-semibold">Isi Karya</label>
                    <textarea id="editor" name="isi" rows="10"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200" placeholder="Mulai tulis karya Anda..."
                        required>{{ old('isi', $karya->konten) }}</textarea>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('karya.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit" name="publish"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Publish
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan sebagai Draft
                    </button>
                </div>

            </form>
        </div>
    </div>

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
                // Sinkronkan ke textarea untuk submit
                editor.model.document.on('change:data', () => {
                    document.querySelector('#editor').value = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>

</x-app-layout>
