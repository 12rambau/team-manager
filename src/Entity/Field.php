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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Image", cascade={"persist"})
    */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Position", mappedBy="field", cascade={"persist"}, orphanRemoval=true)
     */
    private $positions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="fields")
     */
    private $event;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
        $this->setUpdateAt();
        foreach ($this->positions->getIterator() as $position) {
            $position = clone $position;
            $this->addPosition($position);
        }
        $this->event = null; //handled by the controller
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): self
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);

        return $this;
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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setToto($file): self
    {
        $this->setImage(new Image);
        $this->getImage()->setImageFile($file);

        return $this;
    }

    public function getToto()
    {
        return ($this->getImage())?$this->getImage():null;
    }

    /**
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setField($this);
        }

        return $this;
    }

    public function removePosition(Position $position): self
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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
