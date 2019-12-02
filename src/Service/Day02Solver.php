<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Day02Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputFormated();
        $program[1] = 12;
        $program[2] = 2;
        return $this->runProgramm($program);
    }

    public function part2()
    {
    }

    /**
     * @param $program
     * @return int
     */
    protected function runProgramm($program)
    {
        $key = 0;
        while (true) {
            $opcode = $program[$key];
            $p1 = $program[$key+1];
            $p2 = $program[$key+2];
            $p3 = $program[$key+3];

            switch ($opcode) {
                case 99:
                    return $program[0];
                case 1:
                    $program[$p3] = $program[$p1] + $program[$p2];
                    break;
                case 2:
                    $program[$p3] = $program[$p1] * $program[$p2];
                    break;
            }
            $key += 4;
        }
        return $program[0];
    }

    /**
     * @return array
     */
    protected function getInputFormated()
    {
        $input = $this->puzzle->getInput();
        return array_map(function ($value){ return (int)$value; }, explode(",", $input));
    }
}
