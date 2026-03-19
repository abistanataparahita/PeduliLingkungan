<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $query = Order::query()->with('product');
        if (in_array($status, ['pending', 'confirmed', 'selesai', 'dibatalkan'], true)) {
            $query->where('status', $status);
        } else {
            $status = 'semua';
        }

        $orders = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        // Saat admin buka halaman orders → tandai semua pending sebagai read
        Order::query()->where('status', 'pending')->where('is_read', false)->update(['is_read' => true]);

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,selesai,dibatalkan'],
        ]);

        $order->update([
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}
