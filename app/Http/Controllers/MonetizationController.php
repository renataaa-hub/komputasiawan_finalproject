<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonetizationController extends Controller
{
    public function claim(Request $request, Karya $karya)
    {
        // pastikan karya milik user yang login
        abort_unless($karya->user_id === $request->user()->id, 403);

        if (!$karya->monetization_active) {
            return back()->with('error', 'Monetisasi nonaktif.');
        }

        $blocks = $karya->claimableBlocks();
        $amount = $karya->claimableAmount();

        if ($blocks <= 0 || $amount <= 0) {
            return back()->with('error', 'Belum ada pendapatan yang bisa diklaim.');
        }

        DB::transaction(function () use ($request, $karya, $blocks, $amount) {
            // lock row supaya ga double-claim kalau klik 2x
            $karya->lockForUpdate();

            // hitung ulang setelah lock
            $blocksNow = $karya->claimableBlocks();
            $amountNow = $karya->claimableAmount();

            if ($blocksNow <= 0 || $amountNow <= 0) {
                return;
            }

            $karya->claimed_blocks += $blocksNow;
            $karya->save();

            $wallet = Wallet::firstOrCreate(['user_id' => $request->user()->id], ['balance' => 0]);
            $wallet->balance += $amountNow;
            $wallet->save();

            // OPTIONAL: simpan log klaim (recommended)
            DB::table('earning_claims')->insert([
                'user_id' => $request->user()->id,
                'karya_id' => $karya->id,
                'blocks' => $blocksNow,
                'amount' => $amountNow,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return back()->with('success', 'Berhasil klaim pendapatan!');
    }
}