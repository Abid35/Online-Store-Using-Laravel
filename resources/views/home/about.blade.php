@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4 ms-auto">
                <p class="lead">{{ $viewData["description"] }}</p>
            </div>
            <div class="col-lg-4 me-auto">
                <p class="lead">{{ $viewData["author"] }}</p>
            </div>
        </div>
        
        <!-- Additional About Content -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">About Our Electronics Store</h3>
                        
                        <p class="mb-4">
                            Welcome to our premier online electronics destination, where innovation meets convenience. Since our establishment, 
                            we have been committed to bringing you the latest and most reliable electronic devices from around the world. 
                            Our passion for technology drives us to carefully curate a diverse collection of products that cater to both 
                            tech enthusiasts and everyday consumers.
                        </p>
                        
                        <p class="mb-4">
                            From cutting-edge smartphones and powerful laptops to smart home appliances and gaming peripherals, we offer 
                            an extensive range of electronics that enhance your digital lifestyle. We partner with leading manufacturers 
                            like Apple, Samsung, Sony, Dell, HP, and many others to ensure our customers have access to genuine, 
                            high-quality products backed by manufacturer warranties.
                        </p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary">Our Mission</h5>
                                <p class="text-muted">
                                    To make advanced technology accessible to everyone by providing competitive prices, 
                                    exceptional customer service, and a seamless online shopping experience.
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary">Why Choose Us?</h5>
                                <ul class="text-muted">
                                    <li>Genuine products with warranties</li>
                                    <li>Fast and secure shipping</li>
                                    <li>24/7 customer support</li>
                                    <li>Competitive pricing</li>
                                    <li>Easy returns and exchanges</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="lead text-primary">
                                Experience the future of electronics shopping with us!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection