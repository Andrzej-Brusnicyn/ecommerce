<?php

namespace App\DTO;

readonly class CreateOrderDTO
{
    public int $userId;
    public float $totalAmount;
    public string $orderDate;
    public string $status;

    /**
     * CreateOrderDTO constructor.
     *
     * @param int $userId
     * @param float $totalAmount
     */
    public function __construct(int $userId, float $totalAmount)
    {
        $this->userId = $userId;
        $this->totalAmount = $totalAmount;
        $this->orderDate = now()->toDateTimeString();
        $this->status = 'in process';
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'total_amount' => $this->totalAmount,
            'order_date' => $this->orderDate,
            'status' => $this->status
        ];
    }
}
