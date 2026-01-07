<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class RecordVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $ip = $request->ip();
        $today = Carbon::today()->toDateString();

        Visitor::updateOrCreate(
            ['ip' => $ip, 'visit_date' => $today],
            ['visit_date' => $today]
        );

        return $response;
    }
}
