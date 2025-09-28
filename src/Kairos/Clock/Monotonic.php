<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos\Clock;

use Carbon\CarbonImmutable;
use DateTimeZone;
use DecodeLabs\Kairos\Clock;
use DecodeLabs\Kairos\ClockTrait;
use DecodeLabs\Kairos\MicroTime;

class Monotonic implements Clock
{
    use ClockTrait;

    private MicroTime $offset;

    public function __construct(
        string|DateTimeZone|null $timezone = null
    ) {
        $this->timezone = $timezone;

        $offset = MicroTime::hrtime();
        $time = MicroTime::microtime();
        $this->offset = $time->subtract($offset);
    }

    public function now(): CarbonImmutable
    {
        $currentOffset = MicroTime::hrtime();
        $time = $this->offset->add($currentOffset);
        $timestamp = $time->toTimestampString();
        return CarbonImmutable::createFromTimestamp($timestamp, $this->timezone);
    }

    public function withTimeZone(
        string|DateTimeZone|null $timezone
    ): static {
        $output = clone $this;
        $output->timezone = $timezone;
        return $output;
    }
}
