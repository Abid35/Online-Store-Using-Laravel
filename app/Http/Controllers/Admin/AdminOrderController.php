<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $viewData = [];
        $viewData["title"] = "Admin - Manage Orders";
        $viewData["orders"] = Order::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index')->with("viewData", $viewData);
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        $viewData = [];
        $viewData["title"] = "Admin - Order #" . $order->getId();
        $viewData["order"] = $order;
        $viewData["statuses"] = Order::getStatuses();

        return view('admin.orders.show')->with("viewData", $viewData);
    }

      public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Pending,Processing,Shipped,Delivered',
    ]);

    $order = Order::findOrFail($id);
    $order->status = $request->input('status');
    $order->save();

    return redirect()->back()->with('success', 'Order status updated successfully');
}


}