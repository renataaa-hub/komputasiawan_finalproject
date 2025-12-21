<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Pilih Paket Subscription</h1>
            <p class="text-gray-600 text-lg">Mulai gratis dengan Basic, atau upgrade untuk lebih banyak fitur!</p>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-800">✓ {{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-red-800">✕ {{ session('error') }}</p>
        </div>
        @endif

        <!-- Current Plan Status -->
        @if($activeSubscription)
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl p-6 shadow-lg">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-5 py-2 bg-blue-600 text-white rounded-full text-sm font-bold shadow">
                            🎉 {{ ucfirst($activeSubscription->plan) }}
                        </span>
                        <span class="text-green-600 font-semibold">● Aktif</span>
                    </div>
                    
                    <p class="text-gray-700 mb-4 text-lg">
                        Berlaku hingga: <strong>{{ $activeSubscription->expired_at->format('d M Y, H:i') }}</strong>
                        <span class="text-gray-600">({{ $activeSubscription->expired_at->diffForHumans() }})</span>
                    </p>
                    
                    <!-- Usage Stats -->
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $draftPercent = $stats['max_drafts'] === '∞' ? 0 : ($stats['drafts'] / (int)$stats['max_drafts']) * 100;
                            $pubPercent = $stats['max_publications'] === '∞' ? 0 : ($stats['publications'] / (int)$stats['max_publications']) * 100;
                        @endphp
                        
                        <div class="bg-white rounded-lg p-4 border-2 border-gray-200">
                            <p class="text-sm text-gray-600 mb-2 font-medium">📝 Draft</p>
                            <p class="text-2xl font-bold text-gray-900 mb-2">
                                {{ $stats['drafts'] }} / {{ $stats['max_drafts'] }}
                            </p>
                            @if($stats['max_drafts'] !== '∞')
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($draftPercent, 100) }}%"></div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border-2 border-gray-200">
                            <p class="text-sm text-gray-600 mb-2 font-medium">📚 Publikasi</p>
                            <p class="text-2xl font-bold text-gray-900 mb-2">
                                {{ $stats['publications'] }} / {{ $stats['max_publications'] }}
                            </p>
                            @if($stats['max_publications'] !== '∞')
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($pubPercent, 100) }}%"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('subscription.cancel', $activeSubscription->id) }}" method="POST" 
                    onsubmit="return confirm('Yakin ingin membatalkan subscription? Anda akan kembali ke paket Basic (Free) setelah masa aktif berakhir.')">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-md">
                        Batalkan Subscription
                    </button>
                </form>
            </div>
        </div>
        @else
        <!-- Free Plan Status -->
        <div class="mb-8 bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-gray-300 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="text-4xl">🆓</div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-bold text-gray-900">Paket Basic (Gratis)</h3>
                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-medium">Aktif</span>
                    </div>
                    <p class="text-gray-600 mb-4">Anda sedang menggunakan paket gratis. Upgrade untuk mendapatkan lebih banyak fitur!</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $draftPercent = ($stats['drafts'] / 3) * 100;
                            $pubPercent = ($stats['publications'] / 5) * 100;
                        @endphp
                        
                        <div class="bg-white rounded-lg p-3 border">
                            <p class="text-xs text-gray-500 mb-1">📝 Draft</p>
                            <p class="text-lg font-bold text-gray-900 mb-1">{{ $stats['drafts'] }} / 3</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($draftPercent, 100) }}%"></div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border">
                            <p class="text-xs text-gray-500 mb-1">📚 Publikasi</p>
                            <p class="text-lg font-bold text-gray-900 mb-1">{{ $stats['publications'] }} / 5</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($pubPercent, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Pricing Cards (Only Pro & Premium) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            
            <!-- FREE BASIC CARD (Non-clickable, just for reference) -->
            <div class="relative bg-gray-50 rounded-2xl shadow-lg border-2 border-gray-300 opacity-90">
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-200 text-gray-600 rounded-full mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Basic</h2>
                        <p class="text-gray-600">Paket gratis untuk memulai</p>
                    </div>
                    
                    <div class="text-center mb-8 pb-6 border-b-2">
                        <div class="text-5xl font-bold text-gray-900 mb-2">Rp 0</div>
                        <p class="text-gray-500">selamanya</p>
                    </div>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700 font-medium"><strong>3</strong> Draft</span>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700 font-medium"><strong>5</strong> Publikasi</span>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Statistik Dasar</span>
                        </div>
                    </div>
                    
                    <div class="w-full py-4 px-6 bg-gray-300 text-gray-600 rounded-xl font-bold text-lg text-center cursor-not-allowed">
                        {{ $activeSubscription ? 'Paket Saat Ini' : '✓ Paket Aktif' }}
                    </div>
                </div>
            </div>
            
            @foreach($plans as $key => $plan)
            <div class="relative bg-white rounded-2xl shadow-xl border-2 {{ $key === 'pro' ? 'border-green-500 transform scale-105' : 'border-gray-200' }} hover:shadow-2xl transition-all">
                
                @if($key === 'pro')
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-1 rounded-full text-sm font-bold shadow-lg">
                    ⭐ PALING POPULER
                </div>
                @endif
                
                <div class="p-8">
                    <div class="text-center mb-6 {{ $key === 'pro' ? 'mt-3' : '' }}">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 {{ $key === 'pro' ? 'bg-green-100 text-green-600' : 'bg-purple-100 text-purple-600' }}">
                            @if($key === 'pro')
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            @else
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            @endif
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h2>
                        <p class="text-gray-600">{{ $plan['description'] }}</p>
                    </div>
                    
                    <div class="text-center mb-8 pb-6 border-b-2">
                        <div class="text-5xl font-bold text-gray-900 mb-2">
                            Rp {{ number_format($plan['price'], 0, ',', '.') }}
                        </div>
                        <p class="text-gray-500">per bulan</p>
                    </div>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700 font-medium">
                                <strong>{{ is_null($plan['features']['max_drafts']) ? 'Unlimited' : $plan['features']['max_drafts'] }}</strong> Draft
                            </span>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700 font-medium">
                                <strong>{{ is_null($plan['features']['max_publications']) ? 'Unlimited' : $plan['features']['max_publications'] }}</strong> Publikasi
                            </span>
                        </div>
                        
                        @if($plan['features']['collaboration'])
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Kolaborasi Menulis</span>
                        </div>
                        @endif
                        
                        @if($plan['features']['monetization'])
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Monetisasi Karya</span>
                        </div>
                        @endif
                        
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Statistik Lengkap</span>
                        </div>
                        
                        @if($plan['features']['download_statistics'])
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Download Statistik</span>
                        </div>
                        @endif
                        
                        @if($plan['features']['priority_support'])
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Prioritas Dukungan</span>
                        </div>
                        @endif
                        
                        @if($plan['features']['consultation'])
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Konsultasi Pribadi</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- CTA Button -->
                    <form action="{{ route('subscription.subscribe') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $key }}">
                        <button type="submit" 
                            class="w-full py-4 px-6 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-lg
                            {{ $key === 'pro' ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-purple-600 hover:bg-purple-700 text-white' }}"
                            @if($activeSubscription) disabled @endif>
                            {{ $activeSubscription ? '✓ Sudah Berlangganan' : 'Upgrade ke ' . $plan['name'] }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            
        </div>

        <!-- FAQ / Info -->
        <div class="bg-gray-50 rounded-xl p-8 text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-4">💳 Metode Pembayaran Tersedia</h3>
            <div class="flex flex-wrap justify-center gap-4 mb-6">
                <span class="px-4 py-2 bg-white rounded-lg shadow text-sm font-medium">💳 Credit Card</span>
                <span class="px-4 py-2 bg-white rounded-lg shadow text-sm font-medium">📱 GoPay</span>
                <span class="px-4 py-2 bg-white rounded-lg shadow text-sm font-medium">💰 OVO</span>
                <span class="px-4 py-2 bg-white rounded-lg shadow text-sm font-medium">💵 DANA</span>
                <span class="px-4 py-2 bg-white rounded-lg shadow text-sm font-medium">🏦 Bank Transfer</span>
                <span class="px-4 py-2 bg-white rounded-lg shadow text-sm font-medium">🏪 Alfamart</span>
            </div>
            <p class="text-gray-600">
                🔒 Pembayaran aman dan terenkripsi melalui Midtrans<br>
                ✓ Batalkan kapan saja • ✓ Garansi uang kembali 7 hari
            </p>
        </div>

    </div>
</x-app-layout>