<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\CustomOrder;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function checkNew(Request $request)
    {
        $lastCheck = $request->get('since'); // timestamp

        $pendingOrdersCount = CustomOrder::where('status', 'pending')->count();
        $unreadMessagesCount = ContactMessage::where('is_read', false)->count();

        $newOrders = [];
        $newMessages = [];

        if ($lastCheck) {
            $sinceTime = date('Y-m-d H:i:s', (int)$lastCheck);

            $newOrders = CustomOrder::where('status', 'pending')
                ->where('created_at', '>', $sinceTime)
                ->latest()
                ->get(['id', 'order_number', 'customer_name', 'created_at']);

            $newMessages = ContactMessage::where('is_read', false)
                ->where('created_at', '>', $sinceTime)
                ->latest()
                ->get(['id', 'name', 'subject', 'created_at']);
        }

        return response()->json([
            'pending_orders_count' => $pendingOrdersCount,
            'unread_messages_count' => $unreadMessagesCount,
            'total_alerts' => $pendingOrdersCount + $unreadMessagesCount,
            'new_orders' => $newOrders,
            'new_messages' => $newMessages,
            'timestamp' => time(),
        ]);
    }
}
