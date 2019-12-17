<?php

namespace App\Service;

class Day16Solver extends AbstractDaySolver
{
    public function part1()
    {
        $data = $this->getInputFormated();

        for ($i = 0; $i < 100; $i++) {
            $data = $this->calcFFT($data);
        }

        $result = substr(implode('', $data),0, 8);
        return $result;
    }

    protected function calcFFT($data)
    {
        $output = [];
        $max = count($data);
        for ($i = 1; $i <= $max; $i++) {
            $pattern = $this->repeatPattern($i, $max);
            $sum = 0;
            $j = 0;
            foreach ($data as $value) {
                $sum += $value * $pattern[$j];
                $j += 1;
            }
            $rightmost = substr($sum, -1, 1);
            $output[] = $rightmost;
        }
        return $output;
    }

    /**
     * @param int $n
     * @param int $max
     */
    protected function repeatPattern($n, $max)
    {
        $base = [0, 1, 0, -1];
        $nList = [];
        foreach ($base as $value) {
            for ($i = 0; $i < $n; $i++) {
                $nList[] = $value;
            }
        }

        $p = 0;
        while (count($nList) < ($max + 1))
        {
            $nList[] = $nList[$p];
            $p += 1;
        }

        array_shift($nList);
        return $nList;
    }

    public function part2()
    {
    }

    /**
    * @return array
    */
    protected function getInputFormated()
    {
        $data = parent::getInputFormated();
        return str_split(implode('', $data));
    }
}
