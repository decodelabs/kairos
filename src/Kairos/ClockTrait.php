<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

use DateTimeZone;

trait ClockTrait
{
    public private(set) TimeZone $timezone {
        set(
            string|DateTimeZone|null $timezone
        ) {
            $this->timezone = TimeZone::from($timezone);
        }
    }

    public function __construct(
        string|DateTimeZone|null $timezone = null
    ) {
        $this->timezone = $timezone;
    }

    public function sleep(
        int|float|array|MicroTime $seconds
    ): void {
        $seconds = MicroTime::from($seconds);
        usleep($seconds->asMicroseconds());
    }
}
