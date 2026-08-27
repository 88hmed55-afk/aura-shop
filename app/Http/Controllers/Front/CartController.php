<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCartItemsQuery()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id());
        }
        return CartItem::where('session_id', session()->getId());
    }

    public function index()
    {
        $cartItems = $this->getCartItemsQuery()->with('product')->get();
        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);

        $couponCode = session('coupon_code');
        $discount = 0;
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                session()->forget('coupon_code');
            }
        }

        $tax = ($subtotal - $discount) * 0.15; // 15% VAT estimate
        $shipping = $subtotal > 500 || $subtotal == 0 ? 0 : 35.00;
        $total = max(0, $subtotal - $discount + $tax + $shipping);

        return view('front.cart', compact('cartItems', 'subtotal', 'discount', 'tax', 'shipping', 'total', 'coupon'));
    }

    public function drawerData()
    {
        $cartItems = $this->getCartItemsQuery()->with('product')->get();
        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);

        $couponCode = session('coupon_code');
        $discount = 0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $tax = ($subtotal - $discount) * 0.15;
        $shipping = $subtotal > 500 || $subtotal == 0 ? 0 : 35.00;
        $total = max(0, $subtotal - $discount + $tax + $shipping);

        $formattedItems = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product ? $item->product->name : '',
                'price' => number_format($item->product->price, 2),
                'image' => $item->product ? $item->product->main_image : '',
                'quantity' => $item->quantity,
                'subtotal' => number_format($item->subtotal, 2),
            ];
        });

        return response()->json([
            'count' => $cartItems->sum('quantity'),
            'items' => $formattedItems,
            'subtotal' => number_format($subtotal, 2),
            'discount' => number_format($discount, 2),
            'tax' => number_format($tax, 2),
            'shipping' => $shipping == 0 ? __('app.free') : number_format($shipping, 2),
            'total' => number_format($total, 2),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->input('quantity', 1);

        if ($product->stock_quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => __('app.low_stock_warning', ['count' => $product->stock_quantity]),
            ], 422);
        }

        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = $userId ? CartItem::where('user_id', $userId) : CartItem::where('session_id', $sessionId);
        $cartItem = $query->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('app.item_added_to_cart'),
            'drawer' => $this->drawerData()->getData(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = $this->getCartItemsQuery()->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => __('app.cart_updated'),
            'drawer' => $this->drawerData()->getData(),
        ]);
    }

    public function remove($id)
    {
        $cartItem = $this->getCartItemsQuery()->findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => __('app.item_removed'),
            'drawer' => $this->drawerData()->getData(),
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        $cartItems = $this->getCartItemsQuery()->with('product')->get();
        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);

        if (!$coupon || !$coupon->isValidForAmount($subtotal)) {
            return response()->json([
                'success' => false,
                'message' => __('app.invalid_coupon'),
            ], 422);
        }

        session(['coupon_code' => $code]);

        return response()->json([
            'success' => true,
            'message' => __('app.coupon_applied'),
            'drawer' => $this->drawerData()->getData(),
        ]);
    }

    public function clearCoupon()
    {
        session()->forget('coupon_code');
        return response()->json([
            'success' => true,
            'drawer' => $this->drawerData()->getData(),
        ]);
    }
}
