<?php

namespace App\Service;

use App\Service\Day06\Orbit;

class Day06Solver extends AbstractDaySolver
{
    public function part1()
    {
        /** @var Orbit[] $orbits */
        $orbits = $this->buildOrbits($this->getInputFormated());
        return $orbits['COM']->sumChildOrbits();
    }

    public function part2()
    {
    }

    protected function buildOrbits($lines)
    {
        /** @var Orbit[] $orbits */
        $orbits = [];
        $children = [];
        foreach ($lines as $line) {
            list($key, $value) = explode(')', $line);

            if (!isset($orbits[$key])) {
                $orbits[$key] = new Orbit($key);
            }
            if (!isset($orbits[$value])) {
                $orbits[$value] = new Orbit($value);
            }

            if (!isset($children[$key])) {
                $children[$key] = [];
            }
            $children[$key][] = $value;
        }

        foreach ($children as $parent => $childs) {
            foreach ($childs as $child) {
                $orbits[$parent]->addChild($orbits[$child]);
            }
        }

        return $orbits;
    }

    /**
    * @return array
    */
    protected function getInputFormated()
    {
        return parent::getInputFormated();
    }
}
