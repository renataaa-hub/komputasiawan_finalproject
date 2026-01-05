<x-app-layout>
    <div class="p-6">
        <div class="max-w-2xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 text-blue-600 rounded-full mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Subscription</h1>
                <p class="text-gray-600">Order ID: <span class="font-mono text-sm bg-gray-100 px-3 py-1 rounded">{{ $orderId }}</span></p>
            </div>

            <!-- Subscription Details -->
            <div class="bg-white rounded-xl shadow-lg border-2 border-gray-200 p-6 mb-6">
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Detail Subscription
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Paket:</span>
                        <span class="font-bold text-xl text-gray-900">{{ $planConfig['name'] }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Durasi:</span>
                        <span class="font-semibold text-gray-900">30 Hari (1 Bulan)</span>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <p class="text-sm font-semibold text-blue-900 mb-2">Fitur yang akan Anda dapatkan:</p>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>✓ {{ is_null($planConfig['features']['max_drafts']) ? 'Unlimited' : $planConfig['features']['max_drafts'] }} Draft</li>
                            <li>✓ {{ is_null($planConfig['features']['max_publications']) ? 'Unlimited' : $planConfig['features']['max_publications'] }} Publikasi</li>
                            @if($planConfig['features']['monetization'])
                            <li>✓ Akses Monetisasi</li>
                            @endif
                            @if($planConfig['features']['full_statistics'])
                            <li>✓ Statistik Lengkap</li>
                            @endif
                            @if($planConfig['features']['collaboration'])
                            <li>✓ Kolaborasi Menulis</li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t-2">
                        <span class="text-lg text-gray-600">Total Pembayaran:</span>
                        <span class="font-bold text-3xl text-gray-900">Rp {{ number_format($subscription->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Button -->
            <button id="pay-button" 
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-bold text-lg shadow-lg transform hover:scale-105 flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Bayar Sekarang
            </button>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm mb-4">
                    🔒 Anda akan diarahkan ke halaman pembayaran Midtrans yang aman
                </p>
                <div class="flex items-center justify-center gap-4 text-xs text-gray-400">
                    <span>Powered by</span>
                    <span class="font-bold text-blue-600">Midtrans</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    @if(config('midtrans.is_production'))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
    
    <script type="text/javascript">
        document.getElementById('pay-button').addEventListener('click', function () {
            this.disabled = true;
            this.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route("subscription.finish") }}?order_id={{ $orderId }}&status=success';
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route("subscription.finish") }}?order_id={{ $orderId }}&status=pending';
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal! Silakan coba lagi.');
                    location.reload();
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    location.reload();
                }
            });
        });
    </script>
</x-app-layout>