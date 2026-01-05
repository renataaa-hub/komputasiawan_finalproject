<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class MidtransQrisTestController extends Controller
{
    public function create()
    {
        $serverKey = env('MIDTRANS_SERVER_KEY'); // harus SB-... untuk sandbox

        $payload = [
            "payment_type" => "qris",
            "transaction_details" => [
                "order_id" => "ORDER-QRIS-" . time(),
                "gross_amount" => 1000
            ],
        ];

        $res = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post('https://api.sandbox.midtrans.com/v2/charge', $payload);

        return response()->json([
            'http_status' => $res->status(),
            'response' => $res->json(),
        ], $res->status());
    }
}
