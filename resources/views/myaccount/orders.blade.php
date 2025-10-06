@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if($viewData["orders"]->isEmpty())
                <div class="alert alert-info text-center">
                    <h4>No orders yet</h4>
                    <p>You haven't placed any orders yet.</p>
                    <a href="{{ route('product.index') }}" class="btn btn-primary">Start Shopping</a>
                </div>
            @else
                @foreach($viewData["orders"] as $order)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Order #{{ $order->getId() }}</strong>
                            <span class="ms-3 text-muted">{{ $order->getCreatedAt()->format('F d, Y') }}</span>
                        </div>
                        <div>
                            <span class="badge bg-{{ $order->status_color }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tracking Number:</strong>
                                <span class="text-primary">{{ $order->getTrackingNumber() ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 text-end">
                                <strong>Total:</strong> ${{ number_format($order->getTotal(), 2) }}
                            </div>
                        </div>

                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->getName() }}</td>
                                    <td>${{ number_format($item->getPrice(), 2) }}</td>
                                    <td>{{ $item->getQuantity() }}</td>
                                    <td>${{ number_format($item->getPrice() * $item->getQuantity(), 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('myaccount.order.show', $order->getId()) }}" 
                               class="btn btn-sm btn-outline-primary">
                                View Details
                            </a>
                            <a href="{{ route('myaccount.order.invoice', $order->getId()) }}" 
                               class="btn btn-sm btn-outline-success" target="_blank">
                                Download Invoice
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection