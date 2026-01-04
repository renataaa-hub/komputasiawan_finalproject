<x-guest-layout>

    <div class="max-w-md mx-auto bg-white/80 backdrop-blur-xl shadow-xl rounded-3xl p-10 border border-gray-100">

        <div class="text-center mb-8">
            <a href="/" class="block mx-auto w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-3xl hover:bg-blue-200 transition duration-200 cursor-pointer">
                ☁️
            </a>

            <h2 class="mt-4 text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
            <p class="text-gray-500 text-sm mt-1">
                Mulai perjalanan menulismu di penaAwan ✍️
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" class="block mt-1 w-full rounded-xl" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full rounded-xl" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full rounded-xl" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-blue-600 hover:text-blue-800 transition font-medium" href="{{ route('login') }}">
                    Sudah punya akun?
                </a>

                <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-6 py-2.5 rounded-xl">
                    {{ __('Daftar') }}
                </x-primary-button>
            </div>
        </form>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300/50"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white/50 backdrop-blur-sm text-gray-500 rounded-lg">Atau daftar dengan</span>
                </div>
            </div>

            <a href="{{ route('auth.google') }}" 
               class="flex items-center justify-center w-full mt-4 px-4 py-2.5 border border-gray-200 rounded-xl shadow-sm bg-white hover:bg-gray-50 transition duration-200 gap-3 group">
                
                <svg class="w-5 h-5" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(1, 0, 0, 1, 27.009001, -39.23856)">
                        <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                        <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.424 63.239 -14.754 63.239 Z"/>
                        <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.769 -21.864 51.959 -21.864 51.129 C -21.864 50.299 -21.734 49.489 -21.484 48.729 L -21.484 45.639 L -25.464 45.639 C -26.284 47.269 -26.754 49.129 -26.754 51.129 C -26.754 53.129 -26.284 54.989 -25.464 56.619 L -21.484 53.529 Z"/>
                        <path fill="#EA4335" d="M -14.754 43.769 C -12.984 43.769 -11.404 44.379 -10.154 45.579 L -6.734 42.159 C -8.804 40.229 -11.514 39.009 -14.754 39.009 C -19.424 39.009 -23.494 41.709 -25.464 45.639 L -21.484 48.729 C -20.534 45.879 -17.884 43.769 -14.754 43.769 Z"/>
                    </g>
                </svg>
                <span class="text-gray-700 font-medium text-sm group-hover:text-gray-900">Google</span>
            </a>
        </div>
        </div>

</x-guest-layout>