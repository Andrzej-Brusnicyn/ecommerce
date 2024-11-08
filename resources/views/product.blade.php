<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
</head>
<body>
<div>
    <div>
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        <h1>{{ $product->name }}</h1>
        <p>{{ $category->name }}</p>
        <p>{{ $product->description }}</p>
        <p><strong>Price: {{ $product->price }} BYN</strong></p>
    </div>
</div>
<form action="{{ route('cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit">Add to cart</button>
</form>
</body>
</html>
