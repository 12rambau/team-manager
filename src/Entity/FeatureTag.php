<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//TODO add helper in string form for informations that needs choices

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeatureTagRepository")
 */
class FeatureTag
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
    * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="features", cascade={"persist"})
    */
    private $team;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Feature", mappedBy="tag", cascade={"persist"})
    */
    private $features;

    public function __construct()
    {
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
            $feature->setTag($this);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
            // set the owning side to null (unless already changed)
            if ($feature->getTag() === $this) {
                $feature->setTag(null);
            }
        }

        return $this;
    }
}
