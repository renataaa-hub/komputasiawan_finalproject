<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Karya;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'isi' => 'required|string',
            'tags' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        // Handle upload cover jika ada
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Karya::updateOrCreate(
            [
                'id' => $request->karya_id,
                'user_id' => Auth::id(),
            ],
            [
                'judul' => $validated['judul'],
                'slug' => Str::slug($validated['judul']),
                'jenis' => $validated['jenis'], // ← Tambahkan ini
                'deskripsi' => $validated['deskripsi'] ?? null, // ← Tambahkan ini
                'konten' => $validated['isi'],
                'kategori' => $validated['tags'] ?? null,
                'status' => 'publish',
                'is_draft' => false,
                'akses' => 'publik',
                'cover' => $coverPath ?? null, // ← Tambahkan ini
            ]
        );

        return redirect()->route('karya.index')->with('success', 'Karya berhasil dibuat!');
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
        $karya = Karya::where('user_id', Auth()->id())->get();
        return view('karya.monetisasi', compact('karya'));
    }
    // Tambahkan method ini di KaryaController

    public function show(Karya $karya)
    {
        // Jika karya dipublikasikan (publish), semua orang bisa lihat
        if ($karya->status === 'publish') {
            // Increment views (opsional)
            $karya->increment('views');
            
            return view('karya.show', compact('karya'));
        }
        
        // Jika karya masih draft, hanya pemilik yang bisa lihat
        if ($karya->status === 'draft' && $karya->user_id === Auth::id()) {
            return view('karya.show', compact('karya'));
        }
        
        // Selain itu, unauthorized
        abort(403, 'Karya ini tidak dapat diakses');
    }

    public function edit(Karya $karya)
    {
        // Hanya pemilik yang bisa edit
        if ($karya->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit karya ini');
        }

        return view('karya.edit', compact('karya'));
    }

    public function update(Request $request, Karya $karya)
    {
        // Hanya pemilik yang bisa update
        if ($karya->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate karya ini');
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