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
</body>
</html>
