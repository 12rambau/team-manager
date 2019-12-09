<?php

namespace App\Entity;

use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerTagRepository")
 */
class PlayerTag
{

    const COLORS = [
        "primary", 
        "secondary", 
        "success", 
        "danger", 
        "warning", 
        "info", 
        "light", 
        "dark"
    ];

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
     * @ORM\Column(type="string", length=255)
     * * @Assert\Choice(callback="getColors")
     */
    private $color;

    /**
    * @ORM\Column(type="boolean")
    */
    private $active;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="tags", cascade={"persist"})
    */
    private $team;

    /**
    * @ORM\ManyToMany(targetEntity="App\Entity\Player", mappedBy="tags", cascade={"persist"})
    */
    private $players;


    public function __construct()
    {
        $this->color = "primary";
        $this->players = new ArrayCollection();
        $this->active = true;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getColors()
    {
        return $this::COLORS;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
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
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
