<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FieldRepository")
 */
class Field
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\PlayerPosition", mappedBy="field", cascade={"persist"}, orphanRemoval=true)
    */
    private $positions;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\FieldTemplate", inversedBy="fields", cascade={"persist"})
    */
    private $template;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="fields")
    */
    private $event;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function __toString()
    {
        return "#".$this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(): self
    {
        $this->updateAt = new \DateTime();

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

    /**
     * @return Collection|PlayerPosition[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(PlayerPosition $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setField($this);
        }

        return $this;
    }

    public function removePosition(PlayerPosition $position): self
    {
        if ($this->positions->contains($position)) {
            $this->positions->removeElement($position);
            // set the owning side to null (unless already changed)
            if ($position->getField() === $this) {
                $position->setField(null);
            }
        }

        return $this;
    }

    public function getTemplate(): ?FieldTemplate
    {
        return $this->template;
    }

    public function setTemplate(?FieldTemplate $template): self
    {
        $this->template = $template;

        //create the player positions 
        foreach ($template->getPositions() as $position) 
            $this->addPosition(new PlayerPosition());

        return $this;
    }
}
