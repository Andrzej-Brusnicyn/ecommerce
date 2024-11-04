<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
</head>
<body>
<form method="GET" class="filters">
    <select name="category_id">
        <option value="">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <input type="number"
           name="min_price"
           value="{{ request('min_price') }}"
           placeholder="Min Price">

    <input type="number"
           name="max_price"
           value="{{ request('max_price') }}"
           placeholder="Max Price">

    <select name="sort_by">
        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>
            Newest First
        </option>
        <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>
            Price
        </option>
        <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>
            Name
        </option>
    </select>

    <select name="sort_direction">
        <option value="asc" {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>
            Low to High
        </option>
        <option value="desc" {{ request('sort_direction') === 'desc' ? 'selected' : '' }}>
            High to Low
        </option>
    </select>

    <button type="submit">Apply Filters</button>
</form>

<div class="products-grid">
    @foreach($products as $product)
        <div class="product-card">
            <a href="/products/{{$product->id}}"><h3>{{ $product->name }}</h3></a>
            <p>Price: {{ $product->price }} BYN</p>
        </div>
    @endforeach
</div>
</body>
</html>
