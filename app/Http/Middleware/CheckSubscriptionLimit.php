<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionLimit
{
    public function handle(Request $request, Closure $next, string $action): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        switch ($action) {
            case 'draft':
                if (!$user->canCreateDraft()) {
                    return redirect()->back()->with('error', 'Batas draft Anda sudah tercapai. Upgrade subscription untuk membuat lebih banyak draft.');
                }
                break;
                
            case 'publish':
                if (!$user->canPublish()) {
                    return redirect()->back()->with('error', 'Batas publikasi Anda sudah tercapai. Upgrade subscription untuk publikasi lebih banyak.');
                }
                break;
                
            case 'monetization':
                if (!$user->hasFeature('monetization')) {
                    return redirect()->back()->with('error', 'Fitur monetisasi hanya tersedia untuk paket Pro dan Premium.');
                }
                break;
                
            case 'collaboration':
                if (!$user->hasFeature('collaboration')) {
                    return redirect()->back()->with('error', 'Fitur kolaborasi hanya tersedia untuk paket Pro dan Premium.');
                }
                break;
        }

        return $next($request);
    }
}