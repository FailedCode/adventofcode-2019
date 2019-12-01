<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Solves Day 1 Puzzle
 * @package App\Controller
 */
class Day01Controller extends PuzzleController
{
    /**
     * @Route("solve/day/1", name="day1")
     */
    public function solve(Request $request)
    {
        $part = (int)$request->get('part', 0);
        $puzzleId = (int)$request->get('puzzle', 0);
        $this->puzzle = $this->puzzleRepo->find($puzzleId);
        if (!$puzzleId || is_null($this->puzzle)) {
            $this->logger->error("No Puzzle-ID");
            return $this->json(['error' => true, 'message' => "No Puzzle! ($puzzleId)"]);
        }

        $result = [];
        if ($part == 1 || $part == 0) {
            $part1Solution = $this->part1();
            $result['part1'] = $part1Solution;
            $this->logger->info("Part1 : $part1Solution");
        }
        if ($part == 2 || $part == 0) {
            $part2Solution = $this->part2();
            $result['part2'] = $part2Solution;
            $this->logger->info("Part2 : $part2Solution");
        }

        return $this->json($result);
    }

    /**
     * @return int
     */
    public function part1()
    {
        $masses = $this->getInputFormated();
        $massSum = 0;
        foreach ($masses as $mass) {
            $massSum += floor((int)$mass / 3) - 2;
        }
        return $massSum;
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
        return array_filter(explode("\n", $input), 'strlen');
    }
}
