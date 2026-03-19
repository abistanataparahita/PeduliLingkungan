@extends('admin.layouts.dashboard')

@section('page_title', 'Kelola User')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-forest">Kelola User</h2>
            <p class="text-xs text-gray-500 mt-1">
                Pantau dan kelola akun user forum komunitas.
            </p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[11px] text-gray-500 uppercase tracking-[0.18em] mb-1">Total User</p>
            <p class="text-2xl font-heading text-forest">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[11px] text-gray-500 uppercase tracking-[0.18em] mb-1">Aktif</p>
            <p class="text-2xl font-heading text-emerald-700">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[11px] text-gray-500 uppercase tracking-[0.18em] mb-1">Dibanned</p>
            <p class="text-2xl font-heading text-red-600">{{ $stats['banned'] }}</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm mb-4">
        <div class="flex flex-col md:flex-row md:items-center gap-3">
            <div class="flex-1">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <x-icons name="magnifying-glass" class="w-4 h-4" />
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama atau email user..."
                        class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-leaf focus:border-leaf"
                    >
                </div>
            </div>
            <div>
                <select name="role" class="rounded-lg border border-gray-200 text-sm px-3 py-2 focus:ring-leaf focus:border-leaf">
                    <option value="">Semua role</option>
                    <option value="admin" @selected($roleFilter === 'admin')>Admin</option>
                    <option value="user" @selected($roleFilter === 'user')>User</option>
                </select>
            </div>
            <div>
                <select name="status" class="rounded-lg border border-gray-200 text-sm px-3 py-2 focus:ring-leaf focus:border-leaf">
                    <option value="">Semua status</option>
                    <option value="active" @selected($status === 'active')>Aktif</option>
                    <option value="banned" @selected($status === 'banned')>Dibanned</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn-admin text-xs px-4 py-2">
                    Filter
                </button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="min-w-full text-xs">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">User</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal Daftar</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Post Forum</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3">
                            @if($user->id === auth()->id())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                                    {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700 border border-violet-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                                </span>
                                <span class="text-[10px] text-gray-400 ml-1">(kamu)</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                                    {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700 border border-violet-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                                </span>
                                <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="inline-block mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="text-[10px] rounded border border-gray-200 py-0.5 px-1.5 focus:ring-leaf focus:border-leaf">
                                        <option value="user" @selected($user->role === 'user')>User</option>
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                    </select>
                                </form>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-800">
                            {{ $user->forum_posts_count }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($user->is_banned)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-100 text-[11px] font-semibold">
                                    Dibanned
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[11px] font-semibold">
                                    Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-xs text-emerald-700 hover:underline">
                                    Detail
                                </a>
                                @if($user->is_banned)
                                    <form action="{{ route('admin.users.unban', $user) }}" method="POST" onsubmit="return confirm('Aktifkan kembali user ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-emerald-700 hover:underline">
                                            Unban
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" onsubmit="return confirm('Ban user ini? User tidak akan bisa membuat post/reply.')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs text-red-600 hover:underline">
                                            Ban
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini beserta semua post dan reply miliknya? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-gray-500 hover:text-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500 text-sm">
                            Belum ada user yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection

