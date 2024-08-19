@extends('layouts.admin.app')
@section('title')
    Categories
@endsection
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<body>
    <h1 class = "mb-2">Order Details</h1>

    <div class = "mt-2">
        <strong>Order ID:</strong> {{ $order->id }}
    </div>
    <div class = "mt-2">
        <strong>Order placed by:</strong> {{ $order->user->name }}
    </div>
    <div class = "mt-2">
        <strong>Product:</strong> {{ $order->product->name }}
    </div>

    <div class ="mt-2">
        <strong>Order Date:</strong> {{ $order->created_at }}
    </div>
    <div class ="mt-2">
        <strong>Amount Paid:</strong> {{ $order->amount }}
    </div>

    <!-- Add other order details as needed -->

    <h2 class = "mt-4">Shipping Details</h2>

    @if ($order->shippingDetail)

        <div class = "mt-4">
            <strong>Name:</strong> {{ $order->shippingDetail->first_name}}
        </div>
        <div class = "mt-2">
            <strong>Shipping Address:</strong> {{ $order->shippingDetail->address }}
        </div>
        <div class = "mt-2">
            <strong>Address:</strong> {{ $order->shippingDetail->address}}
        </div>
        <div class = "mt-2">
            <strong>City:</strong> {{ $order->shippingDetail->city}}
        </div>
        <div class = "mt-2">
            <strong>State:</strong> {{ $order->shippingDetail->state}}
        </div>
        <div class = "mt-2">
            <strong>Apartment:</strong> {{ $order->shippingDetail->apt}}
        </div>
        <div class = "mt-2">
            <strong>Postal Code:</strong> {{ $order->shippingDetail->postal_code}}
        </div>
        <h2 class = "mt-4 mb-2">Card Details</h2>
        <div class = "mt-2">
            <strong>Card Number:</strong> {{ $order->shippingDetail->card_number}}
        </div>
        <div class = "mt-2">
            <strong>Expiry:</strong> {{ $order->shippingDetail->expiry}}
        </div>
        <div class = "mt-2">
            <strong>Cvc:</strong> {{ $order->shippingDetail->cvc}}
        </div>
    @else
        <p class = "mt-4">No shipping details found for this order.</p>
    @endif

    <!-- You can add additional sections for products, totals, etc. -->
</body>
</html>
@endsection
