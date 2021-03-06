<?php

namespace App\Service;

class Day13Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputIntcode();
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->runProgram();
        $output = $computer->getOutput();
        
        $wallTiles = 0;
        for ($i = 2; $i < count($output); $i+=3) {
            if ($output[$i] == 2) {
                $wallTiles += 1;
            }
        }
        
        return $wallTiles;
    }

    public function part2()
    {
    }
}
