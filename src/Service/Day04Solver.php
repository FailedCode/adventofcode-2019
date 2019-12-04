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
        $range = $this->getInputFormated();

        $validPasswordCount = 0;
        for ($i = $range[0]; $i <= $range[1]; $i++) {
            if ($this->checkValidity2($i)) {
                $validPasswordCount += 1;
            }
        }

        return $validPasswordCount;
    }

    protected function checkValidity2($number)
    {
        $digits = str_split($number);
        $repeatCounter = [];
        $lastDigit = null;
        foreach ($digits as $digit) {
            if (!is_null($lastDigit)) {
                if ($lastDigit == $digit) {
                    if (!isset($repeatCounter[$digit])) {
                        $repeatCounter[$digit] = 0;
                    }
                    $repeatCounter[$digit] += 1;
                }
                if ($lastDigit > $digit) {
                    return false;
                }
            }
            $lastDigit = $digit;
        }

        foreach ($repeatCounter as $digit => $count) {
            if ($count == 1) {
                return true;
            }
        }
        return false;
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
