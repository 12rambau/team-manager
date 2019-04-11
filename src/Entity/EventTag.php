<?php

namespace App\Entity;

use App\Entity\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventTagRepository")
 */
class EventTag
{
    const COLORS = [
        "primary"=>"primary", 
        "secondary"=>"secondary", 
        "success"=> "success", 
        "danger"=>"danger", 
        "warning"=>"warning", 
        "info"=>"info", 
        "light"=>"light", 
        "dark"=>"dark"
    ];

    const HEX_COLORS = [
        "primary"=>"#007bff", 
        "secondary"=>"#6c757d", 
        "success"=>"#28a745", 
        "danger"=>"#dc3545", 
        "warning"=>"#ffc107", 
        "info"=>"#17a2b8", 
        "light"=>"#f8f9fa", 
        "dark"=>"#343a40"
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
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="tag", cascade={"persist"})
     */
    private $events;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(callback="getColors")
     */
    private $color;

    /**
    * @ORM\Column(type="string", length=7)
    * @Assert\Regex(pattern="/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", message="this is not a color")
    */
    private $hexColor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->active = true;
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

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setTag($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getTag() === $this) {
                $event->setTag(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;
        $this->setHexColor();

        return $this;
    }

    public function getColors()
    {
        return $this::COLORS;
    }

    public function getHexColor(): ?string
    {
        return $this->hexColor;
    }

    public function setHexColor(): self
    {
        $this->hexColor = ($this->color)?$this::HEX_COLORS[$this->color]:'';        

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
