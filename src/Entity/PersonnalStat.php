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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $timer;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\StatTag", inversedBy="stats")
    * @ORM\Joincolumn(nullable=false)
    */
    private $tag;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Participation", inversedBy="stats")
    * @ORM\Joincolumn(nullable=false)
    */
    private $participation;

    public function __construct()
    {
        $this->setTimer(false);
    }


    public function __toString()
    {
        $name = $this->getPlayer()->__toString();
        $date = $date = ($this->getEvent()->getStart())?date_format($this->getEvent()->getStart(), "Y-m-d"):"";
        return $name." ".$date;
    }

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

    public function getParticipation(): ?Participation
    {
        return $this->participation;
    }

    public function setParticipation(?Participation $participation): self
    {
        $this->participation = $participation;

        return $this;
    }
}
