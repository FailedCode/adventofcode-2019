<?php

namespace App\Service\Day06;


class Orbit
{
    protected $value = '';

    /** @var Orbit[] */
    protected $children = [];

    /** @var Orbit|null */
    protected $parent = null;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function addChild(Orbit $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    public function setParent(Orbit $parent)
    {
        $this->parent = $parent;
    }

    public function getParentList()
    {
        $parents = [];
        $parent = $this->parent;
        while ($parent !== null) {
            $parents[] = $parent;
            $parent = $parent->parent;
        }
        return $parents;
    }

    public function sumChildOrbits($depth = 0)
    {
        $sum = $depth;
        foreach ($this->children as $child) {
            $sum += $child->sumChildOrbits($depth + 1);
        }
        return $sum;
    }

    public function __toString()
    {
        return $this->value;
    }
}
