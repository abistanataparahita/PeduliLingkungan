@extends('layouts.app_public')

@section('title', 'Katalog Produk — Peduli Lingkungan')

@section('content')
<section class="pt-28 pb-16 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
        <span class="section-eyebrow text-leaf">Merchandise</span>
        <h1 class="font-heading text-3xl md:text-4xl mt-2 text-forest">Katalog Produk</h1>

        <form method="GET" class="mt-6 flex flex-wrap gap-3 items-center text-sm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, SKU, deskripsi..." class="rounded-lg border border-gray-200 px-3 py-2 w-56 focus:ring-leaf focus:border-leaf">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="in_stock" value="1" class="rounded border-gray-300" @checked(request('in_stock'))> Stok tersedia
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="on_sale" value="1" class="rounded border-gray-300" @checked(request('on_sale'))> Sedang diskon
            </label>
            <select name="sort" class="rounded-lg border border-gray-200 px-3 py-2 focus:ring-leaf focus:border-leaf" onchange="this.form.submit()">
                <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Terlama</option>
                <option value="name_asc" @selected(request('sort') === 'name_asc')>Nama A–Z</option>
                <option value="name_desc" @selected(request('sort') === 'name_desc')>Nama Z–A</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga terendah</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga tertinggi</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg bg-forest text-cream text-sm font-medium">Filter</button>
        </form>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group block">
                    <div class="aspect-square bg-gray-100 relative">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tanpa gambar</div>
                        @endif
                        @if($product->is_preorder)
                            <span class="absolute top-2 left-2 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $product->is_preorder_closed ? 'bg-gray-600 text-white' : 'bg-violet-600 text-white' }}">
                                {{ $product->is_preorder_closed ? 'Pre Order Ditutup' : 'Pre Order' }}
                            </span>
                        @elseif($product->has_discount)
                            <span class="absolute top-2 right-2 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-600 text-white">Diskon</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h2 class="font-semibold text-forest mt-0.5 line-clamp-2">{{ $product->name }}</h2>
                        <p class="mt-2 text-sm font-medium text-forest">
                            {{ $product->formatted_final_price }}
                            @if($product->has_discount)
                                <span class="text-gray-400 line-through text-xs ml-1">{{ $product->formatted_selling_price }}</span>
                            @endif
                        </p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-moss/80 py-12">Belum ada produk.</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->withQueryString()->links() }}</div>
    </div>
</section>
@endsection
