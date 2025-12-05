<x-guest-layout>

    <div class="max-w-md mx-auto bg-white/80 backdrop-blur-xl shadow-xl rounded-3xl p-10 border border-gray-100">

        <div class="text-center mb-8">
            <a href="/"
                class="block mx-auto w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl 
                     flex items-center justify-center text-3xl hover:bg-blue-200 
                     transition duration-200 cursor-pointer">
                ☁️
            </a>

            <h2 class="mt-4 text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
            <p class="text-gray-500 text-sm mt-1">
                Mulai perjalanan menulismu di penaAwan ✍️
            </p>
        </div>


        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input id="name" class="block mt-1 w-full rounded-xl" type="text" name="name"
                    :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full rounded-xl" type="email" name="email"
                    :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full rounded-xl" type="password" name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Button -->
            <div class="flex items-center justify-between mt-6">

                <a class="text-sm text-blue-600 hover:text-blue-800 transition font-medium" href="{{ route('login') }}">
                    Sudah punya akun?
                </a>

                <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-6 py-2.5 rounded-xl">
                    {{ __('Daftar') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</x-guest-layout>
