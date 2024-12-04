<?php

namespace App\Exceptions;

use Exception;

class OrderCreationException extends Exception
{
    protected $code = 1002;
    protected $message = 'Error creating order.';

    /**
     * Custom Exception constructor
     *
     * @param string|null $message
     * @param int|null $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        ?string $message = null,
        ?int $code = null,
        ?\Throwable $previous = null
    ) {
        $message = $message ?? $this->message;
        $code = $code ?? $this->code;

        parent::__construct($message, $code, $previous);
    }
}
