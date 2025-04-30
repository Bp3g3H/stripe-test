<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shoplandia')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        header {
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-browse {
            background-color: #007bff;
            color: white;
        }
        .btn-cart {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to Shoplandia</h1>
</header>

<div class="container">
    <h2>@yield('title', 'Home')</h2>
    <div class="button-group">
        <a href="{{ route('shop.index') }}" class="btn btn-browse">Browse Goods</a>
        <a href="{{ route('shop.index') }}" class="btn btn-cart">View Cart</a>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>