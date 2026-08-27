<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('front.pages.about');
    }

    public function contact()
    {
        return view('front.pages.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        return redirect()->back()->with('success', app()->getLocale() === 'ar' ? 'تم استلام رسالتك بنجاح وسنقوم بالرد عليك في أقرب وقت!' : 'Your message has been received. Our concierge team will reach out to you shortly.');
    }

    public function faq()
    {
        return view('front.pages.faq');
    }

    public function policies()
    {
        return view('front.pages.policies');
    }
}
