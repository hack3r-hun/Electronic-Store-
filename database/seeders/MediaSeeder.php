<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaSeeder extends Seeder
{
    /** Unsplash images — free for demo/development use */
    private array $categoryImages = [
        'Bulbs & Lighting' => 'https://images.unsplash.com/photo-1565814636199-ae8133055c1c?w=600&h=400&fit=crop',
        'Capacitors & Components' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&h=400&fit=crop',
        'Wiring & Cables' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=600&h=400&fit=crop',
        'Pipes & Fittings' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=600&h=400&fit=crop',
        'Bolts, Nuts & Fasteners' => 'https://images.unsplash.com/photo-1605152275992-4482bcb413c0?w=600&h=400&fit=crop',
        'Tools & Accessories' => 'https://images.unsplash.com/photo-1581147036325-d8574b9dcee7?w=600&h=400&fit=crop',
    ];

    private array $productImages = [
        'LED Bulb' => 'https://images.unsplash.com/photo-1565814636199-ae8133055c1c?w=800&h=800&fit=crop',
        'Tube Light' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&h=800&fit=crop',
        'Capacitor' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=800&fit=crop',
        'Copper Wire' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&h=800&fit=crop',
        'Cable' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=800&fit=crop',
        'PVC Pipe' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800&h=800&fit=crop',
        'PVC Elbow' => 'https://images.unsplash.com/photo-1585704032915-c3400ca193e7?w=800&h=800&fit=crop',
        'Bolt' => 'https://images.unsplash.com/photo-1605152275992-4482bcb413c0?w=800&h=800&fit=crop',
        'Nut' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800&h=800&fit=crop',
        'Tape' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=800&h=800&fit=crop',
        'Switch' => 'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?w=800&h=800&fit=crop',
        'Pliers' => 'https://images.unsplash.com/photo-1581147036325-d8574b9dcee7?w=800&h=800&fit=crop',
        'MCB' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&h=800&fit=crop',
        'Fan Capacitor' => 'https://images.unsplash.com/photo-1558449028-b1690613f4e3?w=800&h=800&fit=crop',
        'default' => 'https://images.unsplash.com/photo-1581092162384-8987c1d64718?w=800&h=800&fit=crop',
    ];

    private array $teamImages = [
        'ahmed-khan' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop&crop=face',
        'bilal-hussain' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop&crop=face',
        'usman-ali' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop&crop=face',
        'sara-malik' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop&crop=face',
    ];

    public function run(): void
    {
        Storage::disk('public')->makeDirectory('products');
        Storage::disk('public')->makeDirectory('categories');
        Storage::disk('public')->makeDirectory('team');
        Storage::disk('public')->makeDirectory('about');

        $this->download('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=900&h=600&fit=crop', 'about/store.jpg');

        foreach ($this->categoryImages as $name => $url) {
            $category = Category::where('name', $name)->whereNull('parent_id')->first();
            if (! $category) {
                continue;
            }

            $filename = 'categories/'.Str::slug($name).'.jpg';
            if ($this->download($url, $filename)) {
                $category->update(['image' => $filename]);
            }
        }

        foreach (Product::all() as $product) {
            $url = $this->resolveProductUrl($product->name);
            $filename = 'products/'.Str::slug($product->sku).'.jpg';

            if ($this->download($url, $filename)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $filename,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        foreach ($this->teamImages as $slug => $url) {
            $this->download($url, "team/{$slug}.jpg");
        }
    }

    private function resolveProductUrl(string $name): string
    {
        foreach ($this->productImages as $keyword => $url) {
            if ($keyword !== 'default' && stripos($name, $keyword) !== false) {
                return $url;
            }
        }

        return $this->productImages['default'];
    }

    private function download(string $url, string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return true;
        }

        try {
            $response = Http::timeout(60)
                ->withHeaders(['User-Agent' => 'ElectroMart-Seeder/1.0'])
                ->get($url);

            if ($response->successful() && strlen($response->body()) > 1000) {
                Storage::disk('public')->put($path, $response->body());

                return true;
            }
        } catch (\Throwable) {
            // Skip failed downloads — fallback placeholders remain
        }

        return false;
    }
}
