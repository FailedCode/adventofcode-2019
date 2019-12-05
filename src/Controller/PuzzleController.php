<?php

namespace App\Controller;

use App\Entity\Puzzle;
use App\Repository\PuzzleRepository;
use App\Service\AbstractDaySolver;
use App\Service\PuzzleService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Solves Puzzles and returns results over ajax
 *
 * @package App\Controller
 */
class PuzzleController extends AbstractController
{
    /**
     * @var PuzzleService
     */
    protected $puzzleService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var PuzzleRepository
     */
    protected $puzzleRepo;

    /**
     * PuzzleController constructor.
     * @param PuzzleService $puzzleService
     */
    public function __construct(PuzzleService $puzzleService, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->puzzleService = $puzzleService;
        $this->logger = $logger;
        $this->entityManager = $em;
        $this->puzzleRepo = $em->getRepository(Puzzle::class);
    }

    /**
     * @Route("save/solution", name="saveSolution")
     * @param Request $request
     * @return JsonResponse
     */
    public function saveSolution(Request $request)
    {
        $part = (int)$request->get('part', 0);
        $puzzleId = (int)$request->get('puzzle', 0);
        $puzzle = $this->puzzleRepo->find($puzzleId);
        if (!$puzzleId || is_null($puzzle)) {
            $this->logger->error("No Puzzle-ID");
            return $this->json(['error' => true, 'message' => "No Puzzle! ($puzzleId)"]);
        }

        if ($part == 1) {
            $puzzle->setSolution1($request->get('value'));
        }
        if ($part == 2) {
            $puzzle->setSolution2($request->get('value'));
        }
        $this->entityManager->persist($puzzle);
        $this->entityManager->flush();

        return $this->json([]);
    }

    /**
     * @Route("solve/day/{day}", name="solveDay")
     * @param Request $request
     * @param int $day
     * @return JsonResponse
     */
    public function solve(Request $request, int $day)
    {
        $part = (int)$request->get('part', 0);
        $puzzleId = (int)$request->get('puzzle', 0);
        $puzzle = $this->puzzleRepo->find($puzzleId);
        if (!$puzzleId || is_null($puzzle)) {
            $this->logger->error("No Puzzle-ID");
            return $this->json(['error' => true, 'message' => "No Puzzle! ($puzzleId)"]);
        }

        $n = str_pad($day, 2, '0', STR_PAD_LEFT);
        $solverClass = "App\Service\Day{$n}Solver";
        /** @var AbstractDaySolver $solver */
        if (!class_exists($solverClass)) {
            $this->logger->error("Class '$solverClass' not implementet!");
            return $this->json(['error' => true, 'message' => "Class '$solverClass' not implementet!"]);
        }
        $solver = new $solverClass($puzzle, $this->logger);

        $result = [];
        if ($part == 1 || $part == 0) {
            $part1Solution = $solver->part1();
            $result['part1'] = $part1Solution;
            $this->logger->info("Part1 : $part1Solution");
        }
        if ($part == 2 || $part == 0) {
            $part2Solution = $solver->part2();
            $result['part2'] = $part2Solution;
            $this->logger->info("Part2 : $part2Solution");
        }

        return $this->json($result);
    }

    /**
     * @Route("test/day/{day}", name="testDay")
     * @param Request $request
     * @param int $day
     * @return JsonResponse
     */
    public function test(Request $request, int $day)
    {
        $part = (int)$request->get('part', 0);
        $puzzleId = (int)$request->get('puzzle', 0);
        $puzzle = $this->puzzleRepo->find($puzzleId);
        if (!$puzzleId || is_null($puzzle)) {
            $this->logger->error("No Puzzle-ID");
            return $this->json(['error' => true, 'message' => "No Puzzle! ($puzzleId)"]);
        }

        $n = str_pad($day, 2, '0', STR_PAD_LEFT);
        $solverClass = "App\Service\Day{$n}Solver";
        /** @var AbstractDaySolver $solver */
        if (!class_exists($solverClass)) {
            $this->logger->error("Class '$solverClass' not implementet!");
            return $this->json(['error' => true, 'message' => "Class '$solverClass' not implementet!"]);
        }
        $solver = new $solverClass($puzzle, $this->logger);

        $result = [];
        if ($part == 1 || $part == 0) {
            $part1Solution = $solver->part1();
            $result['part1'] = [
                'equal' => $puzzle->getSolution1() == $part1Solution,
                'value' => $part1Solution,
            ];
        }
        if ($part == 2 || $part == 0) {
            $part2Solution = $solver->part2();
            $result['part2'] = [
                'equal' => $puzzle->getSolution2() == $part2Solution,
                'value' => $part2Solution,
            ];
        }

        return $this->json($result);
    }
}
