@extends('layouts.app_public')

@section('title', 'Profil · ' . $user->name)

@section('content')
    <section class="bg-cream py-10 sm:py-16 rv">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs text-moss/70 hover:text-leaf">
                    <x-icons name="arrow-left" class="w-3.5 h-3.5" />
                    Kembali ke Beranda
                </a>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-2xl bg-leaf/10 border border-leaf/40 px-4 py-3 text-sm text-forest">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Header profil --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm mb-6">
                <div class="flex flex-col sm:flex-row gap-6 items-start">
                    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatar-form">
                        @csrf
                        <label class="relative cursor-pointer group block">
                            <img
                                src="{{ $user->avatar_url }}"
                                alt="{{ $user->name }}"
                                class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-lg"
                            >
                            <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <span class="text-white text-xs font-semibold">Ganti Foto</span>
                            </div>
                            <input
                                type="file"
                                name="avatar"
                                class="hidden"
                                accept="image/*"
                                onchange="document.getElementById('avatar-form').submit()"
                            >
                        </label>
                    </form>
                    <div class="flex-1 min-w-0">
                        <h1 class="font-heading text-2xl md:text-3xl text-forest">{{ $user->name }}</h1>
                        <p class="text-sm text-moss/80 mt-0.5">{{ $user->email }}</p>
                        @if ($user->bio)
                            <p class="text-sm text-moss/90 mt-2">{{ $user->bio }}</p>
                        @endif
                        @if ($user->location)
                            <p class="inline-flex items-center gap-1 text-xs text-moss/70 mt-2">
                                <x-icons name="map-pin" class="w-3.5 h-3.5" />
                                {{ $user->location }}
                            </p>
                        @endif
                        <p class="text-xs text-moss/60 mt-2">
                            Bergabung {{ $user->created_at->translatedFormat('d F Y') }}
                        </p>
                        <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full bg-leaf/10 text-forest text-xs font-semibold border border-leaf/40">
                            {{ $totalPosts }} post
                        </span>
                    </div>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm text-center">
                    <p class="text-2xl font-heading font-semibold text-forest">{{ $totalPosts }}</p>
                    <p class="text-xs text-moss/70 mt-0.5">Total Post</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm text-center">
                    <p class="text-2xl font-heading font-semibold text-forest">{{ $totalReplies }}</p>
                    <p class="text-xs text-moss/70 mt-0.5">Total Reply</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm text-center">
                    <p class="text-2xl font-heading font-semibold text-forest">{{ $totalLikesReceived }}</p>
                    <p class="text-xs text-moss/70 mt-0.5">Total Likes Diterima</p>
                </div>
            </div>

            {{-- Tabs --}}
            <div
                x-data="{ tab: '{{ $tab }}' }"
                x-init="$watch('tab', v => { const u = new URL(location); u.searchParams.set('tab', v); history.replaceState({}, '', u) })"
                class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="flex border-b border-gray-100">
                    <button
                        type="button"
                        @click="tab = 'posts'"
                        :class="tab === 'posts' ? 'border-leaf text-forest font-semibold bg-leaf/5' : 'border-transparent text-moss/70 hover:text-forest hover:bg-gray-50'"
                        class="flex-1 px-4 py-3 text-sm border-b-2 transition-colors"
                    >
                        Post Saya
                    </button>
                    <button
                        type="button"
                        @click="tab = 'settings'"
                        :class="tab === 'settings' ? 'border-leaf text-forest font-semibold bg-leaf/5' : 'border-transparent text-moss/70 hover:text-forest hover:bg-gray-50'"
                        class="flex-1 px-4 py-3 text-sm border-b-2 transition-colors"
                    >
                        Pengaturan Akun
                    </button>
                </div>

                <div class="p-5 sm:p-6">
                    {{-- Tab Post Saya --}}
                    <div x-show="tab === 'posts'" x-cloak x-transition>
                        @if ($posts->isEmpty())
                            <p class="text-sm text-moss/80">Belum ada post. <a href="{{ route('forum.create') }}" class="text-leaf font-semibold hover:underline">Buat post pertama</a></p>
                        @else
                            <div class="space-y-4">
                                @foreach ($posts as $post)
                                    <article class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-2xl border border-gray-100 hover:border-leaf/40 transition">
                                        <div class="flex-1 min-w-0">
                                            <h2 class="font-heading text-base text-forest line-clamp-1">{{ $post->title }}</h2>
                                            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-moss/70">
                                                @if ($post->category)
                                                    <span class="px-2 py-0.5 rounded-full bg-leaf/10 text-forest">{{ $post->category }}</span>
                                                @endif
                                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                                <span>·</span>
                                                <span>{{ $post->replies()->count() }} balasan</span>
                                                <span>·</span>
                                                <span>{{ $post->likes()->count() }} suka</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <a href="{{ route('forum.show', $post) }}" class="btn-primary text-xs px-4 py-2">
                                                Lihat
                                            </a>
                                            <form action="{{ route('forum.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Hapus post ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 rounded-full border border-red-200 text-red-600 text-xs font-medium hover:bg-red-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    </div>

                    {{-- Tab Pengaturan Akun --}}
                    <div x-show="tab === 'settings'" x-cloak x-transition>
                        <div class="space-y-8">
                            {{-- Form Informasi Profil --}}
                            <div>
                                <h3 class="font-heading text-lg text-forest mb-3">Informasi Profil</h3>
                                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="label-field" for="name">Nama</label>
                                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="input-field">
                                        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="label-field" for="bio">Bio</label>
                                        <textarea id="bio" name="bio" rows="3" class="w-full rounded-2xl border border-gray-200 text-sm px-3 py-2.5 focus:ring-leaf focus:border-leaf" placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
                                        @error('bio')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="label-field" for="location">Lokasi</label>
                                        <input id="location" type="text" name="location" value="{{ old('location', $user->location) }}" class="input-field" placeholder="Contoh: Purbalingga">
                                        @error('location')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <button type="submit" class="btn-primary text-sm px-5 py-2.5">Simpan</button>
                                </form>
                            </div>

                            {{-- Form Ubah Password --}}
                            <div class="pt-6 border-t border-gray-100">
                                <h3 class="font-heading text-lg text-forest mb-3">Ubah Password</h3>
                                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="label-field" for="current_password">Password saat ini</label>
                                        <div class="relative">
                                            <input id="current_password" name="current_password" :type="showCurrent ? 'text' : 'password'" required class="input-field pr-10">
                                            <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3 top-1/2 -translate-y-1/2 text-moss/60 hover:text-forest text-xs">
                                                <span x-text="showCurrent ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</span>
                                            </button>
                                        </div>
                                        @error('current_password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="label-field" for="password">Password baru</label>
                                        <div class="relative">
                                            <input id="password" name="password" :type="showNew ? 'text' : 'password'" required class="input-field pr-10" minlength="8">
                                            <button type="button" @click="showNew = !showNew" class="absolute right-3 top-1/2 -translate-y-1/2 text-moss/60 hover:text-forest text-xs">
                                                <span x-text="showNew ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</span>
                                            </button>
                                        </div>
                                        @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="label-field" for="password_confirmation">Konfirmasi password baru</label>
                                        <div class="relative">
                                            <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" required class="input-field pr-10" minlength="8">
                                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-moss/60 hover:text-forest text-xs">
                                                <span x-text="showConfirm ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</span>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn-primary text-sm px-5 py-2.5">Simpan Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
