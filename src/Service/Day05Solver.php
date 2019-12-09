<?php

namespace App\Service;

class Day05Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputFormated();
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->addInput(1);
        $computer->runProgram();
        $output = $computer->getOutput();
        return array_pop($output);
    }

    public function part2()
    {
        $program = $this->getInputFormated();
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->addInput(5);
        $computer->runProgram();
        $output = $computer->getOutput();
        return array_pop($output);
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
