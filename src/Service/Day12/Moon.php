<?php

namespace App\Service\Day12;

class Moon
{
    protected $x = 0;
    protected $y = 0;
    protected $z = 0;

    protected $vx = 0;
    protected $vy = 0;
    protected $vz = 0;

    public function __construct($position)
    {
        $this->x = $position[0];
        $this->y = $position[1];
        $this->z = $position[2];
    }

    public function getPosition()
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'z' => $this->z,
        ];
    }

    public function applyGravity(Moon $moon)
    {
        $position = $moon->getPosition();
        
        if ($position['x'] > $this->x) {
            $this->vx += 1;
        } elseif ($position['x'] < $this->x) {
            $this->vx -= 1;
        }

        if ($position['y'] > $this->y) {
            $this->vy += 1;
        } elseif ($position['y'] < $this->y) {
            $this->vy -= 1;
        }

        if ($position['z'] > $this->z) {
            $this->vz += 1;
        } elseif ($position['z'] < $this->z) {
            $this->vz -= 1;
        }
    }

    public function move()
    {
        $this->x += $this->vx;
        $this->y += $this->vy;
        $this->z += $this->vz;
    }

    /**
     * @return float|int
     */
    public function getPotentialEnergy()
    {
        return abs($this->x) + abs($this->y) + abs($this->z);
    }

    /**
     * @return float|int
     */
    public function getKineticEnergy()
    {
        return abs($this->vx) + abs($this->vy) + abs($this->vz);
    }

    /**
     * @return float|int
     */
    public function getTotalEnergy()
    {
        return $this->getPotentialEnergy() * $this->getKineticEnergy();
    }
}
