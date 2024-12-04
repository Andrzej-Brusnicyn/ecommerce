<?php

namespace App\DTO;

use App\Enums\OrderStatus;
use DateTimeImmutable;

readonly class CreateOrderDTO
{
    public int $userId;
    public float $totalAmount;
    public DateTimeImmutable $orderDate;
    public OrderStatus $status;

    /**
     * CreateOrderDTO constructor.
     *
     * @param int $userId
     * @param float $totalAmount
     * @param DateTimeImmutable $orderDate
     * @param OrderStatus $status
     */
    public function __construct(
        int $userId,
        float $totalAmount,
        DateTimeImmutable $orderDate,
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
     * @return array<string, int|float|DateTimeImmutable>
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
