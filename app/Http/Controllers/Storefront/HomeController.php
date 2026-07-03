<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('activeProducts')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $latestProducts = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('storefront.home', compact('featuredProducts', 'categories', 'latestProducts'));
    }
}
