<?php


namespace App\Service;


use App\Entity\Puzzle;
use Psr\Log\LoggerInterface;

abstract class AbstractDaySolver
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Puzzle
     */
    protected $puzzle;

    /**
     * AbstractDaySolver constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(Puzzle $puzzle, LoggerInterface $logger)
    {
        $this->puzzle = $puzzle;
        $this->logger = $logger;
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