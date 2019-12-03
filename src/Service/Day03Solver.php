<?php

namespace App\Service;

class Day03Solver extends AbstractDaySolver
{
    public function part1()
    {
        $cables = $this->getInputFormated();
        $grid = $this->layoutCables($cables);

        $shortestDistance = -1;
        foreach ($grid as $position => $cell) {
            if ($cell == 3) {
                $p = explode('|', $position);
                $d = abs($p[0]) + abs($p[1]);
                if ($shortestDistance === -1 || $d < $shortestDistance) {
                    $shortestDistance = $d;
                }
            }
        }

        return $shortestDistance;
    }

    public function part2()
    {
    }

    protected function layoutCables($cables)
    {
        $grid = [];
        $cableNr = 1;
        foreach ($cables as $cable) {
            $x = 0;
            $y = 0;
            foreach ($cable as $direction) {
                $dir = $direction['dir'];
                $length = $direction['length'];
                $dx = 0;
                $dy = 0;
                switch ($dir) {
                    case 'U':
                        $dx = 0;
                        $dy = -1;
                        break;
                    case 'D':
                        $dx = 0;
                        $dy = 1;
                        break;
                    case 'L':
                        $dx = -1;
                        $dy = 0;
                        break;
                    case 'R':
                        $dx = 1;
                        $dy = 0;
                        break;
                }
                for ($i = 0; $i < $length; $i++) {
                    $x += $dx;
                    $y += $dy;
                    $position = "$x|$y";

                    if (!isset($grid[$position])) {
                        $grid[$position] = 0;
                    }
                    $grid[$position] |= $cableNr;
                }
            }
            $cableNr = $cableNr << 1;
        }
        return $grid;
    }

    /**
     * @return array
     */
    protected function getInputFormated()
    {
        $result = [];
        $input = $this->puzzle->getInput();
        $lines = explode("\n", $input);
        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }
            $directions = explode(",", $line);
            $cable = [];
            foreach ($directions as $direction) {
                $this->logger->info("$direction");
                if (preg_match('~(.)(\d+)~', $direction, $matches)) {
                    $cable[] = [
                        'dir' => $matches[1],
                        'length' => $matches[2],
                    ];
                }
            }
            $result[] = $cable;
        }
        return $result;
    }
}
