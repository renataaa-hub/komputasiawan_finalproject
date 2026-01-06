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
        try {
            // kalau tabel belum ada di DB Azure, jangan insert
            if (Schema::hasTable('visitors')) {
                \App\Models\Visitor::create([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'visit_date' => now()->toDateString(),
                ]);
            }   
        } catch (Throwable $e) {
            // jangan bikin web 500
            // optional: \Log::warning('TrackVisitor error: '.$e->getMessage());
        }

        return $next($request);
    }
}
