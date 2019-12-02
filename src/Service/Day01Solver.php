<?php


namespace App\Service;


class Day01Solver extends AbstractDaySolver
{
    /**
     * @return mixed
     */
    public function part1()
    {
        $masses = $this->getInputFormated();
        $massSum = 0;
        foreach ($masses as $mass) {
            $massSum += floor((int)$mass / 3) - 2;
        }
        return $massSum;
    }

    /**
     * @return mixed
     */
    public function part2()
    {
        $masses = $this->getInputFormated();
        $massSum = 0;
        $fuelMass = 0;
        foreach ($masses as $mass) {
            $massSum += $mass;
            while ($mass > 0)
            {
                $fuelMass += $mass;
                $fuel = floor((int)$mass / 3) - 2;
                $mass = $fuel;
            }
        }
        return $fuelMass - $massSum;
    }
}
