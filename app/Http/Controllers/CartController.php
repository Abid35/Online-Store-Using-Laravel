<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $total = 0;
        $productsInCart = [];

        $productsInSession = $request->session()->get("products");
        if ($productsInSession) {
            $productsInCart = Product::findMany(array_keys($productsInSession));
            $total = Product::sumPricesByQuantities($productsInCart, $productsInSession);
        }

        $viewData = [];
        $viewData["title"] = "Cart - Online Store";
        $viewData["subtitle"] =  "Shopping Cart";
        $viewData["total"] = $total;
        $viewData["products"] = $productsInCart;
        return view('cart.index')->with("viewData", $viewData);
    }

    public function add(Request $request, $id)
    {
        $products = $request->session()->get("products");
        $products[$id] = $request->input('quantity');
        $request->session()->put('products', $products);

        return redirect()->route('cart.index');
    }

    public function delete(Request $request)
    {
        $request->session()->forget('products');
        return back();
    }

    // Show Stripe checkout page
    public function checkout(Request $request)
    {
        $total = 0;
        $productsInCart = [];

        $productsInSession = $request->session()->get("products");
        if (!$productsInSession) {
            return redirect()->route('cart.index');
        }

        $productsInCart = Product::findMany(array_keys($productsInSession));
        $total = Product::sumPricesByQuantities($productsInCart, $productsInSession);

        $viewData = [];
        $viewData["title"] = "Checkout - Online Store";
        $viewData["subtitle"] = "Payment";
        $viewData["total"] = $total;
        $viewData["products"] = $productsInCart;
        
        return view('cart.checkout')->with("viewData", $viewData);
    }

    // Process Stripe payment with Payment Method (Stripe Elements)
    public function stripePost(Request $request)
    {
        $productsInSession = $request->session()->get("products");
        
        if (!$productsInSession) {
            return redirect()->route('cart.index');
        }

        // Calculate total
        $productsInCart = Product::findMany(array_keys($productsInSession));
        $total = Product::sumPricesByQuantities($productsInCart, $productsInSession);

        try {
            // Set Stripe API Key
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            // Get payment method from Stripe Elements
            $paymentMethodId = $request->input('payment_method');

            // Create Payment Intent
            $paymentIntent = Stripe\PaymentIntent::create([
                'amount' => $total * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('cart.index'), // Required for some payment methods
                'description' => 'Order payment from Online Store',
                'metadata' => [
                    'user_id' => Auth::id(), // Changed from getId()
                    'order_date' => now()->toDateString()
                ]
            ]);

            // Check if payment was successful
            if ($paymentIntent->status == 'succeeded') {
                // Create order
                $order = new Order();
                $order->setUserId(Auth::id());
                $order->setTotal(0);
                $order->setStatus(Order::STATUS_PENDING); // Add this
                $order->setTrackingNumber(Order::generateTrackingNumber()); // Add this
                $order->save();

                // Create order items
                foreach ($productsInCart as $product) {
                    $quantity = $productsInSession[$product->getId()];
                    $item = new Item();
                    $item->setQuantity($quantity);
                    $item->setPrice($product->getPrice());
                    $item->setProductId($product->getId());
                    $item->setOrderId($order->getId());
                    $item->save();
                }
                
                $order->setTotal($total);
                $order->save();

                // Clear cart
                $request->session()->forget('products');

                Session::flash('success', 'Payment successful! Your order has been placed.');

                $viewData = [];
                $viewData["title"] = "Purchase - Online Store";
                $viewData["subtitle"] = "Purchase Status";
                $viewData["order"] = $order;
                
                return view('cart.purchase')->with("viewData", $viewData);
            } else {
                Session::flash('error', 'Payment was not successful. Please try again.');
                return redirect()->route('cart.checkout');
            }

        } catch (Stripe\Exception\CardException $e) {
            // Card was declined
            Session::flash('error', 'Card Error: ' . $e->getError()->message);
            return redirect()->route('cart.checkout');
            
        } catch (Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters
            Session::flash('error', 'Invalid request: ' . $e->getMessage());
            return redirect()->route('cart.checkout');
            
        } catch (\Exception $e) {
            // General error
            Session::flash('error', 'Payment failed: ' . $e->getMessage());
            return redirect()->route('cart.checkout');
        }
    }

    // Keep old purchase method or remove if not needed
    public function purchase(Request $request)
    {
        // Redirect to checkout instead
        return redirect()->route('cart.checkout');
    }
}