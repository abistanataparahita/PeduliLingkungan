<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if (in_array($request->get('preorder'), ['1', '0'], true)) {
            $query->where('is_preorder', (bool) $request->get('preorder'));
        }

        $products = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request, ImageUploadService $uploader): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'max_stock' => ['nullable', 'integer', 'min:0'],
            'pcs' => ['required', 'string', 'max:50'],
            'is_preorder' => ['boolean'],
            'preorder_estimate' => ['nullable', 'string', 'max:255'],
            'preorder_open_until' => ['nullable', 'date'],
            'preorder_quota' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_preorder'] = $request->boolean('is_preorder');

        if ($request->hasFile('image')) {
            $data['image'] = $uploader->upload($request->file('image'), 'product_images', ImageUploadService::PRODUCTS);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function show(Product $product): View
    {
        $product->load(['preorders.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product, ImageUploadService $uploader): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'max_stock' => ['nullable', 'integer', 'min:0'],
            'pcs' => ['required', 'string', 'max:50'],
            'is_preorder' => ['boolean'],
            'preorder_estimate' => ['nullable', 'string', 'max:255'],
            'preorder_open_until' => ['nullable', 'date'],
            'preorder_quota' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_preorder'] = $request->boolean('is_preorder');

        if ($request->boolean('remove_image')) {
            if ($product->image) {
                $uploader->delete($product->image);
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($product->image) {
                $uploader->delete($product->image);
            }
            $data['image'] = $uploader->upload($request->file('image'), 'product_images', ImageUploadService::PRODUCTS);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function confirmDelete(Product $product): View
    {
        return view('admin.products.confirm-delete', compact('product'));
    }

    public function destroy(Product $product, ImageUploadService $uploader): RedirectResponse
    {
        if ($product->image) {
            $uploader->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
