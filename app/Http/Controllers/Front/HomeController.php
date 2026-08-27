<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::where('is_featured', true)
            ->withCount('products')
            ->take(6)
            ->get();

        if ($featuredCategories->isEmpty()) {
            $featuredCategories = Category::withCount('products')->take(6)->get();
        }

        $heroProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $newArrivals = Product::where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $trendingProducts = Product::where('is_active', true)
            ->orderBy('reviews_count', 'desc')
            ->take(8)
            ->get();

        $flashSaleProduct = Product::where('is_active', true)
            ->whereNotNull('compare_at_price')
            ->orderByRaw('(compare_at_price - price) DESC')
            ->first();

        return view('front.home', compact(
            'featuredCategories',
            'heroProducts',
            'newArrivals',
            'trendingProducts',
            'flashSaleProduct'
        ));
    }
}
