@extends('admin.layouts.dashboard')

@section('page_title', 'Pesanan')

@section('content')
    <div class="bg-white rounded-2xl p-4 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div>
                <h2 class="text-sm font-semibold text-forest">Daftar Pesanan</h2>
                <p class="text-xs text-gray-500">Kelola pre-order & pesanan masuk.</p>
            </div>

            <form method="GET" class="flex items-center gap-2">
                <label class="text-xs text-gray-600">Status</label>
                <select name="status" class="rounded-xl border border-gray-200 px-3 py-2 text-xs">
                    <option value="" {{ $status === 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="px-3 py-2 rounded-xl bg-forest text-white text-xs hover:bg-forest/90">
                    Terapkan
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                <tr class="text-gray-500 text-[11px] border-b">
                    <th class="py-2 text-left">No</th>
                    <th class="py-2 text-left">Nama Buyer</th>
                    <th class="py-2 text-left">Produk</th>
                    <th class="py-2 text-left">Qty</th>
                    <th class="py-2 text-left">WhatsApp</th>
                    <th class="py-2 text-left">Catatan</th>
                    <th class="py-2 text-left">Status</th>
                    <th class="py-2 text-left">Tanggal</th>
                    <th class="py-2 text-left">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr class="border-b last:border-0 align-top">
                        <td class="py-3 text-gray-600">
                            {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                        </td>
                        <td class="py-3 text-forest font-medium">
                            {{ $order->buyer_name }}
                        </td>
                        <td class="py-3 text-gray-700">
                            {{ $order->product?->name ?? '-' }}
                        </td>
                        <td class="py-3 text-gray-700">
                            {{ $order->qty }}
                        </td>
                        <td class="py-3 text-gray-700 whitespace-nowrap">
                            {{ $order->whatsapp }}
                        </td>
                        <td class="py-3 text-gray-600 max-w-[280px]">
                            {{ $order->catatan ?? '-' }}
                        </td>
                        <td class="py-3">
                            <span class="inline-flex px-2 py-1 rounded-full text-[11px] font-semibold
                                @class([
                                    'bg-amber-100 text-amber-800' => $order->status === 'pending',
                                    'bg-sky-100 text-sky-800' => $order->status === 'confirmed',
                                    'bg-emerald-100 text-emerald-800' => $order->status === 'selesai',
                                    'bg-rose-100 text-rose-800' => $order->status === 'dibatalkan',
                                ])
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-600 whitespace-nowrap">
                            {{ $order->created_at?->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3">
                            <div class="flex flex-col gap-2 min-w-[180px]">
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-xl border border-gray-200 px-2 py-1.5 text-[11px]">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>confirmed</option>
                                        <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>selesai</option>
                                        <option value="dibatalkan" {{ $order->status === 'dibatalkan' ? 'selected' : '' }}>dibatalkan</option>
                                    </select>
                                    <button type="submit" class="px-2 py-1.5 rounded-xl bg-gray-900 text-white text-[11px] hover:bg-gray-800">
                                        Ubah
                                    </button>
                                </form>

                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 px-2 py-1.5 rounded-xl border border-gray-200 text-[11px] hover:bg-gray-50 text-red-700"
                                    @click="$dispatch('open-confirm', { url: '{{ route('admin.orders.destroy', $order) }}' })"
                                >
                                    <x-icons name="trash" class="w-4 h-4" />
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-6 text-center text-gray-500">
                            Belum ada pesanan.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection

