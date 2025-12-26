<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">@yield('title')</h1>

            <!-- ADMIN PROFILE DROPDOWN -->
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="flex items-center gap-3 focus:outline-none">

                    <span class="text-sm font-medium">
                        {{ auth()->user()->name }}
                    </span>

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
                        class="w-9 h-9 rounded-full border"
                    >
                </button>

                <!-- Dropdown -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border z-50">
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>
