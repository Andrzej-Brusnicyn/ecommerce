<?php

namespace App\DTO;

class CreateProductDTO
{
    public $name;
    public $description;
    public $price;
    public $category_id;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = $data['price'];
        $this->category_id = $data['category_id'];
    }

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
