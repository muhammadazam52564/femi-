<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #F79471;
        }

        .header {
            text-align: center;
        }

        .order-details {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 5px;
        }

        .message {
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            background-color: #F79471;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style ="margin-top: 1rem; margin-bottom: 2rem;">FEMI</h1>
        </div>
        <div style ="margin-top: 1rem; margin-bottom: 1rem;">
            <p>An order has been placed. Below are the details:</p>
        </div>
        <div class="order-details">
            <p><strong>Ordered By:</strong> {{ $name }}</p>
            <p><strong>Order ID:</strong> {{ $orderId }}</p>
            <p><strong>Order Date:</strong> {{ $date }}</p>
            <p><strong>Product:</strong> {{ $product }}</p>
            <p><strong>Amount:</strong> {{ $amount }}</p>
        </div>

        <div class="message">
            <p>Please be advised that a new order has been placed at Femi. Kindly take actions accordingly. You can check this order by cross verifying the order id in the admin dashboard.</p>
        </div>
    </div>

    <div class="footer">
        <p>FEMI Inc, 1600 Amphitheatre Parkway, California</p>
    </div>
</body>
</html>
