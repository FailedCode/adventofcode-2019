<?php

namespace App\Controller;

use App\Entity\Puzzle;
use App\Repository\PuzzleRepository;
use App\Service\PuzzleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Displays a website for puzzles and solutions
 *
 * @package App\Controller
 */
class WebController extends AbstractController
{
    /**
     * @var PuzzleService
     */
    protected $puzzleService;

    /**
     * WebController constructor.
     * @param PuzzleService $puzzleService
     */
    public function __construct(PuzzleService $puzzleService)
    {
        $this->puzzleService = $puzzleService;
    }

    /**
     * @Route("/", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('web/index.html.twig', [
        ]);
    }

    /**
     * @Route("/day/{day}", name="showDay")
     * @param int $day
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showDay(int $day)
    {
        /** @var PuzzleRepository $puzzleRepo */
        $puzzleRepo = $this->getDoctrine()->getRepository(Puzzle::class);
        $puzzle = $puzzleRepo->findOneBy(['day' => $day, 'is_test' => false]);
        $isAvailable = $this->puzzleService->isPuzzleAvailable($day);
        if ($isAvailable && is_null($puzzle)) {
            $this->addFlash(
                'notice',
                'this puzzle was not yet downloaded'
            );
            $downloadSuccess = $this->puzzleService->downloadInput($day);
            if ($downloadSuccess) {
                $this->addFlash('success', 'Puzzle input downloaded!');
            } else {
                $this->addFlash('error', 'Puzzle download failed! Check logfile for more information.');
            }
        }

        if (!$isAvailable) {
            $this->addFlash(
                'notice',
                'This puzzle is not yet available'
            );
        }

        return $this->render('web/day.html.twig', [
            'day' => $day,
            'puzzle' => $puzzle,
            'puzzleAvailable' => $isAvailable,
        ]);
    }

    /**
     * @Route("/day/{day}/input", name="showInput")
     * @param int $day
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showInput(int $day)
    {
        /** @var PuzzleRepository $puzzleRepo */
        $puzzleRepo = $this->getDoctrine()->getRepository(Puzzle::class);
        $puzzle = $puzzleRepo->findOneBy(['day' => $day, 'is_test' => false]);
        $isAvailable = $this->puzzleService->isPuzzleAvailable($day);
        $respone = new Response();
        $respone->headers->set('Content-Type', 'text/plain');

        if (!$isAvailable) {
            $respone->setContent('Puzzle not yet available!');
            return $respone;
        }

        if (is_null($puzzle)) {
            $respone->setContent('Puzzle not yet downloaded!');
        } else {
            $respone->setContent($puzzle->getInput());
        }

        return $respone;
    }
}
