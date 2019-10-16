<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $opponent;

    /**
     * @ORM\Column(type="integer")
     */
    private $their;

    /**
     * @ORM\Column(type="integer")
     */
    private $our;

    public function getId(): ?int
    {
        return $this->id;
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
