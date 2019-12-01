<?php

namespace App\Service;

use App\Entity\Puzzle;
use App\Repository\PuzzleRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

class PuzzleService
{
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
     * @var string
     */
    protected $aocSession;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, $aocSession = '')
    {
        $this->logger = $logger;
        $this->entityManager = $em;
        $this->puzzleRepo = $em->getRepository(Puzzle::class);
        $this->aocSession = $aocSession;
    }

    /**
     * @param int $day
     * @return boolean
     */
    public function downloadInput(int $day)
    {
        if ($day < 1 || $day > 24) {
            $this->logger->error("Day $day is not valid");
            return false;
        }

        if (!$this->isPuzzleAvailable()) {
            $this->logger->error("This puzzle is not yet available!");
            return false;
        }

        $puzzle = $this->puzzleRepo->findOneBy(['day' => $day, 'is_test' => false]);
        if (!is_null($puzzle)) {
            $this->logger->error("Day $day was already downloaded");
            return false;
        }

        if (empty($this->aocSession)) {
            $this->logger->error("No AOC_SESSION configured!");
            return false;
        }

        $url = "https://adventofcode.com/2019/day/$day/input";
        $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray([
            'session' => $this->aocSession,
        ], 'adventofcode.com');
        $client = new Client();

        try {
            /** @var Response $response */
            $response = $client->request('GET', $url, [
                'cookies' => $cookieJar,
            ]);
        } catch (GuzzleException $e) {
            $this->logger->error("Guzzle Exception: " . $e->getMessage());
            return false;
        }

        if (200 !== $response->getStatusCode()) {
            $this->logger->error($response->getStatusCode() . ' ' . $response->getReasonPhrase());
            return false;
        }

        $puzzle = new Puzzle();
        $puzzle->setDay($day)->setIsTest(false)->setInput($response->getBody());
        $this->entityManager->persist($puzzle);;
        $this->entityManager->flush();

        return true;
    }

    /**
     * Calculate the date and return true if the puzzle of this day should be available
     * @param int $day
     * @return boolean
     */
    public function isPuzzleAvailable(int $day)
    {
        try {
            $dateNow = new \DateTime();
            $datePuzzleEnabled = \DateTime::createFromFormat('Y-m-d-H-i', "2019-12-$day-06-00");
            $timeLeft = $datePuzzleEnabled->diff($dateNow);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        if ($timeLeft->invert) {
            return false;
        }
        return true;
    }
}
