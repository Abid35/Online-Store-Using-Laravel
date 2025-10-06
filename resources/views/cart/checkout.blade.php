@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewData["products"] as $product)
                            <tr>
                                <td>{{ $product->getName() }}</td>
                                <td>${{ $product->getPrice() }}</td>
                                <td>{{ session('products')[$product->getId()] }}</td>
                                <td>${{ $product->getPrice() * session('products')[$product->getId()] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>${{ $viewData["total"] }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Form with Stripe Elements -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="payment-form" method="POST" action="{{ route('cart.stripe.post') }}">
                        @csrf

                        <!-- Cardholder Name -->
                        <div class="mb-3">
                            <label for="cardholder-name" class="form-label">Cardholder Name</label>
                            <input type="text" id="cardholder-name" class="form-control" 
                                   placeholder="Name" required>
                        </div>

                        <!-- Card Element (Stripe will inject here) -->
                        <div class="mb-3">
                            <label for="card-element" class="form-label">Card Details</label>
                            <div id="card-element" class="form-control" style="height: 40px; padding: 10px;">
                                <!-- Stripe Elements will create input here -->
                            </div>
                            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button id="submit-button" class="btn btn-primary btn-lg" type="submit">
                                <span id="button-text">Pay ${{ $viewData["total"] }}</span>
                                <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status">
                                    <span class="visually-hidden">Processing...</span>
                                </span>
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                Back to Cart
                            </a>
                        </div>
                    </form>

                    <!-- Test Card Info -->
                    {{-- <div class="mt-4 p-3 bg-light rounded">
                        <p class="mb-0 small text-muted">
                            <strong>Test Card for Testing:</strong><br>
                            Card Number: 4242 4242 4242 4242<br>
                            Expiry: Any future date (e.g., 12/28)<br>
                            CVC: Any 3 digits (e.g., 123)<br>
                            ZIP: Any 5 digits (e.g., 12345)
                        </p>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Stripe.js v3 -->
<script src="https://js.stripe.com/v3/"></script>

<script>
// Initialize Stripe
const stripe = Stripe('{{ env('STRIPE_KEY') }}');

// Create card element
const elements = stripe.elements();
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#32325d',
            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#dc3545',
            iconColor: '#dc3545'
        }
    }
});

// Mount card element
cardElement.mount('#card-element');

// Handle real-time validation errors
cardElement.on('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Handle form submission
const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');
const buttonText = document.getElementById('button-text');
const spinner = document.getElementById('spinner');

form.addEventListener('submit', async function(event) {
    event.preventDefault();
    
    // Disable button and show spinner
    submitButton.disabled = true;
    buttonText.classList.add('d-none');
    spinner.classList.remove('d-none');

    // Create payment method
    const {paymentMethod, error} = await stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
        billing_details: {
            name: document.getElementById('cardholder-name').value,
        }
    });

    if (error) {
        // Show error
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
        
        // Re-enable button
        submitButton.disabled = false;
        buttonText.classList.remove('d-none');
        spinner.classList.add('d-none');
    } else {
        // Send payment method to server
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', paymentMethod.id);
        form.appendChild(hiddenInput);
        
        // Submit form
        form.submit();
    }
});
</script>

<style>
/* Stripe Element styles */
#card-element {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 10px;
}

#card-element.StripeElement--focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

#card-element.StripeElement--invalid {
    border-color: #dc3545;
}
</style>
@endsection