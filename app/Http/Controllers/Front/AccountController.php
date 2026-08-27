<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $totalOrdersCount = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total');

        return view('front.account.dashboard', compact('user', 'orders', 'totalOrdersCount', 'totalSpent'));
    }

    public function orders()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->with('items.product')->latest()->paginate(10);
        return view('front.account.orders', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $user = auth()->user();
        $order = Order::where('user_id', $user->id)->where('order_number', $orderNumber)->with('items.product')->firstOrFail();
        return view('front.account.order-detail', compact('order'));
    }

    public function addresses()
    {
        $user = auth()->user();
        $addresses = $user->addresses;
        return view('front.account.addresses', compact('addresses'));
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        $user = auth()->user();

        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'is_default' => $request->boolean('is_default') || $user->addresses()->count() === 0,
        ]);

        return redirect()->back()->with('success', 'Address saved successfully.');
    }

    public function deleteAddress($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();
        return redirect()->back()->with('success', 'Address removed.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
