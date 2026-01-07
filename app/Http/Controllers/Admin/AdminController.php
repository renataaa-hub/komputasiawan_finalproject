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
        Carbon::setLocale('id');

        $start = Carbon::today()->subDays(6);
        $end = Carbon::today();

        // hasil: ['2026-01-01' => 10, '2026-01-02' => 4, ...]
        $counts = Visitor::selectRaw('DATE(visit_date) as d, COUNT(*) as c')
            ->whereBetween('visit_date', [$start->toDateString(), $end->toDateString()])
            ->groupBy('d')
            ->pluck('c', 'd');

        $chartLabels = [];
        $chartData = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();

            // label lebih bagus pakai tanggal biar tidak duplikat "Senin"
            $chartLabels[] = $date->translatedFormat('D, d M');
            $chartData[] = Visitor::whereDate('visit_date', $date->toDateString())->count();
        }

        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalKarya' => Karya::count(),
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }


    public function users()
    {
        $users = User::withCount('karyas')
        ->with(['activeSubscription', 'latestSubscription'])
        ->latest()
        ->get();


        return view('admin.users', compact('users'));
    }
}