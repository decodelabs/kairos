<?php

/**
 * @package Kairos
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Kairos;

use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

class Timer implements Dumpable
{
    public readonly float $start;
    private(set) ?float $end = null;

    public float $time {
        get => ($this->end ?? microtime(true)) - $this->start;
    }

    public function __construct(
        ?float $start = null
    ) {
        if ($start === null) {
            $start = microtime(true);
        }

        $this->start = $start;
    }


    public function isRunning(): bool {
        return $this->end === null;
    }


    /**
     * @return $this
     */
    public function stop(
        ?float $end = null
    ): static {
        if ($end === null) {
            $end = microtime(true);
        }

        $this->end = $end;
        return $this;
    }

    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->itemName = number_format($this->time * 1000, 2) . ' ms';

        $entity->meta = [
            'start' => number_format($this->start, 6),
            'end' => $this->end !== null ? number_format($this->end, 6) : null,
            'running' => $this->isRunning(),
        ];

        return $entity;
    }
}
