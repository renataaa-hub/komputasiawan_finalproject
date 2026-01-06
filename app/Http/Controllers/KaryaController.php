<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Karya;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class KaryaController extends Controller
{
    public function index()
    {
        $karya = Karya::where('user_id', Auth()->id())->get();
        $draft = Karya::where('user_id', Auth()->id())->where('status', 'draft')->get();
        $published = Karya::where('user_id', Auth::id())
        ->where('status', 'publish')
        ->get();

        return view('karya.karya', compact('karya', 'draft', 'published'));
    }

    public function create()
    {
        return view('karya.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if publishing or drafting
        $isPublishing = $request->has('publish') || $request->input('status') === 'publish';
        
        // Check limits
        if ($isPublishing && !$user->canPublish()) {
            $limit = $user->getPublicationLimit();
            $current = $user->getCurrentPublications();
            return back()->with('error', "Batas publikasi tercapai! ({$current}/{$limit}). Upgrade ke Pro atau Premium untuk publikasi lebih banyak.");
        }
        
        if (!$isPublishing && !$user->canCreateDraft()) {
            $limit = $user->getDraftLimit();
            $current = $user->getCurrentDrafts();
            return back()->with('error', "Batas draft tercapai! ({$current}/{$limit}). Upgrade ke Pro atau Premium untuk membuat lebih banyak draft.");
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'isi' => 'required|string',
            'tags' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Karya::updateOrCreate(
            [
                'id' => $request->karya_id,
                'user_id' => $user->id,
            ],
            [
                'judul' => $validated['judul'],
                'slug' => Str::slug($validated['judul']),
                'jenis' => $validated['jenis'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'konten' => $validated['isi'],
                'kategori' => $validated['tags'] ?? null,
                'status' => $isPublishing ? 'publish' : 'draft',
                'is_draft' => !$isPublishing,
                'akses' => 'publik',
                'cover' => $coverPath ?? null,
            ]
        );

        return redirect()->route('karya.index')->with('success', 'Karya berhasil disimpan!');
    }

    public function autosave(Request $request)
    {
        // Autosave TIDAK BOLEH gagal keras
        $request->validate([
            'judul' => 'nullable|string|max:255',
            'isi'   => 'nullable|string',
            'tags'  => 'nullable|string',
        ]);

        $karya = Karya::updateOrCreate(
            [
                'id' => $request->karya_id,
                'user_id' => Auth::id(),
            ],
            [
                'judul'    => $request->judul ?? 'Tanpa Judul',
                'slug'     => Str::slug($request->judul ?? 'draft-' . time()),
                'konten'   => $request->isi,
                'kategori' => $request->tags,
                'status'   => 'draft',
                'is_draft' => true,
                'akses'    => 'pribadi',
            ]
        );

        return response()->json([
            'success'   => true,
            'karya_id' => $karya->id,
            'saved_at' => now()->format('H:i:s'),
        ]);
    }

    public function statistik()
    {
        $karya = Karya::where('user_id', auth()->id())
            ->withCount(['likes', 'comments'])
            ->get();

        $totalKarya = $karya->count();
        $totalDraft = $karya->where('status', 'draft')->count();
        $totalPublished = $karya->where('status', 'publish')->count();
        $totalViews = $karya->sum('views');
        $totalLikes = $karya->sum('likes_count');
        $totalComments = $karya->sum('comments_count');

        return view('karya.statistik', compact(
            'karya', 
            'totalKarya', 
            'totalDraft', 
            'totalPublished', 
            'totalViews',
            'totalLikes',
            'totalComments'
        ));
    }


    public function monetisasi()
    {
        $karya = \App\Models\Karya::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('karya.monetisasi', compact('karya'));
    }

    // Tambahkan method ini di KaryaController
    public function updateMonetisasi(Request $request, Karya $karya)
{
    // hanya owner yang boleh atur
    if ($karya->user_id !== Auth::id()) {
        abort(403, 'Unauthorized');
    }

    // hanya karya publish yang boleh dimonetisasi (biar masuk akal)
    if ($karya->status !== 'publish') {
        return back()->with('error', 'Monetisasi hanya bisa untuk karya yang sudah dipublikasikan.');
    }

    $data = $request->validate([
        'status_monetisasi' => 'required|in:active,inactive',
        'harga' => 'nullable|integer|min:0|max:100000000', // max 100jt
    ]);

    // kalau aktif wajib ada harga > 0
    if (($data['status_monetisasi'] ?? 'inactive') === 'active') {
        $harga = (int) ($data['harga'] ?? 0);
        if ($harga <= 0) {
            return back()->with('error', 'Harga wajib diisi (> 0) jika monetisasi aktif.');
        }
        $karya->harga = $harga;
    }

    $karya->status_monetisasi = $data['status_monetisasi'];

    // kalau dimatiin, harga boleh tetap ada / atau bisa kamu set 0 (pilih salah satu)
    if ($karya->status_monetisasi === 'inactive') {
        // optional:
        // $karya->harga = 0;
    }

    $karya->save();

    return back()->with('success', 'Pengaturan monetisasi berhasil diperbarui.');
}

    public function show(Karya $karya)
{
    // Jika karya dipublikasikan (publish), semua orang bisa lihat
    if ($karya->status === 'publish') {

        // ✅ Hitung view + pendapatan hanya kalau yang buka bukan owner
        if (auth()->check() && auth()->id() !== $karya->user_id) {

            // ✅ anti spam refresh: 1 user dihitung 1x per 1 jam per karya
            $key = "viewed:" . auth()->id() . ":" . $karya->id;

            if (!Cache::has($key)) {
                Cache::put($key, true, now()->addHour());

                // tambah views
                $karya->increment('views');

                // kalau monetisasi aktif, tambah pendapatan per view
                if ($karya->status_monetisasi === 'active') {
                    $rpPerView = (int) config('monetisasi.rp_per_view', 50);
                    $karya->increment('pendapatan', $rpPerView);
                }
            }
        }

        return view('karya.show', compact('karya'));
    }

    // Jika karya masih draft, hanya pemilik + kolaborator yang bisa lihat
    if ($karya->status === 'draft') {
        if ($karya->user_id === Auth::id() || $karya->isCollaborator(Auth::user())) {
            return view('karya.show', compact('karya'));
        }
        abort(403, 'Karya ini tidak dapat diakses');
    }

    abort(403, 'Karya ini tidak dapat diakses');
}


    public function edit(Karya $karya)
    {
        // Hanya pemilik dan kolaborator bisa edittt
        if ($karya->user_id !== Auth::id() && !$karya->isCollaborator(Auth::user())) {
    abort(403, 'Anda tidak memiliki akses untuk mengedit karya ini');
}


        return view('karya.edit', compact('karya'));
    }

    public function update(Request $request, Karya $karya)
    {
        // Hanya pemilik yang bisa update
        if ($karya->user_id !== Auth::id() && !$karya->isCollaborator(Auth::user())) {
    abort(403, 'Anda tidak memiliki akses untuk mengedit karya ini');
}


        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'isi' => 'required|string',
            'tags' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        // Handle upload cover jika ada
        $coverPath = $karya->cover; // Gunakan cover lama sebagai default
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($karya->cover && Storage::disk('public')->exists($karya->cover)) {
                Storage::disk('public')->delete($karya->cover);
            }
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $karya->update([
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'jenis' => $validated['jenis'], // ← Tambahkan ini
            'deskripsi' => $validated['deskripsi'] ?? null, // ← Tambahkan ini
            'konten' => $validated['isi'],
            'kategori' => $validated['tags'] ?? null,
            'status' => $request->has('publish') ? 'publish' : 'draft',
            'is_draft' => !$request->has('publish'),
            'cover' => $coverPath, // ← Tambahkan ini
        ]);

        return redirect()->route('karya.index')->with('success', 'Karya berhasil diupdate!');
    }

    public function destroy(Karya $karya)
    {
        // Pastikan user hanya bisa hapus karya sendiri
        if ($karya->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $karya->delete();

        return redirect()->route('karya.index')->with('success', 'Karya berhasil dihapus!');
    }
        public function dashboard(Request $request)
    {
        $search = $request->input('search');
        
        // Ambil karya user sendiri (untuk section "Karya Saya")
        $myKarya = Karya::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
        
        // Jika ada pencarian, cari dari SEMUA karya yang dipublikasikan
        if ($search) {
            $karya = Karya::where('status', 'publish') // Hanya yang sudah dipublikasikan
                ->where(function($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('jenis', 'LIKE', "%{$search}%")
                    ->orWhere('kategori', 'LIKE', "%{$search}%")
                    ->orWhere('konten', 'LIKE', "%{$search}%");
                })
                ->with('user') // Load relasi user untuk menampilkan penulis
                ->orderBy('updated_at', 'desc')
                ->paginate(9);
        } else {
            // Jika tidak ada pencarian, tampilkan karya user sendiri
            $karya = Karya::where('user_id', Auth::id())
                ->orderBy('updated_at', 'desc')
                ->paginate(6);
        }

        // Data trending (static untuk sementara)
        $trending = [
            (object)[
                'title' => 'Karya Pertama',
                'deskripsi' => 'Sebuah karya menarik tentang petualangan fantasi.',
                'rating' => 4.8
            ],
            (object)[
                'title' => 'Karya Kedua',
                'deskripsi' => 'Cerita drama penuh emosi yang menyentuh hati.',
                'rating' => 4.6
            ],
            (object)[
                'title' => 'Karya Ketiga',
                'deskripsi' => 'Komedi ringan yang cocok menemani waktu santai.',
                'rating' => 4.7
            ],
        ];

        return view('dashboard', compact('trending', 'karya', 'myKarya', 'search'));
    }

}