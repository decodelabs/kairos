<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

use DateTimeInterface;
use DecodeLabs\Coercion;
use DecodeLabs\Exceptional;
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;
use ReflectionProperty;
use Stringable;

class TimeOfDay implements Stringable, Dumpable
{
    public int $hours = 0 {
        set(int $hours) {
            if (
                $hours >= 0 &&
                $hours < 24
            ) {
                $this->hours = $hours;
            } else {
                [$this->hours,,] = $this->setNormalized($hours, $this->minutes, $this->seconds);
            }
        }
    }

    public int $minutes = 0 {
        set(int $minutes) {
            if (
                $minutes >= 0 &&
                $minutes < 60
            ) {
                $this->minutes = $minutes;
            } else {
                [,$this->minutes,] = $this->setNormalized($this->hours, $minutes, $this->seconds);
            }
        }
    }

    public int $seconds = 0 {
        set(int $seconds) {
            if (
                $seconds >= 0 &&
                $seconds < 60
            ) {
                $this->seconds = $seconds;
            } else {
                [,,$this->seconds] = $this->setNormalized($this->hours, $this->minutes, $seconds);
            }
        }
    }

    public static function from(
        int|string|self|DateTimeInterface|null $value
    ): self {
        if (null === ($value = self::tryFrom($value))) {
            throw Exceptional::InvalidArgument(
                message: 'Invalid time of day',
                data: $value
            );
        }

        return $value;
    }

    public static function tryFrom(
        int|string|self|DateTimeInterface|null $value
    ): self|null {
        if ($value === null) {
            return null;
        }

        if ($value instanceof self) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return self::fromDateTime($value);
        }

        if (is_int($value)) {
            return new self(0, 0, $value);
        }

        return self::tryFromString($value);
    }

    public static function fromDateTime(
        DateTimeInterface $date
    ): self {
        return new self(
            (int)$date->format('H'),
            (int)$date->format('i'),
            (int)$date->format('s')
        );
    }

    public static function fromString(
        string $string
    ): self {
        if (null === ($value = self::tryFromString($string))) {
            throw Exceptional::InvalidArgument(
                message: 'Unable to parse time of day string',
                data: $string
            );
        }

        return $value;
    }

    public static function tryFromString(
        string $string
    ): self|null {
        $parts = explode(':', $string);

        if (count($parts) === 1) {
            if (null === ($seconds = Coercion::tryInt($parts[0]))) {
                return null;
            }

            return new self(0, 0, $seconds);
        }

        if (null === ($hours = Coercion::tryInt($parts[0]))) {
            return null;
        }

        if (null === ($minutes = Coercion::tryInt($parts[1]))) {
            return null;
        }

        $seconds = Coercion::tryInt($parts[2] ?? 0) ?? 0;
        return new self($hours, $minutes, $seconds);
    }

    public function __construct(
        int $hours,
        int $minutes,
        int $seconds = 0
    ) {
        $this->setNormalized($hours, $minutes, $seconds);
    }

    /**
     * @return array{int,int,int}
     */
    private function setNormalized(
        int $hours,
        int $minutes,
        int $seconds
    ): array {
        while ($seconds < 0) {
            $seconds += 60;
            $minutes--;
        }

        if ($seconds >= 60) {
            $minutes += floor($seconds / 60);
            $seconds %= 60;
        }


        while ($minutes < 0) {
            $minutes += 60;
            $hours--;
        }

        if ($minutes >= 60) {
            $hours += floor($minutes / 60);
            $minutes %= 60;
        }

        while ($hours < 0) {
            $hours += 24;
        }

        if ($hours >= 24) {
            $hours %= 24;
        }

        $hours = (int)$hours;
        $minutes = (int)$minutes;
        $seconds = (int)$seconds;

        $hourRef = new ReflectionProperty($this, 'hours');
        $hourRef->setRawValue($this, $hours);
        $minuteRef = new ReflectionProperty($this, 'minutes');
        $minuteRef->setRawValue($this, $minutes);
        $secondRef = new ReflectionProperty($this, 'seconds');
        $secondRef->setRawValue($this, $seconds);

        return [$hours, $minutes, $seconds];
    }

    public function __toString(): string
    {
        return sprintf('%02d:%02d:%02d', $this->hours, $this->minutes, $this->seconds);
    }

    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->definition = $this->__toString();
        return $entity;
    }
}
