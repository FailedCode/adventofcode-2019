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
    }

    /**
     * Iterate over all possibilities
     * Ugly but no better idea right now...
     * @return array
     */
    protected function createPhaseSettings()
    {
        // generate 00000-55555
        $len = 5;
        $result = [];
        for ($i1 = 0; $i1 < $len; $i1++) {
            for ($i2 = 0; $i2 < $len; $i2++) {
                for ($i3 = 0; $i3 < $len; $i3++) {
                    for ($i4 = 0; $i4 < $len; $i4++) {
                        for ($i5 = 0; $i5 < $len; $i5++) {
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
