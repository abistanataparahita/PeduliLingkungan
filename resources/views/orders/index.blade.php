@extends('layouts.app_public')

@section('title', 'Pesanan Saya')

@section('content')
<section class="pt-28 pb-16 min-h-screen">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="font-heading text-2xl md:text-3xl text-forest">Pesanan Saya</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Total pesanan: <span class="font-semibold text-forest">{{ $orders->total() }}</span>
                </p>
            </div>
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-forest hover:bg-white transition"
            >
                Lihat Produk
                <x-icons name="arrow-right" class="w-4 h-4" />
            </a>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($orders as $order)
                @php
                    $unitPrice = (int) round((float) ($order->product?->final_price ?? 0));
                    $total = $unitPrice * (int) $order->qty;
                    $statusMap = [
                        'pending' => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-800 border-amber-200'],
                        'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-sky-100 text-sky-800 border-sky-200'],
                        'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-rose-100 text-rose-800 border-rose-200'],
                    ];
                    $s = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-100 text-gray-700 border-gray-200'];
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-100 shrink-0">
                            @if($order->product?->image)
                                <img src="{{ $order->product->image_url }}" alt="{{ $order->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Tanpa gambar</div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs text-gray-500">Produk</p>
                                    <p class="font-semibold text-forest">{{ $order->product?->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Qty: <span class="font-semibold text-forest">{{ $order->qty }}</span>
                                        · Total: <span class="font-semibold text-forest">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-1">
                                        {{ $order->created_at?->translatedFormat('d F Y · H:i') }}
                                    </p>
                                </div>

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold border {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </div>

                            <div class="mt-3 flex justify-end">
                                <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                    <div class="mx-auto w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-700">
                        <x-icons name="shopping-bag" class="w-6 h-6" />
                    </div>
                    <p class="mt-4 font-semibold text-forest">Belum ada pesanan</p>
                    <p class="text-sm text-gray-600 mt-1">Yuk lihat produk dulu dan buat pesanan pertamamu.</p>
                    <a
                        href="{{ route('products.index') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-full bg-emerald-700 text-white font-semibold text-sm px-6 py-3 hover:bg-emerald-800 transition"
                    >
                        Lihat Produk
                        <x-icons name="arrow-right" class="w-4 h-4" />
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</section>
@endsection

