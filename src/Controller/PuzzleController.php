<?php

namespace App\Controller;

use App\Entity\Puzzle;
use App\Repository\PuzzleRepository;
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
abstract class PuzzleController extends AbstractController
{
    /**
     * @var int
     */
    protected $day = 0;

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
     * @var Puzzle
     */
    protected $puzzle;

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
        // self:: refers to the scope at the point of definition not at the point of execution
        // https://stackoverflow.com/questions/151969/when-to-use-self-over-this
        // self::class will result in
        //   Dynamic class names are not allowed in compile-time ::class fetch in <file>
        if (preg_match('~Day(\d+)~', static::class, $match)) {
            $this->day = (int)$match[1];
        }
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
        $this->puzzle = $this->puzzleRepo->find($puzzleId);
        if (!$puzzleId || is_null($this->puzzle)) {
            $this->logger->error("No Puzzle-ID");
            return $this->json(['error' => true, 'message' => "No Puzzle! ($puzzleId)"]);
        }

        if ($part == 1) {
            $this->puzzle->setSolution1($request->get('value'));
        }
        if ($part == 2) {
            $this->puzzle->setSolution2($request->get('value'));
        }
        $this->entityManager->persist($this->puzzle);
        $this->entityManager->flush();

        return $this->json([]);
    }
}
