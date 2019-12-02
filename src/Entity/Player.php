<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="players", cascade={"persist"})
    */
    private $team;

    /**
    * @ORM\ManyToMany(targetEntity="App\Entity\PlayerTag", inversedBy="players", cascade={"persist"})
    */
    private $tags;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="player", cascade={"persist"})
    */
    private $user;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Feature", mappedBy="player", cascade={"persist"}, orphanRemoval=true)
    */
    private $features;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="player", cascade={"persist"}, orphanRemoval=true)
     */
    private $participations;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->features = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUser()->getUsername()." in ".$this->getTeam()->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return Collection|PlayerTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(PlayerTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPlayer($this);
        }

        return $this;
    }

    public function removeTag(PlayerTag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePlayer($this);
        }

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

    /**
     * @return Collection|Feature[]
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
            $feature->setPlayer($this);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
            // set the owning side to null (unless already changed)
            if ($feature->getPlayer() === $this) {
                $feature->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setPlayer($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getPlayer() === $this) {
                $participation->setPlayer(null);
            }
        }

        return $this;
    }
}
