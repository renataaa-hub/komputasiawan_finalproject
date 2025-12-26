<aside class="w-64 bg-white border-r min-h-screen p-4">
    <h2 class="text-xl font-bold mb-6">penaAwan Admin</h2>

    @php
        use App\Models\User;
        $totalUsers = User::count();
    @endphp

    <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="block px-3 py-2 rounded hover:bg-gray-100">
            📊 Dashboard
        </a>

        <!-- Users -->
        <a href="{{ route('admin.users') }}"
           class="flex justify-between items-center px-3 py-2 rounded hover:bg-gray-100">
            <span>👥 Users</span>
            <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">
                {{ $totalUsers }}
            </span>
        </a>
    </nav>
</aside>
