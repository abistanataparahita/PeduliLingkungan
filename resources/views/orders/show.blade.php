@extends('layouts.app_public')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
@php
    $product = $order->product;
    $unitPrice = (int) round((float) ($product?->final_price ?? 0));
    $total = $unitPrice * (int) $order->qty;
    $statusMap = [
        'pending' => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-800 border-amber-200'],
        'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-sky-100 text-sky-800 border-sky-200'],
        'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-rose-100 text-rose-800 border-rose-200'],
    ];
    $s = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-gray-100 text-gray-700 border-gray-200'];

    $steps = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'selesai' => 'Selesai',
    ];
    $rank = ['pending' => 1, 'confirmed' => 2, 'selesai' => 3, 'dibatalkan' => 0];
    $currentRank = $rank[$order->status] ?? 0;

    $waNumber = preg_replace('/[^0-9]/', '', setting('wa_phone', '6281229428356'));
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
    $waText = urlencode(
        "Halo Admin Peduli Lingkungan 👋\n\n" .
        "Saya ingin menanyakan pesanan saya:\n" .
        "Order ID: #{$order->id}\n" .
        "Produk: " . ($product?->name ?? '-') . "\n" .
        "Qty: {$order->qty}\n" .
        "Total: Rp " . number_format($total, 0, ',', '.') . "\n" .
        "Nama: {$order->buyer_name}\n" .
        "WA: {$order->whatsapp}\n" .
        ($order->catatan ? "Catatan: {$order->catatan}\n" : '') .
        "\nTerima kasih 🙏"
    );
@endphp

<section class="pt-28 pb-16 min-h-screen">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between gap-4">
            <x-back-button href="{{ route('orders.index') }}" label="Kembali ke Pesanan Saya" />
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border {{ $s['class'] }}">
                {{ $s['label'] }}
            </span>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h1 class="font-heading text-2xl text-forest">Detail Pesanan #{{ $order->id }}</h1>
                    <p class="text-xs text-gray-500 mt-1">
                        Dipesan pada {{ $order->created_at?->translatedFormat('d F Y · H:i') }}
                    </p>
                </div>

                <div class="p-5 grid md:grid-cols-3 gap-5">
                    <div class="md:col-span-1">
                        <div class="w-full h-48 rounded-2xl overflow-hidden bg-gray-100">
                            @if($product?->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">Tanpa gambar</div>
                            @endif
                        </div>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <p class="text-xs text-gray-500">Produk</p>
                        <p class="font-semibold text-forest text-lg">{{ $product?->name ?? '-' }}</p>
                        <div class="grid sm:grid-cols-2 gap-3 mt-3">
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                <p class="text-xs text-gray-500">Qty</p>
                                <p class="mt-1 font-semibold text-forest">{{ $order->qty }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                <p class="text-xs text-gray-500">Total</p>
                                <p class="mt-1 font-semibold text-forest">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                <p class="text-xs text-gray-500">Nama pembeli</p>
                                <p class="mt-1 font-semibold text-forest">{{ $order->buyer_name }}</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                <p class="text-xs text-gray-500">WhatsApp</p>
                                <p class="mt-1 font-semibold text-forest">{{ $order->whatsapp }}</p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 mt-3">
                            <p class="text-xs text-gray-500">Catatan</p>
                            <p class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $order->catatan ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="text-sm font-semibold text-forest">Timeline Status</h2>
                <div class="mt-4 space-y-3">
                    @foreach($steps as $key => $label)
                        @php
                            $done = $currentRank >= ($rank[$key] ?? 99) && $currentRank > 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center border
                                {{ $done ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-gray-200 text-gray-400' }}
                            ">
                                <x-icons name="check" class="w-4 h-4" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold {{ $done ? 'text-forest' : 'text-gray-500' }}">{{ $label }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($order->status === 'dibatalkan')
                    <div class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-100">
                        <p class="text-sm font-semibold text-rose-800">Pesanan dibatalkan</p>
                        <p class="text-xs text-rose-700/80 mt-1">Jika ada pertanyaan, chat admin untuk info lebih lanjut.</p>
                    </div>
                @endif

                <a
                    href="https://wa.me/{{ $waNumber }}?text={{ $waText }}"
                    target="_blank"
                    rel="noopener"
                    class="mt-6 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 text-white font-semibold text-sm px-6 py-3 hover:bg-emerald-800 transition"
                >
                    <x-icons name="whatsapp" class="w-5 h-5" />
                    Chat Admin
                </a>
                <p class="text-[11px] text-gray-500 mt-2 text-center">WhatsApp adalah media utama konfirmasi pesanan.</p>
            </div>
        </div>
    </div>
</section>
@endsection

