<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use App\Entity\Location;
use App\Entity\Result;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @SerializedName("title")
     * @Groups({"calendar"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @SerializedName("start")
     * @Groups({"calendar"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @SerializedName("end")
     * @Groups({"calendar"})
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
     * @SerializedName("url")
     * @Groups({"calendar"})
     */
    private $slug;

    /**
    * @ORM\Column(type="datetime", nullable=false)
    */
    private $publishDate;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\EventTag", inversedBy="events", cascade={"persist"})
    * @Groups({"calendar"})
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Field", mappedBy="event", cascade={"persist"}, orphanRemoval=true)
     */
    private $fields;

    /**
     * @ORM\Column(type="string", length=7)
     * @Assert\Regex(pattern="/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", message="this is not a color")
     * @SerializedName("color")
     * @Groups({"calendar"})
     * not necessary if I understand how to serialize the Tag color
     */
    private $color;

    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Result", cascade={"persist"}, orphanRemoval=true)
    */
    private $result;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\PersonnalStat", mappedBy="event", cascade={"persist"}, orphanRemoval=true)
    */
    private $stats;
    
    public function __construct()
    {
        $this->active = false;
        $this->location = new Location();
        $this->publishDate = new \DateTime();
        $this->participations = new ArrayCollection();
        $this->fields = new ArrayCollection();
        $this->result = new Result();
        $this->stats = new ArrayCollection();
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

    public function getPublishDate(): ?\DateTime
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTime $publishDate): self
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
            if($participation->getValue() == true)
                $nbParticipant++;
        }

        return $nbParticipant;
    }

    public function getNbParticipationOut(): int
    {
        $nbParticipant = 0;
        foreach ($this->participations as $participation) 
        {
            if($participation->getValue() == false && $participation->getLastUpdate() != null)
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

    /**
     * @return Collection|Field[]
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
            $field->setEvent($this);
        }

        return $this;
    }

    public function removeField(Field $field): self
    {
        if ($this->fields->contains($field)) {
            $this->fields->removeElement($field);
            // set the owning side to null (unless already changed)
            if ($field->getEvent() === $this) {
                $field->setEvent(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(): self
    {
        $this->color = $this->tag->getHexColor();

        return $this;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return Collection|PersonnaStat[]
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(PersonnalStat $stat): self
    {
        if (!$this->stats->contains($stat)) {
            $this->stats[] = $stat;
            $stat->setEvent($this);
        }

        return $this;
    }

    public function removeStat(PersonnalStat $stat): self
    {
        if ($this->stats->contains($stat)) {
            $this->stats->removeElement($stat);
            // set the owning side to null (unless already changed)
            if ($stat->getEvent() === $this) {
                $stat->setEvent(null);
            }
        }

        return $this;
    }
}
