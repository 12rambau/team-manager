<?php

namespace App\Entity;

use App\ENtity\FeatureTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeatureRepository")
 */
class Feature
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
    private $value;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\FeatureTag", inversedBy="features", cascade={"persist"})
    * @ORM\JoinColumn(nullable=false)
    */
    private $tag;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="features", cascade={"persist"})
    */
    private $player;

    public function __toString()
    {
        $str =  $this->getTag()->getName()." - ".$this->getPlayer()->getUser()->getUsername();
        return $str;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTag(): ?FeatureTag
    {
        return $this->tag;
    }

    public function setTag(?FeatureTag $tag): self
    {
        $this->tag = $tag;
        $tag->addFeature($this);

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;
        $player->addFeature($this);

        return $this;
    }
}
