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

            <div class="cart-total">
                Итого: {{ $cart->items->sum(function($item) {
                return $item->product->price * $item->quantity;
            }) }} руб.
            </div>
        @else
            <p>Cart is empty</p>
        @endif
