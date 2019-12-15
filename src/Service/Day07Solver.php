<?php

namespace App\Service;

class Day07Solver extends AbstractDaySolver
{
    public function part1()
    {
        $program = $this->getInputFormated();

        $phaseCombinations = $this->createPhaseSettings();

        $largestSignal = 0;
        foreach ($phaseCombinations as $phaseCombination) {
            $input = 0;
            foreach ($phaseCombination as $phaseSetting) {
                $computer = new IntcodeComputer();
                $computer->setCode($program);
                $computer->addInput($phaseSetting);
                $computer->addInput($input);
                $computer->runProgram();
                $output = $computer->getOutput();
                $input = array_pop($output);
            }
            if ($input > $largestSignal) {
                $largestSignal = $input;
            }
        }

        return $largestSignal;
    }

    public function part2()
    {
        $program = $this->getInputFormated();

        $phaseCombinations = $this->createPhaseSettings(5, 9);

        /** @var IntcodeComputer[] $computer */
        $computer = [];
        $maxComputer = 5;
        $largestSignal = 0;
        foreach ($phaseCombinations as $phaseCombination) {
            $input = 0;
            $currentComputer = 0;
            foreach ($phaseCombination as $phaseSetting) {
                $computer[$currentComputer] = $this->initComputer($program, $phaseSetting);
                $currentComputer += 1;
            }

            $currentComputer = 0;
            while (true) {
                $computer[$currentComputer]->addInput($input);
                $computer[$currentComputer]->runProgram(true);
                $output = $computer[$currentComputer]->getOutput();
                $input = array_pop($output);
                if ($currentComputer == 4 && $computer[$currentComputer]->hasHaltet()) {
                    break;
                }

                $currentComputer = ($currentComputer + 1) % $maxComputer;
            }

            if ($input > $largestSignal) {
                $largestSignal = $input;
            }
        }

        return $largestSignal;
    }

    /**
     * @param $program
     * @return IntcodeComputer
     */
    protected function initComputer($program, $phaseSetting)
    {
        $computer = new IntcodeComputer();
        $computer->setCode($program);
        $computer->addInput($phaseSetting);
        return $computer;
    }

    /**
     * Iterate over all possibilities
     * Ugly but no better idea right now...
     * @return array
     */
    protected function createPhaseSettings($min = 0, $max = 4)
    {
        // generate 00000-44444
        $len = 5;
        $result = [];
        for ($i1 = $min; $i1 <= $max; $i1++) {
            for ($i2 = $min; $i2 <= $max; $i2++) {
                for ($i3 = $min; $i3 <= $max; $i3++) {
                    for ($i4 = $min; $i4 <= $max; $i4++) {
                        for ($i5 = $min; $i5 <= $max; $i5++) {
                            $result[] = [$i1, $i2, $i3, $i4, $i5];
                        }
                    }
                }
            }
        }

        // remove entries with douple digits
        foreach ($result as $key => $item) {
            for ($i1 = 0; $i1 < $len; $i1++) {
                for ($i2 = 0; $i2 < $len; $i2++) {
                    if ($i1 != $i2 && $item[$i1] == $item[$i2]) {
                        unset($result[$key]);
                    }
                }
            }
        }

        return $result;
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
