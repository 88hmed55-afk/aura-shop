<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }} - AURA Luxury</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: '{{ app()->getLocale() === 'ar' ? 'Cairo' : 'Outfit' }}', sans-serif; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-xl border border-gray-200">
        <!-- Print Button Header -->
        <div class="no-print flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
            <a href="{{ route('home') }}" class="text-xs text-amber-600 font-bold uppercase">&larr; Back to Store</a>
            <button onclick="window.print()" class="px-6 py-2 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-400">
                Print Official Invoice
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="flex justify-between items-start mb-8 pb-8 border-b border-gray-200">
            <div>
                <h1 class="text-3xl font-extrabold text-amber-600 tracking-wider">AURA</h1>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Luxury & Modern Commerce</p>
                <p class="text-xs text-gray-400 mt-2">King Fahd Road, Olaya Tower, Riyadh, Saudi Arabia</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold text-gray-400 uppercase">OFFICIAL TAX INVOICE</span>
                <h2 class="text-lg font-mono font-bold text-gray-900 mt-1">#{{ $order->order_number }}</h2>
                <p class="text-xs text-gray-500 mt-1">Date: {{ $order->created_at->format('M d, Y') }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase rounded-full">
                    Payment Status: {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Billing & Shipping Details -->
        <div class="grid grid-cols-2 gap-8 mb-8 text-xs">
            <div>
                <h4 class="font-bold text-gray-400 uppercase tracking-wider mb-2">Billed To:</h4>
                <p class="font-bold text-sm text-gray-900">{{ $order->customer_name }}</p>
                <p class="text-gray-600 mt-1">{{ $order->customer_email }}</p>
                <p class="text-gray-600">{{ $order->customer_phone }}</p>
            </div>
            <div>
                <h4 class="font-bold text-gray-400 uppercase tracking-wider mb-2">Shipping Address:</h4>
                @php $addr = $order->shipping_address; @endphp
                <p class="text-gray-800 font-semibold">{{ $addr['address_line_1'] ?? '' }}</p>
                <p class="text-gray-600">{{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }}</p>
                <p class="text-gray-600">{{ $addr['country'] ?? '' }}</p>
            </div>
        </div>

        <!-- Line Items Table -->
        <table class="w-full text-left text-xs mb-8">
            <thead>
                <tr class="bg-gray-50 text-gray-400 font-bold uppercase border-y border-gray-200">
                    <th class="py-3 px-4">Item Description</th>
                    <th class="py-3 px-4 text-center">Qty</th>
                    <th class="py-3 px-4 text-right">Unit Price</th>
                    <th class="py-3 px-4 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <tr>
                        <td class="py-4 px-4 font-bold text-gray-900">{{ $item->product_name }}</td>
                        <td class="py-4 px-4 text-center font-mono">{{ $item->quantity }}</td>
                        <td class="py-4 px-4 text-right font-mono">${{ number_format($item->price, 2) }}</td>
                        <td class="py-4 px-4 text-right font-mono font-bold">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Breakdown -->
        <div class="flex justify-end mb-8">
            <div class="w-64 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span class="font-mono">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>Discount:</span>
                        <span class="font-mono">-${{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-gray-600">
                    <span>VAT (15%):</span>
                    <span class="font-mono">${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Express Shipping:</span>
                    <span class="font-mono">${{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="flex justify-between font-extrabold text-sm text-gray-900 pt-2 border-t border-gray-200">
                    <span>Total Paid:</span>
                    <span class="text-amber-600 font-mono text-base">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-[10px] text-gray-400 border-t border-gray-100 pt-6">
            <p>Thank you for choosing AURA. For any concierge inquiries, please contact concierge@aura.com</p>
        </div>
    </div>
</body>
</html>
