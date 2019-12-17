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
    * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="field", cascade={"persist"}, orphanRemoval=true)
    */
    private $participations;

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
        $this->participations = new ArrayCollection();
        $this->setUpdateAt();
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

    public function getTemplate(): ?FieldTemplate
    {
        return $this->template;
    }

    public function setTemplate(?FieldTemplate $template): self
    {
        $this->template = $template;

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
            $participation->setField($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getField() === $this) {
                $participation->setField(null);
            }
        }

        return $this;
    }
}
