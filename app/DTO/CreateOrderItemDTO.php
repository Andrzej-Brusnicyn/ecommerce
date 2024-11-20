<?php

namespace App\DTO;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;

readonly class CreateOrderItemDTO
{
    public int $productId;
    public int $quantity;
    public float $price;
    public Collection $services;

    /**
     * CreateOrderItemDTO constructor.
     *
     * @param CartItem $cartItem
     */
    public function __construct(CartItem $cartItem)
    {
        $this->productId = $cartItem->product_id;
        $this->quantity = $cartItem->quantity;
        $this->price = $cartItem->product->price;
        $this->services = $cartItem->services;
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price
        ];
    }
}
