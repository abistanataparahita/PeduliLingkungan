@extends('layouts.app_public')

@section('title', 'Pesan Produk — ' . $product->name)

@section('content')
<section class="pt-28 pb-16 min-h-screen">
    <div class="max-w-5xl mx-auto px-4">
        <x-back-button href="{{ route('products.show', $product) }}" label="Kembali ke Detail Produk" />

        <div class="mt-6 grid gap-6 lg:grid-cols-2" x-data="{ qty: 1, price: {{ (int) round((float) $product->final_price) }} }">
            {{-- Ringkasan produk --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <div class="space-y-4">
                    <div class="h-48 w-full rounded-2xl overflow-hidden bg-gray-100">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">Tanpa gambar</div>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">Produk</p>
                        <h1 class="font-heading text-2xl text-forest mt-1">{{ $product->name }}</h1>
                        <p class="text-sm text-gray-600 mt-2">
                            Harga satuan:
                            <span class="font-semibold text-forest">{{ $product->formatted_final_price }}</span>
                            @if($product->has_discount)
                                <span class="text-gray-400 line-through text-xs ml-2">{{ $product->formatted_selling_price }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
                        <p class="text-xs text-emerald-700 font-semibold">Preview Total</p>
                        <p class="mt-1 text-2xl font-heading text-forest" x-text="'Rp ' + (qty * price).toLocaleString('id-ID')"></p>
                        <p class="text-[11px] text-emerald-700/80 mt-1">Total mengikuti jumlah yang kamu isi.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <h2 class="text-sm font-semibold text-forest">Form Pesanan</h2>
                <p class="text-xs text-gray-500 mt-1">Isi data, lalu kamu akan diarahkan ke WhatsApp admin untuk konfirmasi.</p>

                <form action="{{ route('orders.store', $product) }}" method="POST" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama lengkap <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            name="buyer_name"
                            value="{{ old('buyer_name', auth()->user()->name ?? '') }}"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:ring-leaf focus:border-leaf"
                            required
                        >
                        @error('buyer_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            name="whatsapp"
                            value="{{ old('whatsapp') }}"
                            placeholder="08..."
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:ring-leaf focus:border-leaf"
                            required
                        >
                        @error('whatsapp')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                        <input
                            type="number"
                            name="qty"
                            min="1"
                            x-model.number="qty"
                            value="{{ old('qty', 1) }}"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:ring-leaf focus:border-leaf"
                            required
                        >
                        @error('qty')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                        <textarea
                            name="catatan"
                            rows="3"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:ring-leaf focus:border-leaf"
                            placeholder="Catatan untuk admin..."
                        >{{ old('catatan') }}</textarea>
                        @error('catatan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                        <p class="text-xs text-gray-500">Preview total</p>
                        <p class="mt-1 text-lg font-semibold text-forest" x-text="'Rp ' + (qty * price).toLocaleString('id-ID')"></p>
                    </div>

                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 text-white font-semibold text-sm px-6 py-3 hover:bg-emerald-800 transition"
                    >
                        Konfirmasi & Chat WhatsApp 💬
                    </button>
                    <p class="text-[11px] text-gray-500 text-center">
                        Setelah klik, kamu akan diarahkan ke WhatsApp untuk konfirmasi pesanan
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

