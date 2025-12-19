<x-app-layout>
    <div class="p-6">
        <div class="max-w-3xl mx-auto">
            
            @if($subscription->status === 'active')
            <!-- SUCCESS STATE -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 text-green-600 rounded-full mb-6 animate-bounce">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">🎉 Pembayaran Berhasil!</h1>
                <p class="text-gray-600 text-lg">Subscription Anda sudah aktif dan siap digunakan</p>
            </div>

            <div class="bg-white rounded-xl shadow-xl border-2 border-green-200 p-8 mb-6">
                <h3 class="text-2xl font-bold mb-6 text-gray-900">Detail Subscription Anda</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-mono text-sm bg-gray-100 px-3 py-1 rounded text-gray-900">{{ $subscription->order_id }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Paket:</span>
                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-bold">{{ ucfirst($subscription->plan) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg font-semibold flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <span class="text-gray-900 font-medium capitalize">{{ $subscription->payment_type ?? '-' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Mulai:</span>
                        <span class="text-gray-900 font-medium">{{ $subscription->started_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Berakhir:</span>
                        <span class="text-gray-900 font-medium">{{ $subscription->expired_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-xl text-gray-700">Total Dibayar:</span>
                        <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($subscription->price, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-6 p-5 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-blue-800 font-medium mb-2">✨ Sekarang Anda bisa:</p>
                    <ul class="text-blue-700 text-sm space-y-1">
                        @php
                            $planConfig = config("plans.{$subscription->plan}");
                        @endphp
                        <li>✓ Membuat {{ is_null($planConfig['features']['max_drafts']) ? 'unlimited' : $planConfig['features']['max_drafts'] }} draft</li>
                        <li>✓ Mempublikasikan {{ is_null($planConfig['features']['max_publications']) ? 'unlimited' : $planConfig['features']['max_publications'] }} karya</li>
                        @if($planConfig['features']['monetization'])
                        <li>✓ Mengakses fitur monetisasi</li>
                        @endif
                        @if($planConfig['features']['collaboration'])
                        <li>✓ Berkolaborasi dengan penulis lain</li>
                        @endif
                        @if($planConfig['features']['full_statistics'])
                        <li>✓ Melihat statistik lengkap</li>
                        @endif
                    </ul>
                </div>
            </div>
            
            @else
            <!-- PENDING STATE -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-yellow-100 text-yellow-600 rounded-full mb-6">
                    <svg class="w-16 h-16 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">⏳ Pembayaran Pending</h1>
                <p class="text-gray-600 text-lg">Silakan selesaikan pembayaran Anda</p>
            </div>

            <div class="bg-white rounded-xl shadow-xl border-2 border-yellow-200 p-8 mb-6">
                <h3 class="text-2xl font-bold mb-6 text-gray-900">Detail Order</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-mono text-sm bg-gray-100 px-3 py-1 rounded text-gray-900">{{ $subscription->order_id }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-semibold">Pending</span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-xl text-gray-700">Total:</span>
                        <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($subscription->price, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-6 p-5 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-yellow-800 font-medium mb-2">📌 Catatan Penting:</p>
                    <ul class="text-yellow-700 text-sm space-y-2">
                        <li>• Subscription akan aktif setelah pembayaran dikonfirmasi</li>
                        <li>• Proses konfirmasi biasanya 1-15 menit (tergantung metode pembayaran)</li>
                        <li>• Anda akan menerima notifikasi setelah pembayaran berhasil</li>
                        <li>• Jika lebih dari 24 jam belum aktif, hubungi customer service</li>
                    </ul>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('subscription') }}" 
                    class="flex items-center justify-center gap-2 bg-gray-200 text-gray-700 py-4 px-6 rounded-xl hover:bg-gray-300 transition font-medium shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Lihat Subscription
                </a>
                <a href="{{ route('dashboard') }}" 
                    class="flex items-center justify-center gap-2 bg-blue-600 text-white py-4 px-6 rounded-xl hover:bg-blue-700 transition font-medium shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
            
            @if($subscription->status === 'active')
            <div class="mt-8 text-center">
                <p class="text-gray-500 text-sm">
                    Ada pertanyaan? <a href="#" class="text-blue-600 hover:underline">Hubungi Customer Service</a>
                </p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>