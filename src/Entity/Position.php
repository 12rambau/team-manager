<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PositionRepository")
 */
class Position
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

    public function __construct(int $horizontal=0, int $vertical=0)
    {
        $this->horizontal = $horizontal;
        $this->vertical = $vertical;
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
