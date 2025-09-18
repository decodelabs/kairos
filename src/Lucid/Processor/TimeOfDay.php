<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Lucid\Processor;

use DateTimeInterface;
use DecodeLabs\Exceptional;
use DecodeLabs\Kairos\TimeOfDay as TimeOfDayInterface;
use DecodeLabs\Lucid\Processor;
use DecodeLabs\Lucid\ProcessorTrait;
use DecodeLabs\Lucid\Sanitizer;

/**
 * @implements Processor<TimeOfDayInterface>
 */
class TimeOfDay implements Processor
{
    /**
     * @use ProcessorTrait<TimeOfDayInterface>
     */
    use ProcessorTrait;

    public const array OutputTypes = ['Kairos:TimeOfDay', TimeOfDayInterface::class];

    public function __construct(
        protected Sanitizer $sanitizer
    ) {
    }

    public function coerce(
        mixed $value
    ): ?TimeOfDayInterface {
        if ($value === null) {
            return null;
        }

        if (
            !$value instanceof TimeOfDayInterface &&
            !$value instanceof DateTimeInterface &&
            !is_string($value) &&
            !is_int($value)
        ) {
            throw Exceptional::UnexpectedValue(
                message: 'Unable to coerce value to TimeOfDay'
            );
        }

        return TimeOfDayInterface::from($value);
    }
}
