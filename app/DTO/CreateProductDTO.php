<?php

namespace App\DTO;

readonly class CreateProductDTO
{
    public string $name;
    public string $description;
    public float $price;
    public int $category_id;

    /**
     * CreateProductDTO constructor.
     *
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = $data['price'];
        $this->category_id = $data['category_id'];
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, string|float|int>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id
        ];
    }
}
