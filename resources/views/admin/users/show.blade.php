@extends('admin.layouts.dashboard')

@section('page_title', 'Detail User')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-forest">Detail User</h2>
            <p class="text-xs text-gray-500 mt-1">
                Profil dan aktivitas user forum.
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-xs text-emerald-700 hover:underline">
            ← Kembali ke daftar user
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm md:col-span-1">
            <div class="flex flex-col items-center text-center">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover mb-3">
                <h3 class="font-semibold text-forest text-sm">{{ $user->name }}</h3>
                <p class="text-[11px] text-gray-500 mb-2">{{ $user->email }}</p>
                <div class="mb-2 flex items-center justify-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                        {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700 border border-violet-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                        {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                    </span>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="inline-flex items-center gap-1">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" class="text-[10px] rounded border border-gray-200 py-1 px-2 focus:ring-leaf focus:border-leaf">
                                <option value="user" @selected($user->role === 'user')>User</option>
                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                            </select>
                            <span class="text-[10px] text-gray-400">Ubah role</span>
                        </form>
                    @endif
                </div>
                @if($user->location)
                    <p class="text-[11px] text-gray-500 flex items-center gap-1">
                        <x-icons name="map-pin" class="w-3.5 h-3.5" />
                        {{ $user->location }}
                    </p>
                @endif
                @if($user->bio)
                    <p class="mt-3 text-xs text-gray-600">{{ $user->bio }}</p>
                @endif
                <p class="mt-4 text-[11px] text-gray-500">
                    Bergabung sejak {{ $user->created_at->format('d M Y') }}
                </p>
                <div class="mt-3 flex flex-wrap justify-center gap-2">
                    @if($user->is_banned)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-100 text-[11px] font-semibold">
                            Dibanned
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[11px] font-semibold">
                            Aktif
                        </span>
                    @endif
                </div>

                <div class="mt-4 flex flex-col gap-2 w-full">
                    @if($user->is_banned)
                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" onsubmit="return confirm('Aktifkan kembali user ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-700 text-white text-xs font-semibold px-3 py-2 hover:bg-emerald-800">
                                Unban User
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" onsubmit="return confirm('Ban user ini? User tidak akan bisa membuat post/reply.')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-600 text-white text-xs font-semibold px-3 py-2 hover:bg-red-700">
                                Ban User
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini beserta semua post dan reply miliknya? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-2 hover:bg-red-50 hover:text-red-700">
                            Hapus User
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-4">
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-[11px] text-gray-500 uppercase tracking-[0.18em] mb-1">Post Forum</p>
                    <p class="text-2xl font-heading text-forest">{{ $stats['posts'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-[11px] text-gray-500 uppercase tracking-[0.18em] mb-1">Reply</p>
                    <p class="text-2xl font-heading text-forest">{{ $stats['replies'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <p class="text-[11px] text-gray-500 uppercase tracking-[0.18em] mb-1">Likes Diterima</p>
                    <p class="text-2xl font-heading text-forest">{{ $stats['likes_received'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                <h3 class="text-sm font-semibold text-forest mb-3">Post Forum</h3>
                @if($posts->isEmpty())
                    <p class="text-xs text-gray-500">
                        User ini belum membuat post forum.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-600">Judul</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-600">Kategori</th>
                                    <th class="px-3 py-2 text-left font-semibold text-gray-600">Tanggal</th>
                                    <th class="px-3 py-2 text-center font-semibold text-gray-600">Balasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                    <tr class="border-b border-gray-50">
                                        <td class="px-3 py-2">
                                            <a href="{{ route('forum.show', $post) }}" class="text-emerald-700 hover:underline">
                                                {{ \Illuminate\Support\Str::limit($post->title, 60) }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-2 text-gray-600">
                                            {{ $post->category ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2 text-gray-600">
                                            {{ $post->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-3 py-2 text-center text-gray-800">
                                            {{ $post->replies_count }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

