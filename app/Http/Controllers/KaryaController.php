<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Karya;
use Illuminate\Http\Request;

class KaryaController extends Controller
{
    public function index()
    {
        $karya = Karya::where('user_id', Auth()->id())->get();
        $draft = Karya::where('user_id', Auth()->id())->where('status', 'draft')->get();
        $published = Karya::where('user_id', Auth()->id())->where('status', 'published')->get();

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

        Karya::create([
            'user_id' => Auth()->id(),  // pastikan user login
            'judul' => $validated['judul'],
            'jenis' => $validated['jenis'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'isi' => $validated['isi'],
            'status' => $validated['status'] ?? 'draft',
        ]);

        return redirect()->route('karya.index')->with('success', 'Karya berhasil dibuat!');
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
