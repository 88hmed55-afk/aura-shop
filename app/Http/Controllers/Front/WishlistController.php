<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $wishlists = Wishlist::where('user_id', $user->id)->with('product.category')->latest()->get();

        return view('front.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'redirect' => route('login'),
                'message' => __('app.login_required') ?? 'Please sign in to save items to your wishlist.',
            ], 401);
        }

        $userId = auth()->id();
        $productId = $request->product_id;

        $existing = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = __('app.item_removed');
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $added = true;
            $message = __('app.wishlist_added') ?? 'Added to wishlist.';
        }

        $count = Wishlist::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'added' => $added,
            'count' => $count,
            'message' => $message,
        ]);
    }
}
