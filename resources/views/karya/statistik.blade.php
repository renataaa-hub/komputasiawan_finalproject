<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Statistik Karya</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">
<!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="#" class="text-2xl font-bold text-blue-600">penaAwan</a>
                </div>
                <div class="flex items-center gap-3">

                    <!-- Profile Dropdown Desktop -->
                    <div class="relative">
                        <button id="navProfileBtn" class="flex items-center gap-2 hover:bg-gray-100 rounded-lg p-2 transition">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" 
                                    alt="{{ Auth::user()->name }}" 
                                    class="w-8 h-8 rounded-full">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff" 
                                    alt="{{ Auth::user()->name }}" 
                                    class="w-8 h-8 rounded-full">
                            @endif
                            <span class="hidden md:block text-gray-700 font-medium">
                                {{ Auth::user()->name }}
                            </span>

                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="navProfileDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Edit Profile</a>
                            <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="flex h-screen">

        <!-- SIDEBAR -->
        <aside class="w-20 bg-white border-r border-gray-200 flex flex-col items-center py-6">
            
            <div class="space-y-8">

                <!-- Dashboard -->
                <a href="/dashboard" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-xs mt-1">Home</span>
                </a>

                <!-- Karya -->
                <a href="/karya" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-xs mt-1">Karya</span>
                </a>

                <!-- Statistik (Active) -->
                <a href="/statistik" class="flex flex-col items-center text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-xs mt-1">Statistik</span>
                </a>

                <!-- Monetisasi -->
                <a href="/monetisasi" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs mt-1">Monetisasi</span>
                </a>

                <!-- Subscriber -->
                <a href="/subscriber" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span class="text-xs mt-1">Subscriber</span>
                </a>

                <!-- Notifikasi -->
                <a href="/notifikasi" class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="text-xs mt-1">Notifikasi</span>
                </a>

            </div>

            <!-- Setting -->
            <div class="mt-auto pb-4">
                <button class="flex flex-col items-center text-gray-400 hover:text-blue-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    <span class="text-xs mt-1">Setting</span>
                </button>
            </div>

        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8 overflow-y-auto">

            <h1 class="text-3xl font-bold mb-1">Statistik Karya</h1>
            <p class="text-gray-600 mb-6">Pantau performa karya kamu secara detail.</p>

            <!-- CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-2xl shadow flex items-center gap-4">
                    <div class="text-blue-600 text-3xl">👁️</div>
                    <div>
                        <p class="text-sm text-gray-600">Total Views</p>
                        <p class="text-xl font-semibold">12.4K</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow flex items-center gap-4">
                    <div class="text-pink-600 text-3xl">❤️</div>
                    <div>
                        <p class="text-sm text-gray-600">Likes</p>
                        <p class="text-xl font-semibold">3.9K</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow flex items-center gap-4">
                    <div class="text-green-600 text-3xl">💬</div>
                    <div>
                        <p class="text-sm text-gray-600">Komentar</p>
                        <p class="text-xl font-semibold">812</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow flex items-center gap-4">
                    <div class="text-purple-600 text-3xl">🔄</div>
                    <div>
                        <p class="text-sm text-gray-600">Shares</p>
                        <p class="text-xl font-semibold">421</p>
                    </div>
                </div>
            </div>

            <!-- GRAFIK -->
            <div class="bg-white p-6 rounded-2xl shadow mb-6">
                <div class="flex justify-between mb-4">
                    <h2 class="text-lg font-semibold flex items-center gap-2">📊 Statistik 30 Hari Terakhir</h2>
                    <select class="border rounded-lg p-2 text-sm">
                        <option>30 Hari</option>
                        <option>7 Hari</option>
                        <option>1 Tahun</option>
                    </select>
                </div>
                <div class="w-full h-64 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500">
                    Grafik Placeholder (Views, Likes, Komentar, Share)
                </div>
            </div>

            <!-- TABEL PER KARYA -->
            <div class="bg-white p-6 rounded-2xl shadow mb-10">
                <h2 class="text-lg font-semibold mb-4">Performa Setiap Karya</h2>

                <div class="space-y-4">
                    <div class="p-4 border rounded-xl flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <p class="font-semibold">Petualangan di Negeri Awan</p>
                            <p class="text-sm text-gray-500">Fantasi • Dipublikasikan</p>
                        </div>
                        <div class="flex gap-8 text-sm text-gray-700">
                            <div class="flex items-center gap-1">👁️ 2.4K</div>
                            <div class="flex items-center gap-1">❤️ 156</div>
                            <div class="flex items-center gap-1">💬 45</div>
                            <div class="flex items-center gap-1">🔄 12</div>
                        </div>
                    </div>

                    <div class="p-4 border rounded-xl flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <p class="font-semibold">Misteri Rumah Tua</p>
                            <p class="text-sm text-gray-500">Thriller • Dipublikasikan</p>
                        </div>
                        <div class="flex gap-8 text-sm text-gray-700">
                            <div class="flex items-center gap-1">👁️ 1.8K</div>
                            <div class="flex items-center gap-1">❤️ 98</div>
                            <div class="flex items-center gap-1">💬 30</div>
                            <div class="flex items-center gap-1">🔄 9</div>
                        </div>
                    </div>

                    <div class="p-4 border rounded-xl flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <p class="font-semibold">Jejak Sang Penakluk</p>
                            <p class="text-sm text-gray-500">Aksi • Dipublikasikan</p>
                        </div>
                        <div class="flex gap-8 text-sm text-gray-700">
                            <div class="flex items-center gap-1">👁️ 1.5K</div>
                            <div class="flex items-center gap-1">❤️ 87</div>
                            <div class="flex items-center gap-1">💬 22</div>
                            <div class="flex items-center gap-1">🔄 7</div>
                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>
    <script>
            // Navbar Profile Dropdown
        const navProfileBtn = document.getElementById('navProfileBtn');
        const navProfileDropdown = document.getElementById('navProfileDropdown');
        
        if (navProfileBtn && navProfileDropdown) {
            navProfileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                navProfileDropdown.classList.toggle('hidden');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
                profileDropdown.classList.add('hidden');
            }
            if (navProfileDropdown && !navProfileDropdown.classList.contains('hidden')) {
                navProfileDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
