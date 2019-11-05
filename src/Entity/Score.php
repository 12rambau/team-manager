<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{

    const HOME_TEAM = "My Team"; //need to inject this name from parameters
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $opponent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $their;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $our;

    public function __toString()
    {
        return $this::HOME_TEAM. "  ".$this->getOur()." - ".$this->getTheir()."  ".$this->getOpponent();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHome(): string
    {
        return $this::HOME_TEAM;
    }

    public function getOpponent(): ?string
    {
        return $this->opponent;
    }

    public function setOpponent(string $opponent): self
    {
        $this->opponent = $opponent;

        return $this;
    }

    public function getTheir(): ?int
    {
        return $this->their;
    }

    public function setTheir(int $their): self
    {
        $this->their = $their;

        return $this;
    }

    public function getOur(): ?int
    {
        return $this->our;
    }

    public function setOur(int $our): self
    {
        $this->our = $our;

        return $this;
    }

    public function isWin(): bool
    {
        return ($this->our > $this->their);
    }
}
