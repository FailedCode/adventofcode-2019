<?php

namespace App\Service;

class Day17Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputIntcode();
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->runProgram();
        $output = $computer->getOutput();

        $x = 0;
        $y = 0;
        $xMax = 0;
        $yMax = 0;
        $grid = [];
        foreach ($output as $char) {
            if ($char == 10) {
                $x = 0;
                $y += 1;
                $yMax = $y-1;
                continue;
            }
            $grid[$x][$y] = $char;
            $x += 1;
            $xMax = $x;
        }

        $scaffoldChar = 35;
        $alignmentSum = 0;
        for ($y = 0; $y < $yMax; $y++) {
            for ($x = 0; $x < $xMax; $x++) {
                if ($grid[$x][$y] == $scaffoldChar) {
                    $dirs = 0;
                    if (isset($grid[$x-1][$y]) && $grid[$x-1][$y] == $scaffoldChar) {
                        $dirs += 1;
                    }
                    if (isset($grid[$x+1][$y]) && $grid[$x+1][$y] == $scaffoldChar) {
                        $dirs += 1;
                    }
                    if (isset($grid[$x][$y+1]) && $grid[$x][$y+1] == $scaffoldChar) {
                        $dirs += 1;
                    }
                    if (isset($grid[$x][$y-1]) && $grid[$x][$y-1] == $scaffoldChar) {
                        $dirs += 1;
                    }
                    if ($dirs == 4) {
                        $alignmentSum += $x*$y;
                    }
                }
            }
        }
        return $alignmentSum;
    }

    public function part2()
    {
    }
}
