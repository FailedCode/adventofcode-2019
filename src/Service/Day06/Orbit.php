<?php

namespace App\Service\Day06;


class Orbit
{
    protected $value = '';

    /** @var Orbit[] */
    protected $children = [];

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function addChild(Orbit $child)
    {
        $this->children[] = $child;
    }

    public function sumChildOrbits($depth = 0)
    {
        $sum = $depth;
        foreach ($this->children as $child) {
            $sum += $child->sumChildOrbits($depth + 1);
        }
        return $sum;
    }
}
