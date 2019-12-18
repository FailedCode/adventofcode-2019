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

        $path = $this->findPath([0,0], [$x, $y], $grid);
        $this->logger->info("Path: ", $path);

        return count($path);
    }

    public function part2()
    {
    }

    /**
     * @param int[] $from
     * @param int[] $to
     * @param int[] $grid
     * @return array|bool
     */
    protected static function findPath($from, $to, $grid)
    {
        $startKey = self::posToKey($from);

        $closedSet = [];
        $openSet = [];
        $openSet[$startKey] = $from;
        $cameFrom = [];

        // weight for start to there
        $gScore = [];
        $gScore[$startKey] = 0;

        // weight for target to there
        $fScore = [];
        $fScore[$startKey] = self::manhattanDistance($from, $to);

        while (count($openSet)) {
            $currentKey = self::getMinimalFScore($openSet, $fScore);
            $current = self::keyToPos($currentKey);
            if ($current == $to) {
                if (empty($cameFrom)) {
                    return false;
                }
                $totelPath = [];
                $totelPath[] = $current;
                while (isset($cameFrom[$currentKey])) {
                    $current = $cameFrom[$currentKey];
                    $currentKey = self::posToKey($current);
                    if ($startKey == $currentKey) {
                        break;
                    }
                    $totelPath[] = $current;
                }
                return array_reverse($totelPath);
            }

            unset($openSet[$currentKey]);
            $closedSet[$currentKey] = $current;

            foreach ([[0, -1], [-1, 0], [1, 0], [0, 1]] as $nPos) {
                $neighbor = [$nPos[0] + $current[0], $nPos[1] + $current[1]];
                $neighborKey = self::posToKey($neighbor);

                // already evaluated
                if (isset($closedSet[$neighborKey])) {
                    continue;
                }

                // test here if the field actually can be used
                $neighborGrid = $grid[$neighbor[1]][$neighbor[0]] == self::WALL;
                if ($neighbor != $to && $neighborGrid != self::WALL) {
                    $closedSet[$neighborKey] = $neighbor;
                    continue;
                }

                // distance from current to neighbor is always 1
                $tentative_gScore = $gScore[$currentKey] + 1;


                // default = infinity
                $gScoreThing = isset($gScore[$neighborKey]) ? $gScore[$neighborKey] : PHP_INT_MAX;
                if (!isset($openSet[$neighborKey])) {
                    // add neighbor to searchable fields
                    $openSet[$neighborKey] = $neighbor;
                } elseif ($tentative_gScore >= $gScoreThing) {
                    // new score is same or bigger
                    continue;
                }

                $cameFrom[$neighborKey] = $current;
                $gScore[$neighborKey] = $tentative_gScore;
                $fScore[$neighborKey] = $gScore[$neighborKey] + self::manhattanDistance($neighbor, $to);
            }

        }
        // no path found!
        return false;
    }

    protected static function posToKey($position)
    {
        return "{$position[0]},{$position[1]}";
    }

    protected static function keyToPos($key)
    {
        return explode(',', $key);
    }


    /**
     * Return the node in openSet having the lowest fScore[] value
     *
     * @param array $openSet
     * @param array $fScore
     * @return string
     */
    protected static function getMinimalFScore($openSet, $fScore)
    {
        while (count($fScore)) {
            $key = array_keys($fScore, min($fScore))[0];
            if (!isset($openSet[$key])) {
                unset($fScore[$key]);
            } else {
                return $key;
            }
        }
    }

    /**
     * @param $p1
     * @param $p2
     * @return float|int
     */
    protected static function manhattanDistance($p1, $p2)
    {
        return abs($p1[1] - $p2[1]) + abs($p1[0] - $p2[0]);
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
