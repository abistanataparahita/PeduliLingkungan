@extends('admin.layouts.dashboard')

@section('page_title', 'Detail Produk')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-base font-semibold text-forest">Detail Produk</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-2 rounded-lg border border-gray-200 text-xs font-semibold">Edit</a>
            <a href="{{ route('admin.products.confirm-delete', $product) }}" class="px-3 py-2 rounded-lg border border-red-200 text-xs text-red-600">Hapus</a>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-emerald-700 hover:underline">← Daftar Produk</a>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-4 space-y-3">
            @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-lg object-cover max-h-64">
            @else
                <div class="w-full h-48 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-sm">Tanpa gambar</div>
            @endif
            <div>
                <h2 class="font-semibold text-forest">{{ $product->name }}</h2>
                <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
            </div>
            @if($product->description)
                <p class="text-xs text-gray-600">{{ $product->description }}</p>
            @endif
            <div class="grid grid-cols-2 gap-2 text-xs">
                <p class="text-gray-500">Harga jual</p>
                <p>{{ $product->formatted_selling_price }}</p>
                @if($product->has_discount)
                    <p class="text-gray-500">Harga diskon</p>
                    <p class="text-emerald-600">{{ $product->formatted_discount_price }}</p>
                @endif
                <p class="text-gray-500">Stok</p>
                <p>{{ $product->current_stock }} {{ $product->pcs }} (status: {{ $product->stock_status }})</p>
            </div>
            <div class="pt-2 border-t">
                @if($product->is_preorder)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $product->is_preorder_closed ? 'bg-gray-100 text-gray-600' : 'bg-violet-100 text-violet-700' }}">
                        Pre Order: {{ $product->is_preorder_closed ? 'Ditutup' : 'Open' }}
                    </span>
                    <p class="text-xs text-gray-600 mt-1">Terisi: {{ $product->preorder_filled }}{{ $product->preorder_quota ? ' / ' . $product->preorder_quota : '' }}</p>
                    @if($product->preorder_estimate)<p class="text-xs text-gray-500">Estimasi: {{ $product->preorder_estimate }}</p>@endif
                    @if($product->preorder_open_until)<p class="text-xs text-gray-500">Batas: {{ $product->preorder_open_until->format('d M Y') }}</p>@endif
                @else
                    <span class="text-gray-500 text-xs">Produk biasa (bukan pre order)</span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4">
            <h3 class="text-sm font-semibold text-forest mb-3">Daftar Pre Order</h3>
            @if($product->preorders->isEmpty())
                <p class="text-xs text-gray-500">Belum ada pre order.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                        <tr class="text-gray-500 text-[11px] border-b">
                            <th class="py-2 text-left">User</th>
                            <th class="py-2 text-left">WhatsApp</th>
                            <th class="py-2 text-center">Jumlah</th>
                            <th class="py-2 text-left">Status</th>
                            <th class="py-2 text-left">Tanggal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($product->preorders as $po)
                            <tr class="border-b last:border-0">
                                <td class="py-2">{{ $po->user->name }}</td>
                                <td class="py-2">{{ $po->phone }}</td>
                                <td class="py-2 text-center">{{ $po->quantity }}</td>
                                <td class="py-2">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] {{ $po->status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : ($po->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $po->status }}
                                    </span>
                                </td>
                                <td class="py-2 text-gray-500">{{ $po->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
