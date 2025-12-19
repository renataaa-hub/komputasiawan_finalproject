<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $user = Auth::user();
        $activeSubscription = $user->activeSubscription;
        $plans = config('plans');
        
        // Get usage stats
        $stats = [
            'drafts' => $user->karyas()->where('status', 'draft')->count(),
            'publications' => $user->karyas()->where('status', 'publish')->count(),
        ];
        
        return view('subscription', compact('activeSubscription', 'plans', 'stats'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:pro,premium'
        ]);

        $user = Auth::user();
        
        // Check if user already has active subscription
        if ($user->hasActiveSubscription()) {
            return back()->with('error', 'Anda sudah memiliki subscription aktif. Silakan tunggu hingga subscription berakhir atau batalkan subscription saat ini.');
        }

        $plan = $request->plan;
        $planConfig = config("plans.{$plan}");
        $price = $planConfig['price'];
        $orderId = 'ORDER-' . strtoupper($plan) . '-' . time() . '-' . $user->id;

        // Create subscription record
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan' => $plan,
            'price' => $price,
            'status' => 'pending',
            'order_id' => $orderId,
        ]);

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'order_id' => $orderId,
            'gross_amount' => $price,
            'status' => 'pending',
        ]);

        // Midtrans transaction params
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $plan,
                    'price' => $price,
                    'quantity' => 1,
                    'name' => 'Subscription ' . $planConfig['name'] . ' - 1 Bulan'
                ]
            ],
            'callbacks' => [
                'finish' => route('subscription.finish'),
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            return view('subscription-payment', [
                'snapToken' => $snapToken,
                'subscription' => $subscription,
                'orderId' => $orderId,
                'planConfig' => $planConfig
            ]);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            
            $subscription->delete();
            $transaction->delete();
            
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $subscription = Subscription::where('order_id', $orderId)->first();

        if (!$subscription) {
            return redirect()->route('subscription')->with('error', 'Subscription tidak ditemukan');
        }

        return view('subscription-finish', compact('subscription'));
    }

    public function webhook(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $paymentType = $notification->payment_type;
            $transactionId = $notification->transaction_id;

            Log::info('Midtrans Webhook', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'payment_type' => $paymentType
            ]);

            // Find subscription
            $subscription = Subscription::where('order_id', $orderId)->first();
            
            if (!$subscription) {
                Log::error('Subscription not found: ' . $orderId);
                return response()->json(['message' => 'Subscription not found'], 404);
            }

            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->first();

            // Update transaction with Midtrans response
            if ($transaction) {
                $transaction->update([
                    'transaction_id' => $transactionId,
                    'payment_type' => $paymentType,
                    'midtrans_response' => $request->all()
                ]);
            }

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $subscription->activate();
                    if ($transaction) $transaction->update(['status' => 'settlement']);
                    Log::info('Subscription activated (capture): ' . $orderId);
                }
            } elseif ($transactionStatus == 'settlement') {
                $subscription->activate();
                if ($transaction) $transaction->update(['status' => 'settlement']);
                Log::info('Subscription activated (settlement): ' . $orderId);
            } elseif ($transactionStatus == 'pending') {
                $subscription->update(['status' => 'pending']);
                if ($transaction) $transaction->update(['status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $subscription->update(['status' => 'cancelled']);
                if ($transaction) $transaction->update(['status' => $transactionStatus]);
                Log::info('Subscription cancelled: ' . $orderId);
            }

            $subscription->update([
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'midtrans_response' => $request->all()
            ]);

            return response()->json(['message' => 'Notification handled successfully']);
            
        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function cancel($id)
    {
        $subscription = Subscription::findOrFail($id);
        
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $subscription->update([
            'status' => 'cancelled',
            'expired_at' => now()
        ]);

        return redirect()->route('subscription')->with('success', 'Subscription berhasil dibatalkan');
    }
}