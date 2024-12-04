<?php

namespace App\Exceptions;

use Exception;

class CartEmptyException extends Exception
{
    protected $code = 1001;
    protected $message = 'Cart empty.';

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
