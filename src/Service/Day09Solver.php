<?php

namespace App\Service;

class Day09Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputFormated();
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->addInput(1);
        $computer->runProgram();
        $output = $computer->getOutput();
        $this->logger->info("output", $output);
        return array_pop($output);
    }

    public function part2()
    {
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
