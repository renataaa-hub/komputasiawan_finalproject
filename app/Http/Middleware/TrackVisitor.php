<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class TrackVisitor
{
    public function handle($request, Closure $next)
        {
            DB::table('visits')->insert([
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $next($request);
        }
}
