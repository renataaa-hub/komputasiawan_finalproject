<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Karya;
use App\Models\Visitor; 
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Set bahasa tanggal ke Indonesia
        Carbon::setLocale('id');

        $chartLabels = [];
        $chartData = [];

        // Loop selama 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Masukkan Nama Hari ke Label
            $chartLabels[] = $date->translatedFormat('l'); 

            // Hitung jumlah Visitor pada tanggal tersebut
            
            $chartData[] = Visitor::whereDate('visit_date', $date)->count(); 
        }

        return view('admin.dashboard', [
            // Total user tetap diambil untuk Kartu Statistik di atas
            'totalUsers' => User::count(),
            'totalKarya' => Karya::count(),
            
            // Data grafik sekarang berisi data Pengunjung
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }

    public function users()
    {
        $users = User::withCount('karyas')->latest()->get();
        return view('admin.users', compact('users'));
    }
}