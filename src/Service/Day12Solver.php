<?php

namespace App\Service;

use App\Service\Day12\Moon;

class Day12Solver extends AbstractDaySolver
{
    public function part1()
    {
        $positions = $this->getInputFormated();
        $moonCount = count($positions);
        /** @var Moon[] $moon */
        $moon = [];
        for ($i = 0; $i < $moonCount; $i++) {
            $moon[$i] = new Moon($positions[$i]);
            $this->logger->info("new moon", $moon[$i]->getPosition());
        }

        $iterations = 1000;
        for ($i = 0; $i < $iterations; $i++) {
            for ($m1 = 0; $m1 < $moonCount; $m1++) {
                for ($m2 = 0; $m2 < $moonCount; $m2++) {
                    if ($m1 == $m2) {
                        continue;
                    }
                    $moon[$m1]->applyGravity($moon[$m2]);
                }
            }

            for ($m = 0; $m < $moonCount; $m++) {
                $moon[$m]->move();
            }
        }

        $energy = 0;
        for ($i = 0; $i < $moonCount; $i++) {
            $energy += $moon[$i]->getTotalEnergy();
        }

        return $energy;
    }

    public function part2()
    {
    }

    /**
    * @return array
    */
    protected function getInputFormated()
    {
        $lines = parent::getInputFormated();
        $positions = [];
        foreach ($lines as $line) {
            preg_match_all('~[-\d]+~', $line, $matches);
            $positions[] = $matches[0];
        }
        return $positions;
    }
}
