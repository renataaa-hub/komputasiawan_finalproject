<header class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <h1 class="font-semibold text-lg">
        @yield('title')
    </h1>

    <span class="text-sm text-gray-600">
        {{ auth()->user()->name }}
    </span>
</header>
