@extends('layouts.app_public')

@section('title', 'Forum Diskusi · Peduli Lingkungan')

@section('content')
    <section class="bg-cream py-10 sm:py-16 rv">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
                <div>
                    <span class="section-eyebrow text-leaf">Forum · Diskusi & QnA</span>
                    <h1 class="font-heading text-3xl md:text-4xl text-forest mt-2">
                        Ruang Diskusi Pemuda<br>
                        <span class="italic text-leaf">Peduli Lingkungan</span>
                    </h1>
                    <p class="text-xs md:text-sm text-moss/80 mt-2 max-w-xl">
                        Bagikan keresahan, tanya jawab, dan ide aksi hijau bersama komunitas. Saling belajar, saling menguatkan.
                    </p>
                </div>

                <div class="flex flex-col sm:items-end gap-3">
                    @auth
                        <a href="{{ route('forum.create') }}" class="btn-primary text-sm px-5 py-2.5">
                            Buat Post Baru
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-emerald-700/60 text-emerald-800 text-xs font-semibold px-4 py-2.5 hover:bg-emerald-50 transition">
                            Masuk untuk Berdiskusi
                        </a>
                    @endauth
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 p-4 sm:p-5 mb-6 shadow-sm">
                <form method="GET" action="{{ route('forum.index') }}" class="flex flex-col md:flex-row gap-3 md:items-center">
                    <div class="flex-1">
                        <label class="sr-only" for="q">Cari</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <x-icons name="magnifying-glass" class="w-4 h-4" />
                            </span>
                            <input
                                id="q"
                                type="text"
                                name="q"
                                value="{{ $search ?? '' }}"
                                placeholder="Cari diskusi, pertanyaan, atau laporan..."
                                class="w-full pl-9 pr-3 py-2.5 rounded-full border border-gray-200 text-sm focus:ring-leaf focus:border-leaf"
                            >
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $categories = ['Laporan', 'Diskusi', 'Pertanyaan'];
                        @endphp
                        <button type="submit" class="hidden md:inline-flex btn-admin text-xs px-4 py-2">
                            Filter
                        </button>
                    </div>
                </form>

                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    <a href="{{ route('forum.index') }}" class="px-3 py-1 rounded-full border {{ empty($category) ? 'border-leaf bg-leaf/10 text-forest' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a
                            href="{{ route('forum.index', ['category' => $cat]) }}"
                            class="px-3 py-1 rounded-full border {{ ($category ?? null) === $cat ? 'border-leaf bg-leaf/10 text-forest' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                        >
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if($posts->isEmpty())
                <p class="text-sm text-moss/80">
                    Belum ada diskusi di forum. Jadilah yang pertama memulai percakapan!
                </p>
            @else
                <div class="space-y-4">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-3xl border border-gray-100 p-4 sm:p-5 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start gap-3 mb-3">
                                <img
                                    src="{{ $post->user->avatar_url }}"
                                    alt="{{ $post->user->name }}"
                                    class="w-9 h-9 rounded-full object-cover ring-2 ring-leaf/40"
                                >
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 text-[11px] text-moss/70">
                                        <span class="font-semibold">{{ $post->user->name }}</span>
                                        <span>·</span>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    <a href="{{ route('forum.show', $post) }}" class="block mt-1">
                                        <h2 class="font-heading text-base md:text-lg text-forest line-clamp-2 hover:text-leaf transition">
                                            {{ $post->title }}
                                        </h2>
                                    </a>
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-moss/80 line-clamp-3">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->body), 220) }}
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px]">
                                        @if($post->category)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-leaf/10 text-forest border border-leaf/40">
                                                {{ $post->category }}
                                            </span>
                                        @endif
                                        @if($post->location)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-100">
                                                <x-icons name="map-pin" class="w-3.5 h-3.5" />
                                                {{ $post->location }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($post->image_url)
                                    <a href="{{ route('forum.show', $post) }}" class="block w-full md:w-40 shrink-0">
                                        <img
                                            src="{{ $post->image_url }}"
                                            alt="{{ $post->title }}"
                                            class="w-full h-28 md:h-24 rounded-2xl object-cover"
                                        >
                                    </a>
                                @endif
                            </div>

                            <div class="mt-4 flex items-center justify-between text-[11px] text-moss/70">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-icons name="chat-bubble-left-right" class="w-3.5 h-3.5" />
                                        {{ $post->replies()->count() }} balasan
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-icons name="heart" class="w-3.5 h-3.5" />
                                        {{ $post->likes()->count() }} suka
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-icons name="eye" class="w-3.5 h-3.5" />
                                        {{ $post->views }} lihat
                                    </span>
                                </div>
                                <a href="{{ route('forum.show', $post) }}" class="inline-flex items-center gap-1 text-leaf font-semibold">
                                    Lihat Detail
                                    <x-icons name="arrow-right" class="w-3.5 h-3.5" />
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection

