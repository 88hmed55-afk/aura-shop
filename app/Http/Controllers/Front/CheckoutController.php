<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())->with('product')->get();
        }
        return CartItem::where('session_id', session()->getId())->with('product')->get();
    }

    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', __('app.empty_cart'));
        }

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
        $shipping = $subtotal > 500 ? 0 : 35.00;
        $total = max(0, $subtotal - $discount + $tax + $shipping);

        $savedAddress = auth()->check() ? auth()->user()->addresses()->where('is_default', true)->first() : null;

        return view('front.checkout', compact('cartItems', 'subtotal', 'discount', 'tax', 'shipping', 'total', 'savedAddress'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:card,apple_pay,cash_on_delivery',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', __('app.empty_cart'));
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);
        $couponCode = session('coupon_code');
        $discount = 0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                $coupon->increment('used_count');
            }
        }

        $tax = ($subtotal - $discount) * 0.15;
        $shipping = $subtotal > 500 ? 0 : 35.00;
        $total = max(0, $subtotal - $discount + $tax + $shipping);

        $orderNumber = 'AURA-' . strtoupper(Str::random(3)) . '-' . rand(1000, 9999);

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => [
                'full_name' => $request->customer_name,
                'phone' => $request->customer_phone,
                'address_line_1' => $request->address_line_1,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
            ],
            'shipping_method' => 'express',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cash_on_delivery' ? 'pending' : 'paid',
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping_fee' => $shipping,
            'total' => $total,
            'coupon_code' => $couponCode,
            'notes' => $request->order_notes,
            'tracking_number' => 'TRK-' . rand(100000, 999999),
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_name_en' => $cartItem->product->name_en,
                'product_name_ar' => $cartItem->product->name_ar,
                'price' => $cartItem->product->price,
                'quantity' => $cartItem->quantity,
                'total' => $cartItem->subtotal,
                'image' => $cartItem->product->main_image,
            ]);

            // Decrement inventory stock
            if ($cartItem->product) {
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }
        }

        // Clear cart & coupon
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }
        session()->forget('coupon_code');

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items.product')->firstOrFail();
        return view('front.order-success', compact('order'));
    }

    public function invoice($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items.product')->firstOrFail();
        return view('front.invoice', compact('order'));
    }
}
