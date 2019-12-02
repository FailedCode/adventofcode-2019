<?php


namespace App\Service;


use App\Entity\Puzzle;

abstract class AbstractDaySolver
{
    /**
     * @var Puzzle
     */
    protected $puzzle;

    /**
     * @param Puzzle $puzzle
     */
    public function setPuzzle(Puzzle $puzzle)
    {
        $this->puzzle = $puzzle;
    }

    /**
     * @return mixed
     */
    abstract public function part1();

    /**
     * @return mixed
     */
    abstract public function part2();

    /**
     * @return array
     */
    protected function getInputFormated()
    {
        $input = $this->puzzle->getInput();
        return array_filter(explode("\n", $input), 'strlen');
    }
}
