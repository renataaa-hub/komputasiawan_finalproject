<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\User;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\CollaborationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    // cek fitur collaboration berdasarkan config plans + subscription aktif
    private function canUseCollaboration(User $user): bool
    {
        $active = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expired_at', '>', now())
            ->latest('expired_at')
            ->first();

        $plan = $active?->plan ?? 'free';
        return (bool) (config("plans.$plan.features.collaboration") ?? false);
    }

    /**
     * OWNER invite user lain untuk kolaborasi (by email)
     */
    public function invite(Request $request, Karya $karya)
    {
        $user = Auth::user();

        // hanya pemilik karya yang boleh invite
        if ($karya->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // fitur harus aktif (pro/premium)
        if (!$this->canUseCollaboration($user)) {
            return back()->with('error', 'Fitur kolaborasi hanya tersedia untuk Pro/Premium.');
        }

        $data = $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:500',
        ]);

        $target = User::where('email', $data['email'])->first();
        if (!$target) {
            return back()->with('error', 'User dengan email tersebut tidak ditemukan.');
        }

        if ($target->id === $user->id) {
            return back()->with('error', 'Tidak bisa mengundang diri sendiri.');
        }

        // sudah jadi collaborator?
        if ($karya->collaborators()->where('users.id', $target->id)->exists()) {
            return back()->with('error', 'User tersebut sudah menjadi kolaborator.');
        }

        // ada pending request/invite?
        $exists = CollaborationRequest::where('karya_id', $karya->id)
            ->where('from_user_id', $user->id)
            ->where('to_user_id', $target->id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Undangan kolaborasi sudah pernah dikirim dan masih pending.');
        }

        $req = CollaborationRequest::create([
            'karya_id' => $karya->id,
            'from_user_id' => $user->id,
            'to_user_id' => $target->id,
            'type' => 'invite',
            'status' => 'pending',
            'message' => $data['message'] ?? null,
        ]);

        // Notif ke penerima
        Notification::create([
            'user_id' => $target->id,
            'type' => 'collaboration',
            'title' => 'Undangan Kolaborasi',
            'message' => $user->name . ' mengundang Anda untuk kolaborasi pada karya: "' . $karya->judul . '"',
            'data' => [
                'request_id' => $req->id,
                'karya_id' => $karya->id,
                'from_user_id' => $user->id,
                'action' => 'needs_response',
            ],
        ]);

        return back()->with('success', 'Undangan kolaborasi berhasil dikirim!');
    }

    /**
     * USER request kolaborasi ke pemilik karya
     */
    public function requestToOwner(Request $request, Karya $karya)
    {
        $user = Auth::user();

        // tidak boleh request ke karya sendiri
        if ($karya->user_id === $user->id) {
            return back()->with('error', 'Kamu adalah pemilik karya ini.');
        }

        // fitur harus aktif (pro/premium) untuk yang minta
        if (!$this->canUseCollaboration($user)) {
            return back()->with('error', 'Fitur kolaborasi hanya tersedia untuk Pro/Premium.');
        }

        // sudah jadi collaborator?
        if ($karya->collaborators()->where('users.id', $user->id)->exists()) {
            return back()->with('error', 'Kamu sudah menjadi kolaborator di karya ini.');
        }

        // ada pending request?
        $exists = CollaborationRequest::where('karya_id', $karya->id)
            ->where('from_user_id', $user->id)
            ->where('to_user_id', $karya->user_id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Permintaan kolaborasi kamu masih pending.');
        }

        $data = $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        $req = CollaborationRequest::create([
            'karya_id' => $karya->id,
            'from_user_id' => $user->id,
            'to_user_id' => $karya->user_id,
            'type' => 'request',
            'status' => 'pending',
            'message' => $data['message'] ?? null,
        ]);

        // notif ke owner
        Notification::create([
            'user_id' => $karya->user_id,
            'type' => 'collaboration',
            'title' => 'Permintaan Kolaborasi',
            'message' => $user->name . ' meminta kolaborasi pada karya: "' . $karya->judul . '"',
            'data' => [
                'request_id' => $req->id,
                'karya_id' => $karya->id,
                'from_user_id' => $user->id,
                'action' => 'needs_response',
            ],
        ]);

        return back()->with('success', 'Permintaan kolaborasi berhasil dikirim!');
    }

    public function accept(CollaborationRequest $requestModel)
    {
        $user = Auth::user();

        if ($requestModel->to_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $karya = $requestModel->karya;

        // add collaborator
        $karya->collaborators()->syncWithoutDetaching([$requestModel->from_user_id => ['role' => 'editor']]);

        $requestModel->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // notif balik ke pengirim
        Notification::create([
            'user_id' => $requestModel->from_user_id,
            'type' => 'collaboration',
            'title' => 'Kolaborasi Diterima',
            'message' => $user->name . ' menerima kolaborasi untuk karya: "' . $karya->judul . '"',
            'data' => [
                'karya_id' => $karya->id,
                'action' => 'info',
            ],
        ]);

        return back()->with('success', 'Kolaborasi diterima! User sekarang menjadi kolaborator.');
    }

    public function reject(CollaborationRequest $requestModel)
    {
        $user = Auth::user();

        if ($requestModel->to_user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $karya = $requestModel->karya;

        $requestModel->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        // notif balik ke pengirim
        Notification::create([
            'user_id' => $requestModel->from_user_id,
            'type' => 'collaboration',
            'title' => 'Kolaborasi Ditolak',
            'message' => $user->name . ' menolak kolaborasi untuk karya: "' . $karya->judul . '"',
            'data' => [
                'karya_id' => $karya->id,
                'action' => 'info',
            ],
        ]);

        return back()->with('success', 'Permintaan kolaborasi ditolak.');
    }
}
