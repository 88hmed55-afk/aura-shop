<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index()
    {
        $monthlyRevenue = Order::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(id) as total_orders')
        )
        ->where('payment_status', 'paid')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();

        $topSellingProducts = Product::orderBy('reviews_count', 'desc')->take(10)->get();

        return view('admin.reports.index', compact('monthlyRevenue', 'topSellingProducts'));
    }
}
