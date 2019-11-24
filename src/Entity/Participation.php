<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipationRepository")
 */
class Participation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
    * @ORM\Column(type="boolean", nullable=true)
    */
    private $value;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $lastUpdate;
    
    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="participations", cascade={"persist"})
    */
    private $user;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="participations", cascade={"persist"})
    */
    private $event;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Position", mappedBy="participation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $position;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\PersonnalStat", mappedBy="participation", cascade={"persist"}, orphanRemoval=true)
    * @ORM\JoinColumn(nullable=true)
    */
    private $stats;

    public function __construct()
    {
        $this->lastUpdate = null;
        $this->value = null;
        $this->stats = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getEvent()->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getValue(): ?bool
    {
        return $this->value;
    }

    public function setValue(?bool $value): self
    {
            $this->value = $value;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|PersonnalStat[]
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(PersonnalStat $stat): self
    {
        if (!$this->stats->contains($stat)) {
            $this->stats[] = $stat;
            $stat->setParticipation($this);
        }

        return $this;
    }

    public function removeStat(PersonnalStat $stat): self
    {
        if ($this->stats->contains($stat)) {
            $this->stats->removeElement($stat);
            // set the owning side to null (unless already changed)
            if ($stat->getParticipation() === $this) {
                $stat->setParticipation(null);
            }
        }

        return $this;
    }
}
