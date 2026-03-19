<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function create(Product $product): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('status', 'Login dulu untuk memesan produk.');
        }

        return view('orders.create', compact('product'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'buyer_name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'qty' => ['required', 'integer', 'min:1'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'buyer_name' => $data['buyer_name'],
            'whatsapp' => $data['whatsapp'],
            'qty' => $data['qty'],
            'catatan' => $data['catatan'] ?? null,
            'status' => 'pending',
            'is_read' => false,
        ]);

        $unitPrice = (int) round((float) $product->final_price);
        $total = $unitPrice * (int) $data['qty'];

        $waMessage = urlencode(
            "Halo Admin Peduli Lingkungan 👋\n\n" .
            "Saya ingin memesan produk:\n" .
            "━━━━━━━━━━━━━━━\n" .
            "📦 Produk  : {$product->name}\n" .
            "🔢 Jumlah  : {$data['qty']} pcs\n" .
            "💰 Total   : Rp " . number_format($total, 0, ',', '.') . "\n" .
            "👤 Nama    : {$data['buyer_name']}\n" .
            "📱 WA      : {$data['whatsapp']}\n" .
            ($data['catatan'] ? "📝 Catatan : {$data['catatan']}\n" : '') .
            "━━━━━━━━━━━━━━━\n" .
            "Mohon konfirmasi pesanan saya. Terima kasih! 🙏\n\n" .
            "Order ID: #{$order->id}"
        );

        $waNumber = preg_replace('/[^0-9]/', '', setting('wa_phone', '6281229428356'));
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        return redirect("https://wa.me/{$waNumber}?text={$waMessage}");
    }

    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('product');

        return view('orders.show', compact('order'));
    }
}

