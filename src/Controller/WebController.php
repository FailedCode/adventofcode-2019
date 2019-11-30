<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('web/index.html.twig', [
        ]);
    }

    /**
     * @Route("/day/{day}", name="showDay")
     */
    public function showDay(int $day)
    {
        return $this->render('web/day.html.twig', [
            'day' => $day,
        ]);
    }
}
