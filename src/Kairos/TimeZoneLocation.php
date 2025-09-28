<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

class TimeZoneLocation
{
    public string $countryCode;
    public float $latitude;
    public float $longitude;
    public ?string $comments = null;

    public function __construct(
        string $countryCode,
        float $latitude,
        float $longitude,
        ?string $comments = null
    ) {
        if ($comments === '') {
            $comments = null;
        }

        $this->countryCode = $countryCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->comments = $comments;
    }
}
