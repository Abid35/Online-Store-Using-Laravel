@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')
<div class="card">
  <div class="card-header">
    Admin Panel - Home Page
  </div>
  <div class="card-body">
    <h5 class="card-title">Welcome to the Admin Panel</h5>
    <p class="mb-4">Use the sidebar to navigate between the different options and manage your online electronics store efficiently.</p>
    
    <div class="mt-4">
      <h6 class="text-primary">About Your Electronics Store</h6>
      <p class="text-muted">
        Your online electronics store serves as a comprehensive destination for cutting-edge technology and electronic devices. 
        From the latest smartphones and laptops to home appliances and gaming accessories, we offer a curated selection of 
        high-quality electronics from trusted brands. Our platform is designed to provide customers with detailed product 
        specifications, competitive pricing, and reliable customer service to ensure the best shopping experience.
      </p>
      
      <p class="text-muted">
        Through this admin panel, you can efficiently manage your entire inventory, track sales performance, process orders, 
        handle customer inquiries, and monitor the overall health of your electronics business. Stay ahead of the competition 
        by leveraging real-time analytics and streamlined store management tools available at your fingertips.
      </p>
    </div>
  </div>
</div>
@endsection