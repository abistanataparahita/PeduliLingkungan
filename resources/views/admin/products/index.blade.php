@extends('admin.layouts.dashboard')

@section('page_title', 'Produk')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-base font-semibold text-forest">Produk Merchandise</h1>
        <a href="{{ route('admin.products.create') }}" class="px-3 py-2 rounded-lg bg-forest text-cream text-xs font-semibold">
            + Tambah Produk
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4 space-y-3">
        <form method="GET" class="flex flex-wrap gap-3 items-center text-xs mb-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / SKU..." class="border rounded-md px-2 py-1.5 w-48">
            <select name="preorder" class="border rounded-md px-2 py-1.5">
                <option value="">Semua</option>
                <option value="1" @selected(request('preorder') === '1')>Pre Order</option>
                <option value="0" @selected(request('preorder') === '0')>Biasa</option>
            </select>
            <button type="submit" class="px-2 py-1.5 rounded-md border border-gray-200">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                <tr class="text-gray-500 text-[11px] border-b">
                    <th class="py-2 text-left">Gambar</th>
                    <th class="py-2 text-left">Nama / SKU</th>
                    <th class="py-2 text-left">Harga</th>
                    <th class="py-2 text-center">Stok</th>
                    <th class="py-2 text-left">Pre Order</th>
                    <th class="py-2 text-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr class="border-b last:border-0">
                        <td class="py-2">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-md">
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="py-2">
                            <p class="font-medium text-forest">{{ $product->name }}</p>
                            <p class="text-gray-500">{{ $product->sku }}</p>
                        </td>
                        <td class="py-2 text-gray-700">
                            {{ $product->formatted_final_price }}
                            @if($product->has_discount)
                                <span class="text-red-600 text-[10px]">diskon</span>
                            @endif
                        </td>
                        <td class="py-2 text-center">{{ $product->current_stock }} {{ $product->pcs }}</td>
                        <td class="py-2">
                            @if($product->is_preorder)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] {{ $product->is_preorder_closed ? 'bg-gray-100 text-gray-600' : 'bg-violet-100 text-violet-700' }}">
                                    {{ $product->is_preorder_closed ? 'Ditutup' : 'Open' }}
                                </span>
                                <span class="text-gray-500">({{ $product->preorder_filled }}/{{ $product->preorder_quota ?? '∞' }})</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="py-2 text-right">
                            <div class="inline-flex gap-1">
                                <a href="{{ route('admin.products.show', $product) }}" class="px-2 py-1 rounded-md border border-gray-200 text-[11px]">Detail</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="px-2 py-1 rounded-md border border-gray-200 text-[11px]">Edit</a>
                                <a href="{{ route('admin.products.confirm-delete', $product) }}" class="px-2 py-1 rounded-md border border-red-200 text-[11px] text-red-600">Hapus</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">Belum ada produk.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $products->withQueryString()->links() }}</div>
    </div>
@endsection
