<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MyAccountController extends Controller
{
    public function orders()
    {
        $viewData = [];
        $viewData["title"] = "My Orders - Online Store";
        $viewData["subtitle"] =  "My Orders";
        $viewData["orders"] = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('myaccount.orders')->with("viewData", $viewData);
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $viewData = [];
        $viewData["title"] = "Order #" . $order->getId() . " - Online Store";
        $viewData["subtitle"] = "Order Details";
        $viewData["order"] = $order;

        return view('myaccount.order_show')->with("viewData", $viewData);
    }

    public function invoice($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Generate PDF
        $pdf = Pdf::loadView('invoices.order_invoice', ['order' => $order]);
        
        // Download PDF
        return $pdf->download('invoice-' . $order->getId() . '.pdf');
    }
}