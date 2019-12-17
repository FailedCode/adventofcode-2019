<?php

namespace App\Service;

class Day02Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputIntcode();
        $program[1] = 12;
        $program[2] = 2;
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->runProgram();
        return $computer->getResult();
    }

    public function part2()
    {
        $program = $this->getInputIntcode();
        $target = 19690720;

        for ($noun = 0; $noun <= 99; $noun++) {
            for ($verb = 0; $verb <= 99; $verb++) {
                $program[1] = $noun;
                $program[2] = $verb;
                $computer = new IntcodeComputer();
                $computer->setCode($program);
                $computer->runProgram();
                if ($computer->getResult() == $target) {
                    return 100 * $noun + $verb;
                }
            }
        }

        return 'Target missed';
    }
}
