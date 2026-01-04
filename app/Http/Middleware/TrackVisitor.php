<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // LOGIKA BARU: HITUNG SEMUA KLIK (PAGE VIEWS)
        // Tidak perlu cek IP, langsung simpan data kunjungan baru.
        
        Visitor::create([
            'ip_address' => $request->ip(), // IP tetap disimpan sekadar info, tapi tidak dicek unik/tidaknya
            'user_agent' => $request->userAgent(),
            'visit_date' => \Carbon\Carbon::today(),
        ]);

        return $next($request);
    }
}