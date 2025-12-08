<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>penaAwan — Cloud Writing Platform</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #f8fbff, #eef4ff);
            color: #2b2f3c;
        }

        .gradient-text {
            background: linear-gradient(90deg, #4a7aff, #6fa9ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass {
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.55);
        }
    </style>
</head>

<body class="antialiased">

    {{-- NAVBAR --}}
    <nav class="w-full fixed top-0 left-0 z-50 glass border-b border-white/20">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-6 py-4">
            <h1 class="text-2xl font-semibold gradient-text">penaAwan</h1>
            <div class="flex items-center gap-4">
                <a href="/login" class="text-sm font-medium hover:text-blue-500 transition">Login</a>
                <a href="/register"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="pt-32 pb-20">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center gap-16">

            {{-- Text --}}
            <div class="flex-1">
                <h2 class="text-5xl font-bold mb-6 leading-tight">
                    Tulis Tanpa Batas <br>
                    di <span class="gradient-text">penaAwan</span>
                </h2>

                <p class="text-lg text-gray-600 max-w-lg">
                    Platform menulis berbasis cloud untuk buku, novel, artikel, dan cerita pendek.
                    Simpan otomatis, kolaborasi, dan bagikan karya Anda kepada dunia.
                </p>

                <div class="mt-8 flex gap-4">
                    <a href="/register"
                        class="px-6 py-3 bg-blue-600 text-white text-lg rounded-xl shadow hover:bg-blue-700 transition">
                        Mulai Menulis
                    </a>

                    <a href="/login"
                        class="px-6 py-3 border border-blue-500 text-blue-600 text-lg rounded-xl hover:bg-blue-50 transition">
                        Masuk
                    </a>
                </div>
            </div>

            {{-- Illustration --}}
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-200/40 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 -left-10 w-56 h-56 bg-blue-300/30 rounded-full blur-3xl"></div>

                    <div class="bg-white rounded-3xl shadow-xl p-10 border border-gray-100 relative">
                        <h3 class="text-xl font-semibold mb-3">Editor Awan</h3>
                        <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                            Mulai menulis tanpa batas. Semua tulisanmu akan tersimpan otomatis di cloud
                            dan bisa kamu lanjutkan kapan pun, di perangkat mana pun.
                        </p>

                        <div class="flex items-center gap-3 bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <div
                                class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl font-bold">
                                ✍️
                            </div>
                            <p class="text-sm text-blue-700 font-medium">
                                Ayo mulai perjalanan menulismu. Satu paragraf hari ini, seribu karya besok.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- FEATURES --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">

            <h2 class="text-3xl font-bold text-center mb-14">Fitur Unggulan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <div class="p-7 bg-gray-50 rounded-xl border hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-3">Autosave Cloud</h3>
                    <p class="text-gray-600 text-sm">Semua tulisan Anda tersimpan otomatis setiap detik. Tidak ada lagi
                        kehilangan ide.</p>
                </div>

                <div class="p-7 bg-gray-50 rounded-xl border hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-3">Kolaborasi Real-time</h3>
                    <p class="text-gray-600 text-sm">Undang teman untuk menulis bersama dalam satu proyek.</p>
                </div>

                <div class="p-7 bg-gray-50 rounded-xl border hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-3">Subscription Premium</h3>
                    <p class="text-gray-600 text-sm">Akses fitur statistik karya, export PDF/DOC, dan monetisasi.</p>
                </div>

            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-10 text-center text-gray-600 text-sm">
        © {{ date('Y') }} penaAwan — Platform Menulis Cloud Indonesia
    </footer>

</body>
</html>
