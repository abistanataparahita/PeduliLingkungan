@extends('layouts.app_public')

@section('title', $post->title . ' · Forum Peduli Lingkungan')

@section('content')
    <section class="bg-cream py-10 sm:py-16 rv">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="mb-6">
                <a href="{{ route('forum.index') }}" class="inline-flex items-center gap-1 text-xs text-moss/70 hover:text-leaf">
                    <x-icons name="arrow-left" class="w-3.5 h-3.5" />
                    Kembali ke Forum
                </a>
            </div>

            <article class="bg-white rounded-3xl border border-gray-100 p-5 sm:p-7 shadow-sm mb-6">
                <div class="flex items-start gap-3 mb-4">
                    <img
                        src="{{ $post->user->avatar_url }}"
                        alt="{{ $post->user->name }}"
                        class="w-10 h-10 rounded-full object-cover ring-2 ring-leaf/40"
                    >
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 text-[11px] text-moss/70">
                            <span class="font-semibold">{{ $post->user->name }}</span>
                            <span>·</span>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        <h1 class="font-heading text-xl md:text-2xl text-forest mt-1">
                            {{ $post->title }}
                        </h1>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px]">
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
                            <span class="inline-flex items-center gap-1.5 text-moss/70">
                                <x-icons name="eye" class="w-3.5 h-3.5" />
                                {{ $post->views }} kali dilihat
                            </span>
                        </div>
                    </div>
                </div>

                @if($post->image_url)
                    <img
                        src="{{ $post->image_url }}"
                        alt="{{ $post->title }}"
                        class="w-full max-h-[360px] rounded-2xl object-cover mb-4"
                    >
                @endif

                <div class="prose prose-sm max-w-none text-moss/90">
                    {!! nl2br(e(strip_tags($post->body))) !!}
                </div>

                @auth
                    <form action="{{ route('forum.like', $post) }}" method="POST" class="mt-4">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $liked ? 'bg-leaf text-forest border-leaf' : 'border-gray-200 text-moss/80 hover:bg-emerald-50 hover:text-emerald-800' }}"
                        >
                            <x-icons name="heart" class="w-3.5 h-3.5" />
                            {{ $liked ? 'Batalkan Suka' : 'Suka' }}
                            <span class="ml-1 text-[10px] text-moss/60">{{ $post->likes()->count() }}</span>
                        </button>
                    </form>
                @else
                    <p class="mt-4 text-[11px] text-moss/70">
                        Masuk untuk menyukai diskusi ini.
                    </p>
                @endauth
            </article>

            {{-- Replies --}}
            <section class="bg-white rounded-3xl border border-gray-100 p-5 sm:p-7 shadow-sm">
                <h2 class="font-heading text-lg text-forest mb-4">
                    Balasan ({{ $post->replies()->count() }})
                </h2>

                @if($post->replies->isEmpty())
                    <p class="text-sm text-moss/70">
                        Belum ada balasan di diskusi ini. Jadilah yang pertama merespon.
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach($post->replies->whereNull('parent_id') as $reply)
                            @include('forum.partials.reply', ['reply' => $reply, 'post' => $post, 'level' => 0])
                        @endforeach
                    </div>
                @endif

                {{-- Reply form --}}
                <div class="mt-6 pt-5 border-t border-gray-100">
                    @auth
                        <h3 class="text-sm font-semibold text-forest mb-2">Tulis Balasan</h3>
                        @if(session('error'))
                            <div class="mb-3 rounded-2xl bg-red-50 border border-red-100 px-4 py-2 text-[11px] text-red-700">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="mb-3 rounded-2xl bg-emerald-50 border border-emerald-100 px-4 py-2 text-[11px] text-emerald-800">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('forum.reply', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <textarea
                                    name="body"
                                    rows="3"
                                    class="w-full rounded-2xl border border-gray-200 text-sm px-3 py-2 focus:ring-leaf focus:border-leaf"
                                    placeholder="Tulis tanggapan atau pertanyaanmu di sini..."
                                >{{ old('body') }}</textarea>
                                @error('body')
                                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <label class="inline-flex items-center gap-2 text-[11px] text-moss/80 cursor-pointer">
                                    <input type="file" name="image" accept="image/*" class="hidden" onchange="this.nextElementSibling.textContent = this.files[0]?.name || 'Pilih gambar (opsional)'">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-gray-200">
                                        <x-icons name="photo" class="w-4 h-4 text-moss/80" />
                                    </span>
                                    <span>Pilih gambar (opsional)</span>
                                </label>
                                <button type="submit" class="btn-primary text-sm px-4 py-2 justify-center">
                                    Kirim Balasan
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-sm text-moss/80">
                            <a href="{{ route('login') }}" class="text-leaf font-semibold hover:underline">Masuk</a>
                            untuk membalas diskusi ini.
                        </p>
                    @endauth
                </div>
            </section>
        </div>
    </section>
@endsection

