@extends('layouts.admin')
@section('title', 'Order Details')
@section('content')

<div class="card mb-4">
  <div class="card-header">
    <h4>Order #{{ $viewData["order"]->getId() }} Details</h4>
  </div>

  <div class="card-body">
    @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif
    <div class="row mb-3">
      <div class="col-md-6">
        <strong>Customer:</strong> {{ $viewData["order"]->user->name }}<br>
        <strong>Email:</strong> {{ $viewData["order"]->user->email ?? 'N/A' }}<br>
        <strong>Date:</strong> {{ $viewData["order"]->getCreatedAt()->format('M d, Y') }}
      </div>
      <div class="col-md-6">
        <strong>Status:</strong>
        <span class="badge bg-{{ $viewData["order"]->status_color }}">
          {{ $viewData["order"]->status }}
        </span><br>
        <strong>Tracking No:</strong>
        @if($viewData["order"]->getTrackingNumber())
          <span class="text-primary fw-bold">{{ $viewData["order"]->getTrackingNumber() }}</span>
        @else
          <span class="text-muted fst-italic">Not assigned</span>
        @endif
      </div>
      <div class="row mb-4">
  <div class="col-md-6">
    <h6>Update Order Status</h6>
    <form method="POST" action="{{ route('admin.orders.updateStatus', $viewData['order']->getId()) }}">
      @csrf
      @method('PUT')
      <div class="input-group">
        <select name="status" class="form-select">
          <option value="Pending" {{ $viewData['order']->status_label == 'Pending' ? 'selected' : '' }}>Pending</option>
          <option value="Processing" {{ $viewData['order']->status_label == 'Processing' ? 'selected' : '' }}>Processing</option>
          <option value="Shipped" {{ $viewData['order']->status_label == 'Shipped' ? 'selected' : '' }}>Shipped</option>
          <option value="Delivered" {{ $viewData['order']->status_label == 'Delivered' ? 'selected' : '' }}>Delivered</option>
        </select>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save"></i> Update
        </button>
      </div>
    </form>
  </div>
</div>

    </div>

    <hr>

    <h5 class="mb-3">Order Items</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th scope="col">Product</th>
            <th scope="col">Price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($viewData["order"]->items as $item)
          <tr>
            <td>{{ $item->product->getName() }}</td>
            <td>${{ number_format($item->getPrice(), 2) }}</td>
            <td>{{ $item->getQuantity() }}</td>
            <td>${{ number_format($item->getPrice() * $item->getQuantity(), 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="text-end mt-3">
      <h5>Total: <span class="text-success">${{ number_format($viewData["order"]->getTotal(), 2) }}</span></h5>
    </div>

    <div class="mt-4">
      <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Orders
      </a>
    </div>
  </div>
</div>

@endsection
