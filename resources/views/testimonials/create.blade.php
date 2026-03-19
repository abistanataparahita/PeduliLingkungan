@extends('layouts.app_public')

@section('title', 'Kirim Testimoni')

@section('content')
<section class="pt-28 pb-16 min-h-screen">
    <div class="max-w-3xl mx-auto px-4">
        <x-back-button href="{{ route('home') }}#testimonials" label="Kembali" />

        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-heading text-2xl text-forest">Kirim Testimoni</h1>
                    <p class="text-sm text-gray-600 mt-1">Ceritakan pengalamanmu bergabung/berinteraksi di Peduli Lingkungan.</p>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                    Menunggu review
                </span>
            </div>

            <form method="POST" action="{{ route('testimonials.store') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', auth()->user()->name ?? '') }}"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:ring-leaf focus:border-leaf"
                        required
                    >
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Peran / Pekerjaan <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="role"
                        value="{{ old('role', 'Member') }}"
                        placeholder="Contoh: Member, Volunteer, Pelajar..."
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:ring-leaf focus:border-leaf"
                        required
                    >
                    @error('role')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Testimoni <span class="text-red-500">*</span></label>
                    <textarea
                        name="quote"
                        rows="4"
                        maxlength="500"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:ring-leaf focus:border-leaf"
                        placeholder="Tulis pengalamanmu (maks 500 karakter)..."
                        required
                    >{{ old('quote') }}</textarea>
                    @error('quote')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    <p class="text-[11px] text-gray-500 mt-1">Testimoni kamu akan tampil setelah ditinjau admin.</p>
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 text-white font-semibold text-sm px-6 py-3 hover:bg-emerald-800 transition"
                >
                    Kirim Testimoni
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

