<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->getId() }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #0d6efd;
            margin: 0;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            color: white;
        }
        .status-pending { background-color: #ffc107; }
        .status-processing { background-color: #0dcaf0; }
        .status-shipped { background-color: #0d6efd; }
        .status-delivered { background-color: #198754; }
        .status-cancelled { background-color: #dc3545; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        .items-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Online Store</h1>
        <p>Invoice / Receipt</p>
    </div>

    <!-- Invoice Information -->
    <div class="invoice-info">
        <table>
            <tr>
                <td style="width: 50%;">
                    <strong>Invoice Number:</strong> #{{ $order->getId() }}<br>
                    <strong>Date:</strong> {{ $order->getCreatedAt()->format('F d, Y') }}<br>
                    <strong>Status:</strong> 
                    <span class="status-badge status-{{ $order->getStatus() }}">
                        {{ $order->status_label }}
                    </span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Customer:</strong> {{ $order->user->name }}<br>
                    <strong>Email:</strong> {{ $order->user->email }}<br>
                    @if($order->getTrackingNumber())
                    <strong>Tracking:</strong> {{ $order->getTrackingNumber() }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Order Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Product</th>
                <th style="width: 15%;" class="text-right">Price</th>
                <th style="width: 15%;" class="text-right">Quantity</th>
                <th style="width: 20%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->getName() }}</td>
                <td class="text-right">${{ number_format($item->getPrice(), 2) }}</td>
                <td class="text-right">{{ $item->getQuantity() }}</td>
                <td class="text-right">${{ number_format($item->getPrice() * $item->getQuantity(), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Amount:</td>
                <td class="text-right">${{ number_format($order->getTotal(), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your purchase!</p>
        <p>For any questions, please contact us at support@onlinestore.com</p>
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>