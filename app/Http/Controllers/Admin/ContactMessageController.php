<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $message)
    {
        // Auto mark as read if not already read
        if (!$message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function toggleRead(ContactMessage $message)
    {
        $message->update([
            'is_read' => !$message->is_read,
            'read_at' => !$message->is_read ? now() : null,
        ]);

        return back()->with('success', 'تم تغيير حالة قراءة الرسالة.');
    }

    public function saveReplyNotes(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'reply_notes' => ['required', 'string', 'max:5000'],
        ]);

        $message->update([
            'reply_notes' => $validated['reply_notes'],
            'replied_at' => now(),
        ]);

        return back()->with('success', 'تم حفظ ملاحظات وتوثيق الرد على العميل.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'تم حذف رسالة التواصل بنجاح.');
    }
}
