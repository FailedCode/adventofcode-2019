<?php

namespace App\Service;

class Day15Solver extends AbstractDaySolver
{
    protected const NORTH = 1, SOUTH = 2, WEST = 3, EAST = 4;
    protected const WALL = 0, SPACE = 1, TARGET = 2;

    public function part1()
    {
        $program = $this->getInputIntcode();
        $computer = new IntcodeComputer();
        $computer->setCode($program);

        $grid = [];
        $x = 0;
        $y = 0;
        $direction = self::NORTH;
        $directionMapR = [self::NORTH => self::WEST, self::WEST => self::SOUTH, self::SOUTH => self::EAST, self::EAST => self::NORTH];
        $directionMapL = array_flip($directionMapR);
        $failsave = 10000;
        while (!$computer->hasHaltet()) {
            $computer->addInput($direction);
            $status = $computer->runProgram(true);

            $newX = $x;
            $newY = $y;
            switch ($direction) {
                case self::NORTH: $newY -= 1; break;
                case self::SOUTH: $newY += 1; break;
                case self::WEST: $newX += 1; break;
                case self::EAST: $newX -= 1; break;
            }

            $grid[$newY][$newX] = $status;
            if ($status) {
                $x = $newX;
                $y = $newY;
                // turn back left to check for wall
                $direction = $directionMapL[$direction];
            } else {
                // hitting wall: turn right
                $direction = $directionMapR[$direction];
            }

            if ($status == self::TARGET) {
                break;
            }

            $failsave -= 1;
            if ($failsave == 0) {
                $this->logger->info("ABORTET");
                $grid = $this->fillMap($grid);
                return $this->drawMap($grid);
            }
        }

        // calculate shortest path between (0,0)-($x,$y)
        $this->logger->info("Target: $x,$y");
        $grid = $this->fillMap($grid);
        $map = $this->drawMap($grid);
        return $map;
    }

    public function part2()
    {
    }

    protected function fillMap($grid)
    {
        $yMin = $xMin = PHP_INT_MAX;
        $yMax = $xMax = PHP_INT_MIN;
        foreach ($grid as $y => $line) {
            $yMax = max($yMax, $y);
            $yMin = min($yMin, $y);
            foreach ($line as $x => $element) {
                $xMax = max($xMax, $x);
                $xMin = min($xMin, $x);
            }
        }

        for ($y = $yMin - 1; $y <= $yMax; $y++) {
            for ($x = $xMin - 1; $x <= $xMax; $x++) {
                $grid[$y][$x] = $grid[$y][$x] ?? 0;
            }
        }

        return $grid;
    }

    protected function drawMap($grid)
    {
        $yMin = $xMin = PHP_INT_MAX;
        $yMax = $xMax = PHP_INT_MIN;
        foreach ($grid as $y => $line) {
            $yMax = max($yMax, $y);
            $yMin = min($yMin, $y);
            foreach ($line as $x => $element) {
                $xMax = max($xMax, $x);
                $xMin = min($xMin, $x);
            }
        }

        $text = '';
        for ($y = $yMin - 1; $y <= $yMax; $y++) {
            for ($x = $xMin - 1; $x <= $xMax; $x++) {
                $v = $grid[$y][$x] ?? 0;
                switch ($v) {
                    case self::WALL: $v = '#'; break;
                    case self::TARGET: $v = '@'; break;
                    case self::SPACE: $v = ' '; break;
                }
                if ($y == 0 && $x == 0) {
                    $v = 'D';
                }
                $text .= $v;
            }
            $text .= "\n";
        }
        return "<pre>$text</pre>";
    }
}
