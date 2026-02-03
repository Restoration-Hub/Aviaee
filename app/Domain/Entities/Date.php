<?php

namespace App\Domain\Entities;

use Carbon\Carbon;

/**
 * Minimal Date wrapper used by MissionEntity.
 */
class Date
{
    public Carbon $carbon;

    public function __construct(string|Carbon|null $value = null)
    {
        $this->carbon = $value instanceof Carbon ? $value : Carbon::parse($value ?? now());
    }

    public function __toString(): string
    {
        return $this->carbon->format('Y-m-d H:i:s');
    }

    public function toDateTimeString(): string
    {
        return $this->carbon->toDateTimeString();
    }
}
