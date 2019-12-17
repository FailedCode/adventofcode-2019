<?php

namespace App\Service;

class Day11Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputIntcode();
        $computer = new IntcodeComputer();
        $computer->setCode($program);

        // 0 = up, 1 = right, 2 = down, 3 = left
        $direction = 0;
        $x = 0;
        $y = 0;
        $grid = [];
        $coloredPanels = [];
        while (!$computer->hasHaltet()) {
            $currentPanel = $grid[$x][$y] ?? 0;
            $computer->addInput($currentPanel);
            $grid[$x][$y] = $computer->runProgram(true);
            $turn = $computer->runProgram(true);

            if (!isset($coloredPanels["$x,$y"])) {
                $coloredPanels["$x,$y"] = 1;
            }

            if ($turn) {
                $direction = ($direction + 1) % 4;
            } else {
                $direction = ($direction + 3) % 4;
            }
            switch ($direction) {
                case 0:
                    $y -= 1;
                    break;
                case 1:
                    $x += 1;
                    break;
                case 2:
                    $y += 1;
                    break;
                case 3:
                    $x -= 1;
                    break;
            }
        }

        return count($coloredPanels);
    }

    public function part2()
    {
    }
}
