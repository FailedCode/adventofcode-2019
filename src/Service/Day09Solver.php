<?php

namespace App\Service;

class Day09Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputIntcode();
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->addInput(1);
        $computer->runProgram();
        $output = $computer->getOutput();
        return array_pop($output);
    }

    public function part2()
    {
        $program = $this->getInputIntcode();
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->addInput(2);
        $computer->runProgram();
        $output = $computer->getOutput();
        return array_pop($output);
    }
}
