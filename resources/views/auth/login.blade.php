<x-guest-layout>

    <!-- Header Cloud + Title -->
    <div class="text-center mb-8">
        <a href="/" class="block mx-auto w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl 
                         flex items-center justify-center text-3xl hover:bg-blue-200 
                         transition duration-200 cursor-pointer">
            ☁️
        </a>

        <h2 class="mt-4 text-2xl font-bold text-gray-800">Masuk ke Akun</h2>
        <p class="text-gray-500 text-sm mt-1">
            Lanjutkan menulis dan jelajahi karya di penaAwan ✍️
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-xl"
                type="email" name="email"
                :value="old('email')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                class="block mt-1 w-full rounded-xl"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- OR LINE -->
        <div class="flex items-center my-6">
            <div class="flex-grow border-t"></div>
            <span class="mx-3 text-sm text-gray-400">atau</span>
            <div class="flex-grow border-t"></div>
        </div>

        <!-- LOGIN DENGAN GOOGLE -->
        <div class="mt-6">
            <a href="{{ route('google.login') }}"
               class="w-full flex items-center justify-center gap-3 py-2 px-4 border border-gray-300 rounded-xl
                      hover:bg-gray-100 transition font-medium">
                <img src="https://developers.google.com/identity/images/g-logo.png"
                     class="w-5 h-5">
                Masuk dengan Google
            </a>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    name="remember">
                <span class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-6">

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800 transition"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <!-- Modified Login Button -->
            <button type="submit"
                class="ms-3 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
                {{ __('Log in') }}
            </button>
        </div>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Daftar sekarang
            </a>
        </p>

    </form>

</x-guest-layout>
