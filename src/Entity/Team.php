<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripsion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\PlayerTag", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $tags;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $players;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\FeatureTag", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $features;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescripsion(): ?string
    {
        return $this->descripsion;
    }

    public function setDescripsion(string $descripsion): self
    {
        $this->descripsion = $descripsion;

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
            $tag->setTeam($this);
        }

        return $this;
    }

    public function removeTag(PlayerTag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getTeam() === $this) {
                $tag->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FeatureTag[]
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(FeatureTag $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
            $feature->setTeam($this);
        }

        return $this;
    }

    public function removeFeature(FeatureTag $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
            // set the owning side to null (unless already changed)
            if ($feature->getTeam() === $this) {
                $feature->setTeam(null);
            }
        }

        return $this;
    }
}
