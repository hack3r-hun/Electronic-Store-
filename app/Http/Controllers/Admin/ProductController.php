<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\MediaUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'images'])->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']).'-'.Str::random(4);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        unset($data['images']);

        $product = Product::create($data);
        $this->storeImages($product, $request->file('images', []));

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created. Images are live on the store.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        unset($data['images']);

        $product->update($data);
        $this->storeImages($product, $request->file('images', []));

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product saved. Uploaded images are live on the store.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $image) {
            MediaUrl::deleteLocalFile($image->path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        $wasPrimary = $image->is_primary;
        MediaUrl::deleteLocalFile($image->path);
        $image->delete();

        if ($wasPrimary) {
            $next = $product->images()->get()->first(
                fn (ProductImage $img) => MediaUrl::localFileExists($img->path)
            ) ?? $product->images()->first();

            $next?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image removed.');
    }

    protected function storeImages(Product $product, array $images): void
    {
        $files = array_values(array_filter($images));

        if ($files === []) {
            return;
        }

        $product->images()
            ->get()
            ->filter(fn (ProductImage $image) => ! MediaUrl::isLocalPath($image->path))
            ->each(fn (ProductImage $image) => $image->delete());

        $product->images()->update(['is_primary' => false]);

        $existingCount = $product->images()->count();

        foreach ($files as $index => $file) {
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => $index === 0,
                'sort_order' => $existingCount + $index,
            ]);
        }
    }
}
