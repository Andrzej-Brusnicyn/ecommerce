<h2>Корзина</h2>

@if($cart && $cart->items->count() > 0)
    @foreach($cart->items as $item)
        <div class="cart-item">
            <h3>{{ $item->product->name }}</h3>
            <p>Цена: {{ $item->product->price }} BYN</p>

            <form action="{{ route('cart.update', $item) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <label>
                    Количество:
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1">
                </label>
                <button type="submit">Обновить</button>
            </form>

            <form action="{{ route('cart.remove', $item) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>

            @if($item->services->isNotEmpty())
                <div class="cart-item-services">
                    <h4>Услуги:</h4>
                    <ul>
                        @foreach($item->services as $service)
                            <li>{{ $service->name }} (+{{ $service->price }} BYN)</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <hr>
    @endforeach

    <div class="cart-total">
        <h3>Итого:</h3>
        <p>
            {{ $cart->items->sum(function($item) {
                return $item->product->price * $item->quantity
                    + $item->services->sum('price');
            }) }} BYN
        </p>
    </div>

    <form action="{{ route('order.create') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Оформить заказ</button>
    </form>
@else
    <p>Ваша корзина пуста.</p>
@endif
