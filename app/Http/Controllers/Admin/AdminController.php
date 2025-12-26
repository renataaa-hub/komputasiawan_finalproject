<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Karya;

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
}
