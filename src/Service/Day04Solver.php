<?php

namespace App\Service;

class Day04Solver extends AbstractDaySolver
{
    public function part1()
    {
        $range = $this->getInputFormated();

        $validPasswordCount = 0;
        for ($i = $range[0]; $i <= $range[1]; $i++) {
            if ($this->checkValidity($i)) {
                $validPasswordCount += 1;
            }
        }

        return $validPasswordCount;
    }

    public function part2()
    {
    }

    protected function checkValidity($number)
    {
        $digits = str_split($number);
        $repeatFound = false;
        $lastDigit = null;
        foreach ($digits as $digit) {
            if (!is_null($lastDigit)) {
                if ($lastDigit == $digit) {
                    $repeatFound = true;
                }
                if ($lastDigit > $digit) {
                    return false;
                }
            }
            $lastDigit = $digit;
        }

        if ($repeatFound) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    protected function getInputFormated()
    {
        $input = trim($this->puzzle->getInput());
        return explode('-', $input);
    }
}
