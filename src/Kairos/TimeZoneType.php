<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

enum TimeZoneType: int
{
    case Offset = 1;
    case Abbreviation = 2;
    case Region = 3;
}
