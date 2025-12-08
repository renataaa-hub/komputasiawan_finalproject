<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PenaAwan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="#" class="text-2xl font-bold text-blue-600">penaAwan</a>
                    <div class="hidden md:flex gap-6">
                        <a href="#" class="text-blue-600 font-medium">Dashboard</a>
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition">Komunitas</a>
                    </div>
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

    <!-- Sidebar Navigation Desktop -->
    <div class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-20 bg-white shadow-lg z-40 hidden md:flex flex-col items-center py-6">
        <nav class="flex-1 flex flex-col gap-4">
            <a href="#" class="group relative flex flex-col items-center gap-1 p-3 rounded-xl bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-xs">Home</span>
            </a>

            <a href="#" class="group relative flex flex-col items-center gap-1 p-3 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-xs">Karya</span>
            </a>

            <a href="#" class="group relative flex flex-col items-center gap-1 p-3 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-xs">Statistik</span>
            </a>

            <a href="#" class="group relative flex flex-col items-center gap-1 p-3 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs">Monetisasi</span>
            </a>

            <a href="#" class="group relative flex flex-col items-center gap-1 p-3 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="text-xs">Subscriber</span>
            </a>

            <a href="#" class="group relative flex flex-col items-center gap-1 p-3 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="text-xs">Notifikasi</span>
            </a>
        </nav>

        <div class="flex flex-col gap-4">
            <a href="#" class="group relative flex flex-col items-center gap-1 p-3 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-blue-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-xs">Setting</span>
            </a>

            <div class="relative">
                
                <!-- Dropdown Menu -->
                <div id="profileDropdown" class="hidden absolute bottom-full left-full ml-2 mb-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                    <a href="#edit-profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Edit Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Delete Account</a>
                    <hr class="my-2">
                    <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition">Log Out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation Mobile -->
    <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg z-50 md:hidden">
        <nav class="flex justify-around items-center h-16 px-2">
            <a href="#" class="flex flex-col items-center gap-1 p-2 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-xs">Home</span>
            </a>

            <a href="#" class="flex flex-col items-center gap-1 p-2 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-xs">Karya</span>
            </a>

            <a href="#" class="flex flex-col items-center gap-1 p-2 -mt-8">
                <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </a>

            <a href="#" class="flex flex-col items-center gap-1 p-2 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="text-xs">Notif</span>
            </a>

            <a href="#" class="flex flex-col items-center gap-1 p-2 text-gray-600">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name=Renata&background=3b82f6&color=fff" alt="User" class="w-6 h-6 rounded-full">
                </div>
                <span class="text-xs">Profil</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="md:ml-20 pb-20 md:pb-6 pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
                <p class="text-gray-600">"Selamat datang kembali! Lanjutkan petualangan tulisanmu, semua idemu tersimpan aman di awan."</p>
            </div>

            <!-- Search Bar & Actions -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
                <div class="flex gap-4 items-center flex-col sm:flex-row">
                    <div class="flex-1 relative w-full">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Cari karya, draft, atau topik..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 w-full sm:w-auto justify-center transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Karya
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Total Karya</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">24</p>
                    <p class="text-xs text-green-600 mt-2">↑ 12% dari bulan lalu</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Total Pembaca</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">1.2K</p>
                    <p class="text-xs text-green-600 mt-2">↑ 23% dari bulan lalu</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Draft</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">8</p>
                    <p class="text-xs text-gray-600 mt-2">Perlu diselesaikan</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">Rp 450K</p>
                    <p class="text-xs text-green-600 mt-2">↑ 8% dari bulan lalu</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Karya Trending -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Karya Trending</h3>
                            <button class="text-blue-600 text-sm hover:text-blue-700 font-medium">Lihat Semua</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">1</div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 truncate">Petualangan di Negeri Awan</h4>
                                    <p class="text-sm text-gray-600">2.4K views • 156 likes</p>
                                </div>
                                <div class="text-green-600 text-sm font-medium whitespace-nowrap">↑ 45%</div>
                            </div>
                            <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center text-white font-bold">2</div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 truncate">Misteri Rumah Tua</h4>
                                    <p class="text-sm text-gray-600">1.8K views • 98 likes</p>
                                </div>
                                <div class="text-green-600 text-sm font-medium whitespace-nowrap">↑ 32%</div>
                            </div>
                            <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center text-white font-bold">3</div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 truncate">Jejak Sang Penakluk</h4>
                                    <p class="text-sm text-gray-600">1.5K views • 87 likes</p>
                                </div>
                                <div class="text-green-600 text-sm font-medium whitespace-nowrap">↑ 28%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Karya -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Karya Terbaru</h3>
                            <div class="flex gap-2">
                                <button class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg font-medium">Semua</button>
                                <button class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Publikasi</button>
                                <button class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Draft</button>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                <div class="flex justify-between items-start mb-2 gap-2">
                                    <h4 class="font-medium text-gray-900">Sang Penjaga Waktu</h4>
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full whitespace-nowrap">Publikasi</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">Cerita tentang seorang penjaga yang dapat mengendalikan waktu...</p>
                                <div class="flex items-center justify-between text-sm text-gray-500 flex-wrap gap-2">
                                    <span>12 Des 2024</span>
                                    <div class="flex gap-3">
                                        <span>👁️ 234</span>
                                        <span>❤️ 45</span>
                                        <span>💬 12</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                <div class="flex justify-between items-start mb-2 gap-2">
                                    <h4 class="font-medium text-gray-900">Legenda Naga Terakhir</h4>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full whitespace-nowrap">Draft</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">Di sebuah kerajaan kuno, tersembunyi seekor naga terakhir...</p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>10 Des 2024</span>
                                    <div class="flex gap-3">
                                        <button class="text-blue-600 hover:text-blue-700 font-medium">Edit</button>
                                        <button class="text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Aktivitas -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Aktivitas</h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Karya "Sang Penjaga Waktu" dipublikasikan</p>
                                    <p class="text-xs text-gray-500">2 jam yang lalu</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Draft "Legenda Naga" diperbarui</p>
                                    <p class="text-xs text-gray-500">5 jam yang lalu</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Mendapat 10 subscriber baru</p>
                                    <p class="text-xs text-gray-500">1 hari yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Statistik 7 Hari -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik 7 Hari Terakhir</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Views</span>
                                    <span class="font-medium">2,456</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: 75%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Likes</span>
                                    <span class="font-medium">342</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-pink-600 h-2 rounded-full transition-all" style="width: 60%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Komentar</span>
                                    <span class="font-medium">89</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full transition-all" style="width: 45%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Shares</span>
                                    <span class="font-medium">56</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full transition-all" style="width: 35%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monetisasi Karya -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Monetisasi Karya</h3>
                        <p class="text-blue-100 text-sm mb-4">Mulai hasilkan uang dari karyamu</p>
                        <div class="bg-white/20 backdrop-blur rounded-lg p-4 mb-4">
                            <div class="text-2xl font-bold mb-1">Rp 450.000</div>
                            <div class="text-sm text-blue-100">Pendapatan bulan ini</div>
                        </div>
                        <button class="w-full bg-white text-blue-600 py-2 rounded-lg font-medium hover:bg-blue-50 transition mb-2">
                            Tarik Saldo
                        </button>
                        <button class="w-full bg-white/10 backdrop-blur py-2 rounded-lg font-medium hover:bg-white/20 transition">
                            Lihat Detail
                        </button>
                    </div>

                    <!-- Aksi Cepat -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-3 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Buat Draft Baru</span>
                            </button>
                            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-3 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Balas Komentar</span>
                            </button>
                            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-3 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Lihat Analitik</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

        // Modal functionality
        const editProfileModal = document.getElementById('editProfileModal');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');

        // Open modal when clicking "Edit Profile" links
        document.querySelectorAll('a[href="#edit-profile"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                editProfileModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        // Close modal
        const closeModalFunc = () => {
            editProfileModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        if (closeModal) closeModal.addEventListener('click', closeModalFunc);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModalFunc);

        // Close modal when clicking outside
        editProfileModal.addEventListener('click', (e) => {
            if (e.target === editProfileModal) {
                closeModalFunc();
            }
        });

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !editProfileModal.classList.contains('hidden')) {
                closeModalFunc();
            }
        });
    </script>
</body>
</html>