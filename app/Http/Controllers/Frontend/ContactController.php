<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:5', 'max:5000'],
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? (app()->getLocale() === 'ar' ? 'استفسار عام من الموقع' : 'General Inquiry'),
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'is_read' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => app()->getLocale() === 'ar' 
                    ? 'شكراً لتواصلك معنا! تم استلام رسالتك بنجاح وسيقوم فريقنا بالرد عليك في أقرب وقت.' 
                    : 'Thank you for reaching out! Your message has been received and our team will respond shortly.'
            ]);
        }

        return back()->with('success', app()->getLocale() === 'ar' 
            ? 'شكراً لتواصلك معنا! تم استلام رسالتك بنجاح وسيقوم فريقنا بالرد عليك في أقرب وقت.' 
            : 'Thank you for reaching out! Your message has been received.');
    }
}
