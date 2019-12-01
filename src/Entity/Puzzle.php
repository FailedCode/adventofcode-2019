<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PuzzleRepository")
 */
class Puzzle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $day;

    /**
     * @ORM\Column(type="text")
     */
    private $input = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $runtime = '';

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_test;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $solution1 = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $solution2 = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function setInput(string $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function getRuntime(): ?string
    {
        return $this->runtime;
    }

    public function setRuntime(string $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getIsTest(): ?bool
    {
        return $this->is_test;
    }

    public function setIsTest(bool $is_test): self
    {
        $this->is_test = $is_test;

        return $this;
    }

    public function getSolution1(): ?string
    {
        return $this->solution1;
    }

    public function setSolution1(string $solution1): self
    {
        $this->solution1 = $solution1;

        return $this;
    }

    public function getSolution2(): ?string
    {
        return $this->solution2;
    }

    public function setSolution2(string $solution2): self
    {
        $this->solution2 = $solution2;

        return $this;
    }
}
