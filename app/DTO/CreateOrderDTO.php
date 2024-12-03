<?php

namespace App\DTO;

enum OrderStatus: string
{
    case New = 'new';
    case InProcess = 'in process';
    case Shipping = 'shipping';
    case Delivering = 'delivering';
    case Cancelled = 'cancelled';
}

readonly class CreateOrderDTO
{
    public int $userId;
    public float $totalAmount;
    public string $orderDate;
    public OrderStatus $status;

    /**
     * CreateOrderDTO constructor.
     *
     * @param int $userId
     * @param float $totalAmount
     * @param string $orderDate
     * @param OrderStatus $status
     */
    public function __construct(
        int $userId,
        float $totalAmount,
        string $orderDate,
        OrderStatus $status
    ) {
        $this->userId = $userId;
        $this->totalAmount = $totalAmount;
        $this->orderDate = $orderDate;
        $this->status = $status;
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, int|float|string>
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'total_amount' => $this->totalAmount,
            'order_date' => $this->orderDate,
            'status' => $this->status->value,
        ];
    }
}
