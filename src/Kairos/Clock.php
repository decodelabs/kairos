<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

use Carbon\CarbonImmutable;
use DateTimeZone;
use Psr\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    public TimeZone $timezone { get; }

    public function now(): CarbonImmutable;

    /**
     * @param int|float|array{int,int}|MicroTime $seconds
     */
    public function sleep(
        int|float|array|MicroTime $seconds
    ): void;

    public function withTimeZone(
        string|DateTimeZone|null $timezone
    ): static;
}
