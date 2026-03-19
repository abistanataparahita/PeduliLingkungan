@extends('layouts.app_public')

@section('title', 'Masuk · Forum Peduli Lingkungan')

@section('content')
    <section class="bg-cream py-12 sm:py-20">
        <div class="max-w-md mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h1 class="font-heading text-2xl text-forest mb-2">Masuk ke Forum</h1>
                <p class="text-sm text-moss/80 mb-6">
                    Diskusikan ide, tanya jawab, dan laporkan isu lingkungan bersama komunitas.
                </p>

                @if ($errors->any())
                    <div class="mb-4 rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-xs text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="label-field" for="email">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
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

                    <div class="flex items-center justify-between text-xs text-moss/80">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-leaf focus:ring-leaf">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full btn-primary mt-2 justify-center"
                    >
                        Masuk
                    </button>
                </form>

                <p class="mt-6 text-xs text-moss/70 text-center">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-leaf font-semibold hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </section>
@endsection

