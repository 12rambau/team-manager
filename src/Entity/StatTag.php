<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatTagRepository")
 */
class StatTag
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
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $unity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PersonnalStat", mappedBy="tag")
     */
    private $stats;

    /**
    * @ORM\Column(type="boolean")
    */
    private $active;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="statTags", cascade={"persist"})
    */
    private $team;

    public function __construct()
    {
        $this->stats = new ArrayCollection();
        $this->active = true;
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

    public function getUnity(): ?string
    {
        return $this->unity;
    }

    public function setUnity(?string $unity): self
    {
        $this->unity = $unity;

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
            $stat->setTag($this);
        }

        return $this;
    }

    public function removeStat(PersonnalStat $stat): self
    {
        if ($this->stats->contains($stat)) {
            $this->stats->removeElement($stat);
            // set the owning side to null (unless already changed)
            if ($stat->getTag() === $this) {
                $stat->setTag(null);
            }
        }

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
}
