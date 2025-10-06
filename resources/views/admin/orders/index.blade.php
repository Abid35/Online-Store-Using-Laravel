@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')

<div class="card mb-4">
  <div class="card-header">
    Manage Orders
  </div>

  <div class="card-body">
    @if($errors->any())
    <ul class="alert alert-danger list-unstyled">
      @foreach($errors->all() as $error)
      <li>- {{ $error }}</li>
      @endforeach
    </ul>
    @endif

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th scope="col">Order ID</th>
            <th scope="col">Customer</th>
            <th scope="col">Date</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
            <th scope="col">Tracking</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($viewData["orders"] as $order)
          <tr>
            <td>#{{ $order->getId() }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->getCreatedAt()->format('M d, Y') }}</td>
            <td>${{ number_format($order->getTotal(), 2) }}</td>
            <td>
              <span class="badge bg-{{ $order->status_color }}">
                {{ $order->status }}
              </span>
            </td>
            <td>
              @if($order->getTrackingNumber())
                <small class="text-primary fw-bold">{{ $order->getTrackingNumber() }}</small>
              @else
                <small class="text-muted fst-italic">Not assigned</small>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.orders.show', $order->getId()) }}" 
                 class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> View/Edit
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted">No orders found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
