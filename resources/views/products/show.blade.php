@extends('layouts.app_public')

@section('title', $product->name . ' — Peduli Lingkungan')

@section('content')
<section
    class="pt-28 pb-16 min-h-screen"
    x-data="{ preorderModal: false, qty: 1, maxQty: {{ $product->preorder_remaining_quota ?? 'null' }} }"
>
    <div class="max-w-4xl mx-auto px-4">
        <x-back-button href="{{ route('products.index') }}" label="Kembali ke Katalog" />

        @if(session('success'))
            <div
                x-data="{ open: true }"
                x-init="setTimeout(() => open = false, 7000)"
                x-show="open"
                x-transition
                class="fixed bottom-6 right-6 z-[80] max-w-md w-[calc(100vw-3rem)]"
            >
                <div class="p-4 rounded-2xl bg-white shadow-xl border border-emerald-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-forest">Berhasil</p>
                            <p class="text-sm text-gray-700 mt-1">{{ session('success') }}</p>
                        </div>
                        <button
                            type="button"
                            class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500"
                            @click="open = false"
                            aria-label="Tutup"
                        >
                            <x-icons name="x-mark" class="w-5 h-5" />
                        </button>
                    </div>

                    @if(session('wa_url'))
                        <div class="mt-3 flex items-center justify-end">
                            <a
                                href="{{ session('wa_url') }}"
                                target="_blank"
                                rel="noopener"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition"
                            >
                                <x-icons name="whatsapp" class="w-5 h-5" />
                                Chat Admin
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 text-red-800 text-sm border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-6">
            <div class="grid md:grid-cols-2 gap-0">
                <div class="aspect-square md:aspect-auto md:min-h-[320px] bg-gray-100">
                    @if($product->image)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">Tanpa gambar</div>
                    @endif
                </div>
                <div class="p-6 md:p-8 flex flex-col">
                    <h1 class="font-heading text-2xl md:text-3xl text-forest mt-1">{{ $product->name }}</h1>

                    @if($product->is_preorder)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold mt-3 w-fit {{ $product->is_preorder_closed ? 'bg-gray-200 text-gray-700' : 'bg-violet-100 text-violet-700' }}">
                            {{ $product->is_preorder_closed ? 'Pre Order Ditutup' : 'Pre Order' }}
                        </span>
                    @endif

                    <div class="mt-4">
                        <span class="text-2xl font-semibold text-forest">{{ $product->formatted_final_price }}</span>
                        @if($product->has_discount)
                            <span class="text-gray-400 line-through text-sm ml-2">{{ $product->formatted_selling_price }}</span>
                        @endif
                    </div>

                    @if(! $product->is_preorder)
                        <p class="text-sm text-gray-600 mt-2">Stok: {{ $product->current_stock }} {{ $product->pcs }}</p>
                    @endif

                    @if($product->description)
                        <div class="mt-4 text-sm text-gray-600 prose prose-sm max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    @endif

                    @if($product->is_preorder && ! $product->is_preorder_closed)
                        <div class="mt-4 p-3 rounded-xl bg-violet-50 text-sm text-violet-800 space-y-1">
                            @if($product->preorder_estimate)
                                <p><strong>Estimasi ketersediaan:</strong> {{ $product->preorder_estimate }}</p>
                            @endif
                            @if($product->preorder_open_until)
                                <p><strong>Batas waktu pre order:</strong> {{ $product->preorder_open_until->translatedFormat('d F Y') }}</p>
                            @endif
                            @if($product->preorder_remaining_quota !== null)
                                <p><strong>Sisa kuota:</strong> {{ $product->preorder_remaining_quota }}</p>
                            @endif
                        </div>

                        @if($product->preorder_remaining_quota !== null)
                            <div class="mt-5 flex items-center gap-3">
                                <div class="inline-flex items-center border border-gray-200 rounded-full overflow-hidden">
                                    <button
                                        type="button"
                                        class="px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100"
                                        @click="qty = Math.max(1, qty - 1)"
                                    >
                                        −
                                    </button>
                                    <span
                                        class="px-4 py-1.5 text-sm font-semibold text-forest"
                                        x-text="qty"
                                    ></span>
                                    <button
                                        type="button"
                                        class="px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100"
                                        @click="qty = maxQty ? Math.min(maxQty, qty + 1) : qty + 1"
                                    >
                                        +
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Sisa kuota: {{ $product->preorder_remaining_quota }}
                                </p>
                            </div>
                        @endif
                    @endif

                    <div class="mt-6 flex flex-wrap gap-3">
                        @if($product->is_preorder)
                            @if($product->is_preorder_closed)
                                <button type="button" disabled class="px-4 py-2.5 rounded-xl bg-gray-200 text-gray-500 text-sm font-medium cursor-not-allowed">
                                    Pre Order Ditutup
                                </button>
                            @elseif($hasPreordered)
                                <span class="px-4 py-2.5 rounded-xl bg-emerald-100 text-emerald-800 text-sm font-medium">Kamu sudah pre order produk ini</span>
                            @elseif(auth()->check())
                                <button type="button" @click="preorderModal = true" class="px-4 py-2.5 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700 transition">
                                    Pre Order Sekarang
                                </button>
                            @else
                                <a href="{{ route('login') }}?intended={{ urlencode(route('products.show', $product)) }}" class="inline-flex px-4 py-2.5 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700 transition">
                                    Login untuk Pre Order
                                </a>
                            @endif
                        @else
                            @auth
                                <a href="{{ route('orders.create', $product) }}"
                                   class="inline-flex items-center gap-2 rounded-full bg-emerald-700 text-white font-semibold text-sm px-6 py-3 hover:bg-emerald-800 transition">
                                    🛒 Pesan Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 rounded-full bg-emerald-700 text-white font-semibold text-sm px-6 py-3 hover:bg-emerald-800 transition">
                                    🛒 Pesan Sekarang
                                </a>
                            @endauth
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('wa_phone', '6281229428356')) }}?text={{ urlencode('Halo, saya ingin tanya tentang produk: ' . $product->name) }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center gap-2 rounded-full border border-emerald-700 text-emerald-700 font-semibold text-sm px-6 py-3 hover:bg-emerald-50 transition">
                                💬 Tanya via WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Pre Order --}}
    <div
        x-show="preorderModal"
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center p-4"
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/50"
            @click="preorderModal = false"
        ></div>

        {{-- Modal Box --}}
        <div
            x-show="preorderModal"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="relative z-10 bg-white rounded-2xl shadow-xl max-w-md w-full p-6"
        >
            <h2 class="text-lg font-semibold text-forest mb-4">Form Pre Order</h2>
            <form action="{{ route('products.preorder.store', $product) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama pemesan</label>
                    <input type="text" value="{{ auth()->user()?->name ?? '' }}" readonly class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm bg-gray-50">
                </div>
                <input type="hidden" name="buyer_name" value="{{ auth()->user()?->name ?? '' }}">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08..." class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-leaf focus:border-leaf" required>
                    @error('phone')<p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah order <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        name="quantity"
                        x-model="qty"
                        min="1"
                        :max="maxQty ?? undefined"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-leaf focus:border-leaf"
                        required
                    >
                    @if($product->preorder_remaining_quota !== null)
                        <p class="text-[11px] text-gray-500 mt-0.5">Sisa kuota: {{ $product->preorder_remaining_quota }}</p>
                    @endif
                    @error('quantity')<p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea name="note" rows="2" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-leaf focus:border-leaf" placeholder="Catatan untuk admin...">{{ old('note') }}</textarea>
                    @error('note')<p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700">Kirim Pre Order</button>
                    <button type="button" @click="preorderModal = false" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
