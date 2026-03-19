<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const WHATSAPP_ADMIN = '6281229428356';

    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $q = $request->string('search')->toString();
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->boolean('in_stock')) {
            $query->where('current_stock', '>', 0);
        }

        if ($request->boolean('on_sale')) {
            $query->whereNotNull('discount_price')
                ->whereColumn('discount_price', '<', 'selling_price');
        }

        $sort = $request->string('sort')->toString();
        match ($sort) {
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, selling_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, selling_price) DESC'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $hasPreordered = $user !== null
            && $product->orders()->where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed'])->exists();

        return view('products.show', compact('product', 'hasPreordered'));
    }

    public function storePreorder(Request $request, Product $product): RedirectResponse
    {
        if (! $product->is_preorder || $product->is_preorder_closed) {
            return back()->with('error', 'Pre order untuk produk ini tidak tersedia.');
        }

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user === null) {
            return redirect()->route('login')->with('intended', route('products.show', $product));
        }

        if ($product->orders()->where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->with('error', 'Kamu sudah melakukan pre order untuk produk ini.');
        }

        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:500'],
            'buyer_name' => ['nullable', 'string', 'max:255'],
        ]);

        $remaining = $product->preorder_remaining_quota;
        if ($remaining !== null && $data['quantity'] > $remaining) {
            return back()->withErrors(['quantity' => "Sisa kuota pre order: {$remaining}."])->withInput();
        }

        Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'buyer_name' => $user->name ?? ($data['buyer_name'] ?? 'Pembeli'),
            'whatsapp' => $data['phone'],
            'qty' => $data['quantity'],
            'catatan' => $data['note'] ?? null,
            'status' => 'pending',
        ]);

        $product->increment('preorder_filled', $data['quantity']);

        $buyerName = $user->name ?? ($data['buyer_name'] ?? 'saya');
        $waMessage = self::buildWhatsAppMessage($product, $buyerName, (string) $data['quantity']);
        $waUrl = 'https://wa.me/' . self::WHATSAPP_ADMIN . '?text=' . rawurlencode($waMessage);

        return back()
            ->with('success', 'Pre-order berhasil dikirim. Kamu bisa chat admin via WhatsApp (opsional).')
            ->with('wa_url', $waUrl);
    }

    public static function buildWhatsAppMessage(Product $product, string $buyerName = 'saya', string $quantity = ''): string
    {
        $lines = [
            'Halo Admin, saya ' . $buyerName . ' ingin memesan produk berikut:',
            '',
            '- Nama Produk : ' . $product->name,
            '- SKU         : ' . $product->sku,
            '- Harga       : ' . ($product->has_discount ? $product->formatted_final_price . ' (diskon)' : $product->formatted_final_price),
            '- Stok        : ' . $product->current_stock . ' ' . $product->pcs,
            '- Jumlah      : ' . $quantity,
            '',
            'Apakah produk ini masih tersedia? Saya ingin order.',
        ];
        return implode("\n", $lines);
    }
}
