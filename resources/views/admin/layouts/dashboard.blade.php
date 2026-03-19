<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }" x-cloak>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin · Peduli Lingkungan')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Flatpickr & TinyMCE via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @stack('head')
</head>
<body class="bg-cream font-body antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 w-64 bg-forest text-spring flex flex-col z-40 transform transition-transform duration-300 md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        >
            <div class="h-16 flex items-center px-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-lime flex items-center justify-center text-forest rotate-[-15deg]">
                        <x-icons name="leaf" class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="font-heading text-sm text-white">Peduli Lingkungan</p>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-spring/70">Admin Dashboard</p>
                    </div>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 text-sm">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="squares-2x2" class="w-5 h-5" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banners.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/banners*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="photo" class="w-5 h-5" />
                            <span>Banner</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/events*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="calendar-days" class="w-5 h-5" />
                            <span>Event</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.galleries.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/galleries*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="rectangle-group" class="w-5 h-5" />
                            <span>Galeri</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.articles.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/articles*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="document-text" class="w-5 h-5" />
                            <span>Artikel</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.about.edit') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/about*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="leaf" class="w-5 h-5" />
                            <span>Tentang Kami</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.testimonials.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/testimonials*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="chat-bubble-left-right" class="w-5 h-5" />
                            <span>Testimonial</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/products*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="shopping-bag" class="w-5 h-5" />
                            <span>Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}"
                           class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/orders*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <span class="flex items-center gap-2">
                                <x-icons name="chat-bubble-left-right" class="w-5 h-5" />
                                <span>Pesanan</span>
                            </span>
                            @if(($pendingOrdersCount ?? 0) > 0)
                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-red-600 text-white text-[11px] font-semibold">
                                    {{ $pendingOrdersCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/users*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="user-group" class="w-5 h-5" />
                            <span>Kelola User</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->is('admin/settings*') ? 'bg-lime/30 text-white' : 'hover:bg-white/5' }}">
                            <x-icons name="cog-6-tooth" class="w-5 h-5" />
                            <span>Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 md:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" class="md:hidden p-2 rounded-full border border-gray-200" @click="sidebarOpen = !sidebarOpen">
                        <x-icons name="bars-3" class="w-5 h-5" />
                    </button>
                    <h1 class="text-sm md:text-base font-semibold text-forest">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-600">
                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="relative inline-flex items-center justify-center w-9 h-9 rounded-full border border-gray-200 hover:bg-gray-50"
                        title="Pesanan"
                    >
                        <x-icons name="bell" class="w-5 h-5 text-gray-700" />
                        @if(($pendingOrdersCount ?? 0) > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-600 text-white text-[10px] font-semibold flex items-center justify-center">
                                {{ $pendingOrdersCount }}
                            </span>
                        @endif
                    </a>
                    <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full border border-gray-200 text-[11px] hover:bg-gray-50">
                            <x-icons name="arrow-right-on-rectangle" class="w-4 h-4" />
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 bg-cream/60">
                <div class="max-w-6xl mx-auto px-4 py-6 md:py-8">
                    @if(session('success'))
                        <x-admin.flash-message type="success" class="mb-4">{{ session('success') }}</x-admin.flash-message>
                    @endif
                    @if(session('error'))
                        <x-admin.flash-message type="error" class="mb-4">{{ session('error') }}</x-admin.flash-message>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <x-admin.confirm-modal />

    @stack('scripts')

    <a
        href="{{ route('home') }}"
        target="_blank"
        class="fixed bottom-6 right-6 z-50 inline-flex items-center gap-2 bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-full shadow-lg hover:bg-emerald-800 transition"
    >
        <x-icons name="globe-alt" class="w-4 h-4" />
        View Home Page
    </a>
</body>
</html>

