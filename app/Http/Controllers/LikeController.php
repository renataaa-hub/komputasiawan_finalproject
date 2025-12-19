<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    public function toggle(Karya $karya)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            // Check if already liked
            $like = Like::where('user_id', $user->id)
                        ->where('karya_id', $karya->id)
                        ->first();
            
            if ($like) {
                // Unlike
                $like->delete();
                $liked = false;
            } else {
                // Like
                Like::create([
                    'user_id' => $user->id,
                    'karya_id' => $karya->id,
                ]);
                $liked = true;
                
                // Send notification to karya owner (if not self-like)
                if ($karya->user_id !== $user->id) {
                    Notification::create([
                        'user_id' => $karya->user_id,
                        'type' => 'like',
                        'title' => 'Karya Disukai!',
                        'message' => $user->name . ' menyukai karya "' . $karya->judul . '"',
                        'data' => [
                            'karya_id' => $karya->id,
                            'user_id' => $user->id,
                        ]
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $karya->likes()->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Like toggle error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}