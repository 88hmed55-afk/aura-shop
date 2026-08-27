<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $lowStockCount = Product::where('stock_quantity', '<=', 5)->count();

        $recentOrders = Order::latest()->take(6)->get();

        $topProducts = Product::where('is_active', true)
            ->orderBy('reviews_count', 'desc')
            ->take(5)
            ->get();

        // Monthly sales data for chart
        $monthlySales = Order::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(id) as orders_count')
        )
        ->where('payment_status', 'paid')
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->take(6)
        ->get();

        // Category distribution for chart
        $categoryStats = Category::withCount('products')->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'lowStockCount',
            'recentOrders',
            'topProducts',
            'monthlySales',
            'categoryStats'
        ));
    }
}
