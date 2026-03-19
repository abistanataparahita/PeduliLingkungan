@extends('admin.layouts.dashboard')

@section('page_title', 'Konfirmasi Hapus Produk')

@section('content')
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm p-6">
        <h1 class="text-base font-semibold text-forest mb-2">Hapus Produk?</h1>
        <p class="text-xs text-gray-600 mb-4">Produk yang dihapus tidak dapat dikembalikan. Gambar produk juga akan dihapus dari server.</p>
        <div class="flex items-center gap-3 mb-6">
            @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg">
            @endif
            <div>
                <p class="font-medium text-forest">{{ $product->name }}</p>
                <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
            </div>
        </div>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-semibold">Ya, Hapus</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-xs">Batal</a>
        </form>
    </div>
@endsection
