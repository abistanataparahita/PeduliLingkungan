@extends('layouts.app_public')

@section('title', 'Daftar · Forum Peduli Lingkungan')

@section('content')
    <section class="bg-cream py-12 sm:py-20">
        <div class="max-w-md mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h1 class="font-heading text-2xl text-forest mb-2">Daftar Akun Forum</h1>
                <p class="text-sm text-moss/80 mb-6">
                    Buat akun untuk bergabung dalam diskusi dan membagikan aksi peduli lingkunganmu.
                </p>

                @if ($errors->any())
                    <div class="mb-4 rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-xs text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="label-field" for="name">Nama Lengkap</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="input-field"
                        >
                    </div>

                    <div>
                        <label class="label-field" for="email">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="input-field"
                        >
                    </div>

                    <div>
                        <label class="label-field" for="password">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="input-field"
                        >
                    </div>

                    <div>
                        <label class="label-field" for="password_confirmation">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            class="input-field"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full btn-primary mt-2 justify-center"
                    >
                        Daftar & Masuk
                    </button>
                </form>

                <p class="mt-6 text-xs text-moss/70 text-center">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-leaf font-semibold hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </section>
@endsection

