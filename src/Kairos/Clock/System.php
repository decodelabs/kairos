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

class System implements Clock
{
    use ClockTrait;

    public function now(): CarbonImmutable
    {
        return CarbonImmutable::now($this->timezone);
    }

    public function withTimeZone(
        string|DateTimeZone|null $timezone
    ): static {
        $output = clone $this;
        $output->timezone = $timezone;
        return $output;
    }
}
