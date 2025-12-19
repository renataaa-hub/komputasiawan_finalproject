<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Karya $karya)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'karya_id' => $karya->id,
            'comment' => $validated['comment'],
        ]);

        // Send notification to karya owner (if not self-comment)
        if ($karya->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $karya->user_id,
                'type' => 'comment',
                'title' => 'Komentar Baru!',
                'message' => Auth::user()->name . ' berkomentar di karya "' . $karya->judul . '"',
                'data' => [
                    'karya_id' => $karya->id,
                    'comment_id' => $comment->id,
                    'user_id' => Auth::id(),
                ]
            ]);
        }

        return redirect()->route('karya.show', $karya->id)->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(Comment $comment)
    {
        // Only owner can delete their comment
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak bisa menghapus komentar orang lain');
        }

        $karyaId = $comment->karya_id;
        $comment->delete();

        return redirect()->route('karya.show', $karyaId)->with('success', 'Komentar berhasil dihapus!');
    }
}