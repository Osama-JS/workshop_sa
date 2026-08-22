<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChatSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiChatLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AiChatSession::with(['order', 'user'])->withCount('messages');

        if ($request->has('with_orders')) {
            $query->whereNotNull('order_id');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('user_phone', 'like', "%{$search}%")
                  ->orWhere('visitor_ip', 'like', "%{$search}%");
            });
        }

        $sessions = $query->latest()->paginate(15);
        $totalSessions = AiChatSession::count();
        $totalOrdersCreated = AiChatSession::whereNotNull('order_id')->count();

        return view('admin.ai_logs.index', compact('sessions', 'totalSessions', 'totalOrdersCreated'));
    }

    public function show(AiChatSession $aiLog): View
    {
        $aiLog->load(['messages', 'order']);
        return view('admin.ai_logs.show', compact('aiLog'));
    }

    public function destroy(AiChatSession $aiLog): RedirectResponse
    {
        $aiLog->delete();
        return redirect()->route('admin.ai-logs.index')->with('success', 'تم حذف سجل المحادثة بنجاح!');
    }
}
