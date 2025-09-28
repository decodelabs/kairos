<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos\Clock;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use DateTimeZone;
use DecodeLabs\Kairos\Clock;
use DecodeLabs\Kairos\MicroTime;
use DecodeLabs\Kairos\TimeZone;

class Fixed implements Clock
{
    private CarbonImmutable $now;

    public TimeZone $timezone {
        get => TimeZone::from($this->now->timezone);
    }

    public function __construct(
        string|DateTimeInterface|null $now = null,
        string|DateTimeZone|null $timezone = null
    ) {
        $timezone = TimeZone::from($timezone);

        if (is_string($now)) {
            $now = new CarbonImmutable($now, $timezone);
        } elseif ($now === null) {
            $now = new CarbonImmutable('now', $timezone);
        } elseif (!$now instanceof CarbonImmutable) {
            $now = CarbonImmutable::createFromInterface($now);
        }

        $now = $now->setTimezone($timezone);
        $this->now = $now;
    }

    public function now(): CarbonImmutable
    {
        return $this->now;
    }

    public function sleep(
        int|float|array|MicroTime $seconds
    ): void {
        $seconds = MicroTime::from($seconds);
        $now = (float)$this->now->format('Uu') + $seconds->asMicroseconds();
        $now = substr_replace(\sprintf('@%07.0F', $now), '.', -6, 0);
        $timezone = $this->now->getTimezone();
        $this->now = CarbonImmutable::createFromTimestamp($now, $timezone);
    }

    public function withTimeZone(
        string|DateTimeZone|null $timezone
    ): static {
        $output = clone $this;
        $output->__construct($this->now, $timezone);
        return $output;
    }
}
