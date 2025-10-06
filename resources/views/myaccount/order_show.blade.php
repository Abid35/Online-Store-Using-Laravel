@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order #{{ $viewData["order"]->getId() }}</h5>
                </div>
                <div class="card-body">
                    <!-- Order Status Timeline -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Order Status</h6>
                            <div class="order-status-timeline">
                                <div class="progress" style="height: 30px;">
                                    @php
                                        $statuses = ['pending', 'Processing', 'Shipped', 'Delivered'];
                                        $currentIndex = array_search($viewData["order"]->getStatus(), $statuses);
                                        $percentage = ($currentIndex + 1) / count($statuses) * 100;
                                    @endphp
                                    <div class="progress-bar bg-{{ $viewData['order']->status_color }}" 
                                         role="progressbar" 
                                         style="width: {{ $percentage }}%"
                                         aria-valuenow="{{ $percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $viewData["order"]->status }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-{{ $currentIndex >= 0 ? 'success' : 'muted' }}">Pending</small>
                                    <small class="text-{{ $currentIndex >= 1 ? 'success' : 'muted' }}">Processing</small>
                                    <small class="text-{{ $currentIndex >= 2 ? 'success' : 'muted' }}">Shipped</small>
                                    <small class="text-{{ $currentIndex >= 3 ? 'success' : 'muted' }}">Delivered</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Order Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td>{{ $viewData["order"]->getCreatedAt()->format('F d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $viewData['order']->status_color }}">
                                           {{ $viewData["order"]->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>${{ number_format($viewData["order"]->getTotal(), 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Tracking Number:</strong></td>
                                    <td class="text-primary">{{ $viewData["order"]->getTrackingNumber() ?? 'Not assigned yet' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Order Items</h6>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($viewData["order"]->items as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('product.show', $item->product->getId()) }}">
                                                {{ $item->product->getName() }}
                                            </a>
                                        </td>
                                        <td>${{ number_format($item->getPrice(), 2) }}</td>
                                        <td>{{ $item->getQuantity() }}</td>
                                        <td>${{ number_format($item->getPrice() * $item->getQuantity(), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>${{ number_format($viewData["order"]->getTotal(), 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('myaccount.orders') }}" class="btn btn-secondary">
                                Back to Orders
                            </a>
                            <a href="{{ route('myaccount.order.invoice', $viewData['order']->getId()) }}" 
                               class="btn btn-success" target="_blank">
                                <i class="bi bi-download"></i> Download Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection