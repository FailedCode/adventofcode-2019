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
        $this->puzzleRepo = $em->getRepository(Puzzle::class);
        // self:: refers to the scope at the point of definition not at the point of execution
        // https://stackoverflow.com/questions/151969/when-to-use-self-over-this
        // self::class will result in
        //   Dynamic class names are not allowed in compile-time ::class fetch in <file>
        if (preg_match('~Day(\d+)~', static::class, $match)) {
            $this->day = (int)$match[1];
        }
    }
}
