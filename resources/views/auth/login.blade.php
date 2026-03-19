<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Peduli Lingkungan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-screen font-body antialiased">
    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- Left: Forest background + quote --}}
        <div class="hidden lg:flex lg:w-1/2 bg-forest relative overflow-hidden items-center justify-center p-12">
            <div class="absolute inset-0 bg-gradient-to-br from-forest via-moss/90 to-forest"></div>
            <span class="floating-leaf absolute left-[15%] top-[20%] text-lime/40" aria-hidden="true">
                <x-icons name="leaf" class="w-12 h-12" />
            </span>
            <span class="floating-leaf absolute right-[20%] top-[30%] text-spring/30" style="animation-delay: 1s" aria-hidden="true">
                <x-icons name="leaf" class="w-8 h-8 rotate-45" />
            </span>
            <span class="floating-leaf absolute left-[25%] bottom-[30%] text-leaf/30" style="animation-delay: 2s" aria-hidden="true">
                <x-icons name="leaf" class="w-10 h-10 -rotate-12" />
            </span>
            <div class="relative z-10 max-w-md text-center">
                <div class="mb-8">
                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="Logo Peduli Lingkungan"
                        class="w-20 h-20 mx-auto rounded-full object-cover ring-4 ring-white/20 shadow-xl"
                    >
                </div>
                <blockquote class="font-heading text-2xl md:text-3xl text-white leading-relaxed italic">
                    "Dari keresahan, jadi gerakan hijau. Bergabunglah dengan pemuda yang peduli bumi."
                </blockquote>
                <p class="mt-6 text-spring/80 text-sm">— Komunitas Peduli Lingkungan Purbalingga</p>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="flex-1 flex items-center justify-center p-6 sm:p-12 bg-cream">
            <div class="w-full max-w-md">

                {{-- Logo mobile --}}
                <div class="text-center mb-8 lg:hidden">
                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="Logo Peduli Lingkungan"
                        class="w-14 h-14 mx-auto rounded-full object-cover ring-2 ring-emerald-200 shadow mb-3"
                    >
                    <h1 class="font-heading text-xl text-forest">Peduli Lingkungan</h1>
                    <p class="text-sm text-moss/60">Komunitas Peduli Lingkungan</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <h2 class="font-heading text-2xl text-forest mb-1">Selamat Datang</h2>
                    <p class="text-sm text-moss/70 mb-6">Masuk ke akun kamu</p>

                    @if (session('status'))
                        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-800 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-800 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-moss/50">
                                    <x-icons name="user-circle" class="w-5 h-5" />
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-leaf focus:border-transparent"
                                    placeholder="email@kamu.com"
                                >
                            </div>
                        </div>

                        {{-- Password dengan toggle show/hide --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Password
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-moss/50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    :type="show ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-leaf focus:border-transparent"
                                    placeholder="••••••••"
                                >
                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-moss/50 hover:text-moss transition"
                                    :aria-label="show ? 'Sembunyikan sandi' : 'Tampilkan sandi'"
                                >
                                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Remember me --}}
                        <div class="flex items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-leaf focus:ring-leaf"
                            >
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                                Ingat saya
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-lime text-forest font-semibold hover:bg-leaf transition flex items-center justify-center gap-2"
                        >
                            <x-icons name="arrow-right-on-rectangle" class="w-5 h-5" />
                            Masuk
                        </button>

                        {{-- Link register --}}
                        <p class="text-center text-sm text-moss/70">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-emerald-700 font-semibold hover:underline">
                                Register di sini
                            </a>
                        </p>

                    </form>
                </div>

                <p class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-moss hover:text-forest">
                        <x-icons name="arrow-left" class="w-4 h-4" />
                        Kembali ke Beranda
                    </a>
                </p>

            </div>
        </div>
    </div>
</body>
</html>