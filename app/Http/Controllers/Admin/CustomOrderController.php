<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Service;
use Illuminate\Http\Request;

class CustomOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomOrder::with('service');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $services = Service::all();

        // Status Counters for quick KPI pills
        $counts = [
            'all' => CustomOrder::count(),
            'pending' => CustomOrder::where('status', 'pending')->count(),
            'in_review' => CustomOrder::where('status', 'in_review')->count(),
            'in_progress' => CustomOrder::where('status', 'in_progress')->count(),
            'completed' => CustomOrder::where('status', 'completed')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'services', 'counts'));
    }

    public function show(CustomOrder $order)
    {
        $order->load('service');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, CustomOrder $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_review,contacted,in_progress,completed,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $order->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $order->admin_notes,
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب والملاحظات بنجاح.');
    }

    public function destroy(CustomOrder $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'تم حذف الطلب بنجاح.');
    }
}
