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
            <h2>Hi {{$name}},</h2>
        </div>
        <div style ="margin-top: 1rem; margin-bottom: 1rem;">
          <p>This is a confirmation email for your order. We have placed your order and is now in processing. Following are the details for your order:</p>
        </div>
        <div class="order-details">
            <p><strong>Order ID:</strong> {{ $orderId }}</p>
            <p><strong>Order Date:</strong> {{ $date }}</p>
            <p><strong>Product:</strong> {{ $product }}</p>
            <p><strong>Amount:</strong> {{ $amount }}</p>
        </div>

        <div class="message">
            <h4>Thank you for ordering with Femi!</h4>
        </div>
    </div>

    <div class="footer">
        <p>FEMI Inc, 1600 Amphitheatre Parkway, California</p>
    </div>
</body>
</html>
