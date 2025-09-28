<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

use DecodeLabs\Coercion;
use DecodeLabs\Exceptional;
use Stringable;

class MicroTime implements Stringable
{
    private readonly int $seconds;
    private readonly int $nanoseconds;

    public static function hrtime(): self
    {
        // @phpstan-ignore-next-line
        if (false === ($time = hrtime())) {
            throw Exceptional::ComponentUnavailable(
                message: 'hrtime() could not provide a monotonic timer.'
            );
        }

        return new self($time);
    }

    public static function microtime(): self
    {
        return new self(microtime(true));
    }

    /**
     * @param int|float|string|array{int,int}|self|null $seconds
     */
    public static function from(
        int|float|string|array|self|null $seconds = null
    ): self {
        if ($seconds instanceof self) {
            return $seconds;
        }

        return new self($seconds);
    }

    /**
     * @param int|float|string|array{int,int}|self|null $seconds
     */
    public function __construct(
        int|float|string|array|self|null $seconds = null,
    ) {
        if (
            is_string($seconds) &&
            is_numeric($seconds)
        ) {
            $seconds = Coercion::asFloat($seconds);
        }

        if (is_null($seconds)) {
            $seconds = microtime(true);
        }

        if (is_int($seconds)) {
            $this->seconds = $seconds;
            $this->nanoseconds = 0;
            return;
        }

        if (is_float($seconds)) {
            $this->seconds = (int)$seconds;
            $this->nanoseconds = (int)(($seconds - $this->seconds) * 1e+9);
            return;
        }

        if (is_array($seconds)) {
            $this->seconds = Coercion::asInt($seconds[0]);
            $this->nanoseconds = Coercion::asInt($seconds[1]);
            return;
        }

        if ($seconds instanceof MicroTime) {
            $this->seconds = $seconds->seconds;
            $this->nanoseconds = $seconds->nanoseconds;
            return;
        }

        if (preg_match('/^([\d.]+) (\d+)$/', $seconds, $matches)) {
            $this->seconds = Coercion::asInt($matches[2]);
            $this->nanoseconds = Coercion::asInt(Coercion::asFloat($matches[1]) * 1e+9);
            return;
        }

        throw Exceptional::InvalidArgument(
            message: 'Invalid microtime value',
            data: $seconds
        );
    }

    /**
     * @param int|float|string|array{int,int}|self $seconds
     */
    public function add(
        int|float|string|array|self $seconds
    ): self {
        if (!$seconds instanceof MicroTime) {
            $seconds = new MicroTime($seconds);
        }

        $newSeconds = $this->seconds + $seconds->seconds;
        $newNanoseconds = $this->nanoseconds + $seconds->nanoseconds;

        while ($newNanoseconds >= 1e+9) {
            $newSeconds++;
            $newNanoseconds -= 1e+9;
        }

        return new MicroTime([$newSeconds, Coercion::asInt($newNanoseconds)]);
    }

    /**
     * @param int|float|string|array{int,int}|self $seconds
     */
    public function subtract(
        int|float|string|array|self $seconds
    ): self {
        if (!$seconds instanceof MicroTime) {
            $seconds = new MicroTime($seconds);
        }

        $newSeconds = $this->seconds - $seconds->seconds;
        $newNanoseconds = $this->nanoseconds - $seconds->nanoseconds;

        while ($newNanoseconds < 0) {
            $newSeconds--;
            $newNanoseconds += 1e+9;
        }

        return new MicroTime([$newSeconds, Coercion::asInt($newNanoseconds)]);
    }


    public function toFloat(): float
    {
        return $this->seconds + ($this->nanoseconds / 1e+9);
    }

    /**
     * @return array{int,int}
     */
    public function toArray(): array
    {
        return [$this->seconds, $this->nanoseconds];
    }

    public function asSeconds(): int
    {
        return $this->seconds;
    }

    public function asMilliseconds(): int
    {
        return Coercion::asInt(($this->seconds * 1e+3) + ($this->nanoseconds / 1e+6));
    }

    public function asMicroseconds(): int
    {
        return Coercion::asInt(($this->seconds * 1e+6) + ($this->nanoseconds / 1e+3));
    }

    public function asNanoseconds(): int
    {
        return Coercion::asInt(($this->seconds * 1e+9) + $this->nanoseconds);
    }

    public function toTimestampString(): string
    {
        return '@' . $this->seconds . '.' . str_pad((string)$this->nanoseconds, 9, '0', STR_PAD_LEFT);
    }

    public function __toString(): string
    {
        $time = $this->asNanoseconds() / 1e+9;

        if ($time > 60) {
            return number_format($time / 60, 0) . ':' . number_format((int)$time % 60);
        } elseif ($time > 1) {
            return number_format($time, 3) . ' s';
        } elseif ($time > 0.0005) {
            return number_format($time * 1000, 2) . ' ms';
        } else {
            return number_format($time * 1000, 5) . ' ms';
        }
    }
}
