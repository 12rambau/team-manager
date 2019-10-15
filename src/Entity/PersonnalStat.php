<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonnalStatRepository")
 */
class PersonnalStat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="boolean")
     */
    private $timer;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\StatTag", inversedBy="stats", cascade={"persist"})
    * @ORM\Joincolumn(nullable=false)
    */
    private $tag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTimer(): ?bool
    {
        return $this->timer;
    }

    public function setTimer(bool $timer): self
    {
        $this->timer = $timer;

        return $this;
    }

    public function getTag(): ?StatTag
    {
        return $this->tag;
    }

    public function setTag(?StatTag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
