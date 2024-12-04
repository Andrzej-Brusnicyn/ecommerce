<?php

namespace App\Services;

use Psr\Clock\ClockInterface;
use DateTimeImmutable;

class SystemClock implements ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable instance.
     *
     * @return DateTimeImmutable
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
