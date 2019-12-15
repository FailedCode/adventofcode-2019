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
        /** @var Orbit[] $orbits */
        $orbits = $this->buildOrbits($this->getInputFormated());
        $youParents = $orbits['YOU']->getParentList();
        $sanParents = $orbits['SAN']->getParentList();

        $this->logger->info("YOU", $youParents);
        $this->logger->info("SAN", $sanParents);

        $sharedParent = null;
        foreach ($sanParents as $sanParent) {
            foreach ($youParents as $youParent) {
                if ($youParent == $sanParent) {
                    $sharedParent = $youParent;
                    $this->logger->info("sharedParent", [$sharedParent]);
                    break 2;
                }
            }
        }

        if (is_null($sharedParent)) {
            $this->logger->info("No sharedParent found");
            return 0;
        }

        $steps = 0;
        foreach ($youParents as $youParent) {
            $steps += 1;
            if ($sharedParent == $youParent) {
                break;
            }
        }
        foreach ($sanParents as $sanParent) {
            $steps += 1;
            if ($sharedParent == $sanParent) {
                break;
            }
        }

        // don't count shared parent twice: -1
        // don't count parents but transfers: -1
        return $steps - 2;
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
