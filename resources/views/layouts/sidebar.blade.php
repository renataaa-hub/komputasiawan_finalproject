<div class="w-64 h-screen fixed bg-white border-r flex flex-col py-6">

    <!-- Logo -->
    <div class="px-6 mb-6">
        <h1 class="text-2xl font-semibold text-blue-600">penaAwan</h1>
    </div>

    <!-- Menu -->
    <ul class="flex-1 space-y-2 px-4">

        <!-- Dashboard -->
        <li>
            <a href="/dashboard"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100
               {{ request()->is('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7m-9 5v6m0 0h4m-4 0H7" />
                </svg>
                Home
            </a>
        </li>

        <!-- Karya -->
        <li>
            <a href="/karya"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100
               {{ request()->is('karya*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 20l9-5-9-5-9 5 9 5z" />
                </svg>
                Karya
            </a>
        </li>

        <!-- Statistik -->
        <li>
            <a href="/statistik"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100
               {{ request()->is('statistik') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Statistik
            </a>
        </li>

        <!-- Monetisasi -->
        <li>
            <a href="/monetisasi"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100
               {{ request()->is('monetisasi') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-2.21 0-4 1.79-4 4m8 0c0-2.21-1.79-4-4-4m0 8c2.21 0 4-1.79 4-4m-8 0c0 2.21 1.79 4 4 4" />
                </svg>
                Monetisasi
            </a>
        </li>


        <li>
            <a href="/subscription"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100
               {{ request()->is('subscription') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A3 3 0 017 17h10a3 3 0 011.879.804M12 3a3 3 0 110 6 3 3 0 010-6z" />
                </svg>
                Subscription
            </a>
        </li>

        <!-- Notifikasi -->
        <li>
            <a href="/notification"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100
               {{ request()->is('notification') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.33-4.992" />
                </svg>
                Notification
            </a>
        </li>

    </ul>

</div>
