<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Participation;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PositionRepository")
 */
class PositionCopy
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
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0, max = 100)
     */
    private $horizontal;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 0, max = 100)
     */
    private $vertical;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FieldTemplate", inversedBy="positions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fieldTemplate;

    /**
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToOne(targetEntity="App\Entity\Participation", inversedBy="position", cascade={"persist"})
     */
    private $participation;

    public function __construct()
    {
        $this->horizontal = 0;
        $this->vertical = 0;
    }

    public function __clone()
    {
        $this->id = null;
        $this->field = null; //dealt by the field directly
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

    public function getHorizontal(): ?int
    {
        return $this->horizontal;
    }

    public function setHorizontal(int $horizontal): self
    {
        $this->horizontal = $horizontal;

        return $this;
    }

    public function getVertical(): ?int
    {
        return $this->vertical;
    }

    public function setVertical(int $vertical): self
    {
        $this->vertical = $vertical;

        return $this;
    }

    public function getParticipation(): ?Participation
    {
        return $this->participation;
    }

    public function setParticipation(?Participation $participation): self
    {
        $oldParticipation = $this->participation;
        $this->participation = $participation;

        // set (or unset) the owning side of the relation if necessary 
        if ($oldParticipation !== null) {
            $oldParticipation->setPosition(null);
        }
        if ($participation !== null) {
            if ($this !== $participation->getPosition()) {
                $participation->setPosition($this);
            }
        }

        return $this;
    }

    public function getFieldTemplate(): ?FieldTemplate
    {
        return $this->fieldTemplate;
    }

    public function setFieldTemplate(?FieldTemplate $fieldTemplate): self
    {
        $this->fieldTemplate = $fieldTemplate;

        return $this;
    }
}