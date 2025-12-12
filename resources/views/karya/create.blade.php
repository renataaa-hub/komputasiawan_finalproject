<x-app-layout>

<div class="p-6 ml-64"> 
    <h1 class="text-3xl font-bold mb-6">Tambah Karya</h1>

    <div class="bg-white p-6 rounded-xl shadow-md max-w-3xl">
        <form action="{{ route('karya.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                <select name="jenis"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih Jenis Karya --</option>
                    <option value="buku">Buku</option>
                    <option value="novel">Novel</option>
                    <option value="cerpen">Cerita Pendek</option>
                    <option value="artikel">Artikel</option>
                </select>
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
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Isi Karya</label>
                <textarea name="isi" rows="10"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"
                    placeholder="Mulai tulis karya Anda..."></textarea>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="/karya" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Karya
                </button>
            </div>

        </form>
    </div>
</div>

</x-app-layout>
