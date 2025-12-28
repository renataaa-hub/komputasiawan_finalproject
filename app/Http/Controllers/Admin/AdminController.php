<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Karya;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalKarya' => Karya::count(),
        ]);
    }

    public function users()
    {
        // ambil user + jumlah karya masing-masing
        $users = User::withCount('karyas')->latest()->get();

        return view('admin.users', compact('users'));
    }


    public function visitorChart()
    {
        return DB::table('visits')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

}
