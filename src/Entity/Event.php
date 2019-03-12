<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use App\Entity\Location;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
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
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finish;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registerFinish;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxPlayers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
    * @ORM\Column(type="datetime", nullable=false)
    */
    private $publishDate;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\EventTag", inversedBy="events", cascade={"persist"})
    */
    private $tag;

    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Location", cascade={"persist"}, orphanRemoval=true)
    * @ORM\JoinColumn(nullable=false)
    */
    private $location;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="event", cascade={"persist"}, orphanRemoval=true)
    */
    private $participations;
    
    public function __construct()
    {
        $this->active = false;
        $this->location = new Location();
        $this->publishDate = new \DateTime();
        $this->participations = new ArrayCollection();
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
        $this->setSlug();

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;
        $this->setSlug();

        return $this;
    }

    public function getFinish(): ?\DateTimeInterface
    {
        return $this->finish;
    }

    public function setFinish(\DateTimeInterface $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    public function getRegisterFinish(): ?\DateTimeInterface
    {
        return $this->registerFinish;
    }

    public function setRegisterFinish(?\DateTimeInterface $registerFinish): self
    {
        $this->registerFinish = $registerFinish;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getMaxPlayers(): ?int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(?int $maxPlayers): self
    {
        $this->maxPlayers = $maxPlayers;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): self
    {
        $slugify = new Slugify();
        $date = ($this->start)?date_format($this->start, "Y-m-d"):"";
        $this->slug = $slugify->slugify($this->name.$date);
        
        return $this;
    }

    public function getTag(): ?EventTag
    {
        return $this->tag;
    }

    public function setTag(?EventTag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

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
            $participation->setEvent($this);
        }

        return $this;
    }

    public function getNbParticipationIn(): int
    {
        $nbParticipant = 0;
        foreach ($this->participations as $participation) 
        {
            if($participation->getValue() == 1)
                $nbParticipant++;
        }

        return $nbParticipant;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getEvent() === $this) {
                $participation->setEvent(null);
            }
        }

        return $this;
    }
}
