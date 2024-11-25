<div>
    <h1>Управление товарами</h1>

    <h2>Добавить товар</h2>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="description">Описание:</label>
            <textarea name="description" required></textarea>
        </div>
        <div>
            <label for="price">Цена:</label>
            <input type="number" name="price" required>
        </div>
        <div>
            <label for="category_id">Категория:</label>
            <select name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Добавить</button>
    </form>

    <h2>Список товаров</h2>
    @foreach($products as $product)
        <div style="margin-bottom: 20px;">
            <p><strong>ID:</strong> {{ $product->id }}</p>
            <p><strong>Название:</strong> {{ $product->name }}</p>
            <p><strong>Описание:</strong> {{ $product->description }}</p>
            <p><strong>Цена:</strong> {{ $product->price }}</p>
            <p><strong>Категория:</strong> {{ $product->categories->first()->name ?? 'Без категории' }}</p>

            <h3>Редактировать товар</h3>
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <label for="name">Название:</label>
                    <input type="text" name="name" value="{{ $product->name }}" required>
                </div>
                <div>
                    <label for="description">Описание:</label>
                    <textarea name="description" required>{{ $product->description }}</textarea>
                </div>
                <div>
                    <label for="price">Цена:</label>
                    <input type="number" name="price" value="{{ $product->price }}" required>
                </div>
                <div>
                    <label for="category_id">Категория:</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->categories->first()->id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit">Сохранить</button>
            </form>

            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="margin-top: 10px;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">Удалить</button>
            </form>
        </div>
    @endforeach
</div>
