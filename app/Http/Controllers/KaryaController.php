<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Karya;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'status' => 'in:draft,published',
            'tags' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
            'status_monetisasi' => 'nullable|in:active,inactive',
            'harga' => 'nullable|integer',
            'pendapatan' => 'nullable|integer',
            
        ]);
        Karya::updateOrCreate(
        [
            'id' => $request->karya_id,
            'user_id' => Auth::id(),
        ],
        [
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'konten' => $validated['isi'],
            'kategori' => $validated['tags'] ?? null,
            'status' => 'publish',
            'is_draft' => false,
            'akses' => 'publik',
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
        $karya = Karya::where('user_id', auth()->id())->get();

        $totalKarya = $karya->count();
        $totalDraft = $karya->where('status', 'draft')->count();
        $totalPublished = $karya->where('status', 'published')->count();
        $totalViews = $karya->sum('views'); // pastikan kolom views ada di tabel

        return view('karya.statistik', compact('karya', 'totalKarya', 'totalDraft', 'totalPublished', 'totalViews'));
    }


    public function monetisasi()
    {
        $karya = Karya::where('user_id', Auth()->id())->get();
        return view('karya.monetisasi', compact('karya'));
    }



}