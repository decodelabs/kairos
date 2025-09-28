<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;
use Stringable;

class Timer implements
    Stringable,
    Dumpable
{
    public readonly MicroTime $start;
    public private(set) ?MicroTime $end = null;

    public MicroTime $time {
        get {
            $end = $this->end ?? new MicroTime(hrtime());
            return $end->subtract($this->start);
        }
    }

    /**
     * @param int|float|string|array{int,int}|MicroTime|null $start
     */
    public function __construct(
        int|float|string|array|MicroTime|null $start = null
    ) {
        if ($start === null) {
            $start = hrtime();
        }

        $this->start = MicroTime::from($start);
    }


    public function isRunning(): bool
    {
        return $this->end === null;
    }


    /**
     * @param int|float|string|array{int,int}|MicroTime|null $end
     * @return $this
     */
    public function stop(
        int|float|string|array|MicroTime|null $end = null
    ): static {
        if ($end === null) {
            $end = hrtime();
        }

        $this->end = MicroTime::from($end);
        return $this;
    }

    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->itemName = $this->__toString();

        $entity->meta = [
            'start' => $this->start,
            'end' => $this->end,
            'running' => $this->isRunning(),
        ];

        return $entity;
    }

    public function __toString(): string
    {
        return (string)$this->time;
    }
}
