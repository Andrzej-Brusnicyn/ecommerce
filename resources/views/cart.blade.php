<h2>Cart</h2>

@if($cart && $cart->items->count() > 0)
    @foreach($cart->items as $item)
        <div class="cart-item">
            <h3>{{ $item->product->name }}</h3>
            <p>Price: {{ $item->product->price }} BYN</p>

            <form action="{{ route('cart.update', $item) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1">
                <button type="submit">Reload</button>
            </form>

            <form action="{{ route('cart.remove', $item) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    @endforeach

    <div class="cart-services">
        <h3>Selected Services</h3>
        @foreach($cart->services as $service)
            <p>{{ $service->name }} (+{{ $service->price }} BYN)</p>
        @endforeach
    </div>

    <div class="cart-total">
        <p>Итого:
            {{ $cart->items->sum(function($item) {
                return $item->product->price * $item->quantity;
            }) + $cart->services->sum('price') }} руб.
        </p>
    </div>

    <form action="{{ route('order') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Купить</button>
    </form>
@else
    <p>Cart is empty</p>
@endif
