<header
    x-data="{ openAnnouncement: true }"
    x-cloak
    class="fixed top-0 left-0 right-0 z-50 px-4 pb-2"
>
    {{-- Announcement bar --}}
    @isset($navbarEvent)
        <div
            class="hidden sm:block bg-forest text-white"
            id="announcement-bar"
            x-show="openAnnouncement"
            x-transition
        >
            <div class="max-w-6xl mx-auto flex items-center justify-between gap-4 px-4 py-1.5 text-xs">
                <div class="flex items-center gap-2 truncate">
                    <span class="text-[10px] font-bold tracking-widest px-2 py-0.5 rounded border border-white/40 shrink-0">
                        EVENT TERDEKAT
                    </span>
                    <span class="truncate">
                        {{ $navbarEvent->title }}
                        @if($navbarEvent->event_date)
                            · {{ $navbarEvent->event_date->translatedFormat('d F Y') }}
                        @endif
                    </span>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('events.index') }}" class="hover:underline">Lihat →</a>
                    <button
                        type="button"
                        class="text-white/70 hover:text-white"
                        onclick="document.getElementById('announcement-bar')?.remove();"
                        @click="openAnnouncement = false"
                        aria-label="Tutup pengumuman"
                    >
                        ✕
                    </button>
                </div>
            </div>
        </div>
    @endisset

    <nav
        id="main-nav"
        x-data="{ open: false, shadow: false }"
        @scroll.window="shadow = window.scrollY > 10"
        class="max-w-5xl mx-auto mt-3 bg-white rounded-full border border-gray-100 px-4 py-2.5 flex items-center justify-between gap-2 sm:gap-4 transition-shadow duration-300 shadow-md"
        :class="shadow ? 'shadow-lg border-gray-200' : 'border-gray-100'"
    >
    <div class="w-full flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
            <img
                src="{{ asset('images/logo.jpg') }}"
                alt="Peduli Lingkungan"
                class="w-8 h-8 md:w-9 md:h-9 rounded-full object-cover ring-1 ring-emerald-700/30"
            >
            <div class="leading-tight block">
                <p class="font-heading text-sm md:text-base font-semibold text-forest">
                    Peduli Lingkungan
                </p>
                <p class="text-[9px] md:text-[10px] uppercase tracking-[0.18em] text-slate-500">
                    Since 2025 · Purbalingga
                </p>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-1 text-[13px] font-medium text-slate-600 flex-1 justify-center">
            <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-full hover:bg-emerald-50 hover:text-emerald-700 transition whitespace-nowrap">Home</a>
            <a href="{{ route('home') }}#events" class="px-3 py-1.5 rounded-full hover:bg-emerald-50 hover:text-emerald-700 transition whitespace-nowrap">Event</a>
            <a href="{{ route('products.index') }}" class="px-3 py-1.5 rounded-full hover:bg-emerald-50 hover:text-emerald-700 transition whitespace-nowrap">Produk</a>
            <a href="{{ route('forum.index') }}" class="px-3 py-1.5 rounded-full hover:bg-emerald-50 hover:text-emerald-700 transition whitespace-nowrap">Forum</a>

            <div
                class="relative"
                x-data="{ open: false, closeTimer: null }"
                @mouseenter="clearTimeout(closeTimer); open = true"
                @mouseleave="closeTimer = setTimeout(() => open = false, 300)"
            >
                <button
                    type="button"
                    @click="open = !open"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full hover:bg-emerald-50 hover:text-emerald-700 transition whitespace-nowrap"
                    :class="open ? 'bg-emerald-50 text-emerald-700' : ''"
                >
                    Lainnya
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div
                    x-show="open"
                    x-cloak
                    @mouseenter="clearTimeout(closeTimer)"
                    @mouseleave="closeTimer = setTimeout(() => open = false, 300)"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute left-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 z-50"
                >
                    <a href="{{ route('home') }}#about" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <x-icons name="leaf" class="w-4 h-4 text-emerald-600" />
                        Tentang Kami
                    </a>
                    <a href="{{ route('home') }}#why-join" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <x-icons name="star" class="w-4 h-4 text-emerald-600" />
                        Kenapa Join?
                    </a>
                    <a href="{{ route('articles.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <x-icons name="document-text" class="w-4 h-4 text-emerald-600" />
                        Artikel
                    </a>
                    <a href="{{ route('galleries.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <x-icons name="photo" class="w-4 h-4 text-emerald-600" />
                        Galeri
                    </a>
                </div>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-2 shrink-0">
            @guest
                <a href="{{ route('login') }}"
                   class="text-xs font-semibold text-slate-600 hover:text-emerald-700 px-3 py-2 rounded-full hover:bg-emerald-50 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="text-xs font-semibold text-white bg-slate-700 hover:bg-slate-800 px-3 py-2 rounded-full transition">
                    Daftar
                </a>
                <div class="w-px h-5 bg-gray-200 mx-1" aria-hidden="true"></div>
            @endguest
            @auth
                <div
                    class="relative"
                    x-data="{ open: false, closeTimer: null }"
                    @mouseenter="clearTimeout(closeTimer); open = true"
                    @mouseleave="closeTimer = setTimeout(() => open = false, 300)"
                >
                    <button
                        type="button"
                        @click="open = !open"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-slate-200 bg-white text-xs text-slate-700 hover:border-slate-300 transition-colors"
                    >
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="max-w-[90px] truncate">{{ auth()->user()->name }}</span>
                    </button>
                    <div
                        x-show="open"
                        x-cloak
                        @mouseenter="clearTimeout(closeTimer)"
                        @mouseleave="closeTimer = setTimeout(() => open = false, 300)"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 z-50"
                    >
                        <a href="{{ route('profile') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-emerald-50">Profil</a>
                        <a href="{{ route('orders.index') }}" class="block px-4 py-2.5 text-slate-700 hover:bg-emerald-50 ">
                            Pesanan Saya
                        </a>
                        <a href="{{ route('profile', ['tab' => 'posts']) }}" class="block px-4 py-2.5 text-slate-700 hover:bg-emerald-50">Post Saya</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 hover:bg-emerald-50 text-red-600">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="w-px h-5 bg-gray-200 mx-1" aria-hidden="true"></div>
            @endauth
            <a
                href="{{ setting('wa_group_link', 'https://chat.whatsapp.com/Lo7XaVcbPi68DXbW212FX4') }}"
                target="_blank"
                class="inline-flex items-center gap-2 rounded-full bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 shadow-sm hover:bg-emerald-800 transition-colors"
            >
                <x-icons name="whatsapp" class="w-4 h-4" />
                Join Sekarang
            </a>
        </div>

        <button
            type="button"
            class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 text-slate-700 bg-white/90 relative z-50"
            @click="open = !open"
        >
            <span x-show="!open"><x-icons name="bars-3" class="w-5 h-5" /></span>
            <span x-show="open" x-cloak><x-icons name="x-mark" class="w-5 h-5" /></span>
        </button>
    </div>

    {{-- Mobile overlay --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden"
        @click="open = false"
        x-cloak
    ></div>

    {{-- Mobile drawer --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="fixed top-0 right-0 h-full w-72 bg-white z-50 md:hidden flex flex-col shadow-2xl"
        x-cloak
    >
        {{-- Drawer header --}}
        <div class="bg-forest text-white px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img
                    src="{{ asset('images/logo.jpg') }}"
                    alt="Peduli Lingkungan"
                    class="w-9 h-9 rounded-full object-cover ring-1 ring-emerald-400/50 bg-white/10"
                >
                <div class="leading-tight">
                    <p class="font-heading text-sm font-semibold">Peduli Lingkungan</p>
                    <p class="text-[10px] uppercase tracking-[0.18em] text-spring/80">
                        Since 2025 · Purbalingga
                    </p>
                </div>
            </div>
            <button
                type="button"
                class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20"
                @click="open = false"
            >
                <x-icons name="x-mark" class="w-4 h-4" />
            </button>
        </div>

        {{-- Drawer menu --}}
        <nav class="flex-1 overflow-y-auto text-sm text-slate-700">
            <a
                href="{{ route('home') }}"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors {{ request()->is('/') ? 'bg-emerald-50 text-emerald-700' : 'hover:bg-emerald-50 hover:text-emerald-700' }}"
                @click="open = false"
            >
                <x-icons name="squares-2x2" class="w-4 h-4" />
                <span>Home</span>
            </a>
            <a
                href="{{ route('home') }}#about"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                @click="open = false"
            >
                <x-icons name="leaf" class="w-4 h-4" />
                <span>Tentang Kami</span>
            </a>
            <a
                href="{{ route('home') }}#events"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                @click="open = false"
            >
                <x-icons name="calendar-days" class="w-4 h-4" />
                <span>Event</span>
            </a>
            <a
                href="{{ route('home') }}#why-join"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                @click="open = false"
            >
                <x-icons name="star" class="w-4 h-4" />
                <span>Kenapa Join?</span>
            </a>
            <a
                href="{{ route('home') }}#articles"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                @click="open = false"
            >
                <x-icons name="document-text" class="w-4 h-4" />
                <span>Artikel</span>
            </a>
            <a
                href="{{ route('home') }}#gallery"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                @click="open = false"
            >
                <x-icons name="photo" class="w-4 h-4" />
                <span>Galeri</span>
            </a>
            <a
                href="{{ request()->is('/') ? route('home') . '#products' : route('products.index') }}"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                @click="open = false"
            >
                <x-icons name="shopping-bag" class="w-4 h-4" />
                <span>Produk</span>
            </a>
            <a
                href="{{ route('forum.index') }}"
                class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                @click="open = false"
            >
                <x-icons name="chat-bubble-left-right" class="w-4 h-4" />
                <span>Forum</span>
            </a>
            @auth
                <a
                    href="{{ route('orders.index') }}"
                    class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 transition-colors hover:bg-emerald-50 hover:text-emerald-700"
                    @click="open = false"
                >
                    <span class="text-base">🛍️</span>
                    <span>Pesanan Saya</span>
                </a>
            @endauth
        </nav>

        {{-- Drawer footer --}}
        <div class="px-5 pb-5 pt-3 border-t border-gray-100">
            @guest
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center justify-center rounded-full border border-gray-200 text-slate-700 font-semibold text-sm py-2.5 hover:bg-emerald-50 hover:text-emerald-700 transition"
                        @click="open = false"
                    >
                        Masuk
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center justify-center rounded-full bg-slate-800 text-white font-semibold text-sm py-2.5 hover:bg-slate-900 transition"
                        @click="open = false"
                    >
                        Daftar
                    </a>
                </div>
            @endguest

            @auth
                <div class="mb-4 rounded-2xl border border-gray-100 bg-gray-50 p-3">
                    <div class="flex items-center gap-3">
                        <img
                            src="{{ auth()->user()->avatar_url }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-10 h-10 rounded-full object-cover"
                        >
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-forest truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <a
                            href="{{ route('profile') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white text-slate-700 text-xs font-semibold py-2 hover:bg-emerald-50 hover:text-emerald-700 transition"
                            @click="open = false"
                        >
                            Profil
                        </a>
                        <a
                            href="{{ route('orders.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white text-slate-700 text-xs font-semibold py-2 hover:bg-emerald-50 hover:text-emerald-700 transition"
                            @click="open = false"
                        >
                            Pesanan
                        </a>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center rounded-xl bg-rose-600 text-white text-xs font-semibold py-2 hover:bg-rose-700 transition"
                        >
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth

            <a
                href="{{ setting('wa_group_link', 'https://chat.whatsapp.com/Lo7XaVcbPi68DXbW212FX4') }}"
                target="_blank"
                class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-emerald-700 text-white text-sm font-semibold py-2.5 shadow-sm shadow-emerald-900/20 hover:bg-emerald-800 transition-colors"
                @click="open = false"
            >
                <x-icons name="whatsapp" class="w-5 h-5" />
                Gabung via WhatsApp
            </a>
            <p class="mt-2 text-[11px] text-slate-400 text-center">
                Since 2025 · Purbalingga
            </p>
        </div>
    </div>
</nav>
</header>

