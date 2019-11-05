<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Score;
use App\Entity\Gallery;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 */
class Result
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Score", cascade={"persist"}, orphanRemoval=true)
    * @ORM\JoinColumn(nullable=true)
    */
    private $score;

    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Gallery", cascade={"persist"}, orphanRemoval=true)
    */
    private $files;

    public function __construct()
    {
        $this->setScore(new Score());
        $this->setFiles(new Gallery());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?Score
    {
        return $this->score;
    }

    public function setScore(?Score $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getFiles(): ?Gallery
    {
        return $this->files;
    }

    public function setFiles(?Gallery $files): self
    {
        $this->files = $files;

        return $this;
    }
}
