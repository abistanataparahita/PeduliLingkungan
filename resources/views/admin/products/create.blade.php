@extends('admin.layouts.dashboard')

@section('page_title', 'Tambah Produk')

@push('head')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.flatpickr) {
                flatpickr('[data-datepicker]', { dateFormat: 'Y-m-d' });
            }
        });
    </script>
@endpush

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-base font-semibold text-forest">Tambah Produk</h1>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-emerald-700 hover:underline">← Daftar Produk</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm p-4 space-y-4">
        @csrf

        <div class="grid md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full text-xs border rounded-md px-3 py-2" required>
                    @error('name')<p class="text-[10px] text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="w-full text-xs border rounded-md px-3 py-2" required>
                    @error('sku')<p class="text-[10px] text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full text-xs border rounded-md px-3 py-2">{{ old('description') }}</textarea>
                    @error('description')<p class="text-[10px] text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Beli</label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price', 0) }}" step="0.01" min="0" class="w-full text-xs border rounded-md px-3 py-2" required>
                        @error('purchase_price')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Jual</label>
                        <input type="number" name="selling_price" value="{{ old('selling_price', 0) }}" step="0.01" min="0" class="w-full text-xs border rounded-md px-3 py-2" required>
                        @error('selling_price')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Diskon</label>
                        <input type="number" name="discount_price" value="{{ old('discount_price') }}" step="0.01" min="0" class="w-full text-xs border rounded-md px-3 py-2" placeholder="Opsional">
                        @error('discount_price')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Gambar (opsional, max 2MB)</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif" class="w-full text-xs border rounded-md px-3 py-2">
                    @error('image')<p class="text-[10px] text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="space-y-3">
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Stok Saat Ini</label>
                        <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" min="0" class="w-full text-xs border rounded-md px-3 py-2" required>
                        @error('current_stock')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Stok Min</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 0) }}" min="0" class="w-full text-xs border rounded-md px-3 py-2" required>
                        @error('min_stock')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Stok Maks</label>
                        <input type="number" name="max_stock" value="{{ old('max_stock') }}" min="0" class="w-full text-xs border rounded-md px-3 py-2" placeholder="Opsional">
                        @error('max_stock')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan (pcs)</label>
                    <input type="text" name="pcs" value="{{ old('pcs', 'pcs') }}" class="w-full text-xs border rounded-md px-3 py-2" required>
                    @error('pcs')<p class="text-[10px] text-red-600 mt-0.5">{{ $message }}</p>@enderror
                </div>

                <div class="border border-gray-200 rounded-lg p-3 space-y-3">
                    <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700">
                        <input type="checkbox" name="is_preorder" value="1" class="rounded border-gray-300" @checked(old('is_preorder'))>
                        Pre Order
                    </label>
                    <div data-preorder-fields class="space-y-2">
                        <div>
                            <label class="block text-[11px] text-gray-600 mb-0.5">Estimasi ketersediaan</label>
                            <input type="text" name="preorder_estimate" value="{{ old('preorder_estimate') }}" class="w-full text-xs border rounded-md px-3 py-2" placeholder="Contoh: Maret 2025">
                            @error('preorder_estimate')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-600 mb-0.5">Batas waktu pre order</label>
                            <input type="text" name="preorder_open_until" value="{{ old('preorder_open_until') }}" data-datepicker class="w-full text-xs border rounded-md px-3 py-2">
                            @error('preorder_open_until')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-600 mb-0.5">Kuota maksimal pre order</label>
                            <input type="number" name="preorder_quota" value="{{ old('preorder_quota') }}" min="0" class="w-full text-xs border rounded-md px-3 py-2" placeholder="Opsional">
                            @error('preorder_quota')<p class="text-[10px] text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-forest text-cream text-xs font-semibold">Simpan Produk</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-xs">Batal</a>
        </div>
    </form>
@endsection
