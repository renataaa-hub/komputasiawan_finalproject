<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('midtrans/webhook')) {
            return $next($request);
        }

        // kalau table belum ada pun, jangan sampai ngebunuh request:
        try {
            Visitor::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'visit_date' => \Carbon\Carbon::today(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('TrackVisitor skipped: '.$e->getMessage());
        }

        return $next($request);
    }

}
