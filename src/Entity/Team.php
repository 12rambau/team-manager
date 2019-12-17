<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    const MAX_DESC = 200;

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
     * @ORM\Column(type="text")
     * @Assert\Length(max = Team::MAX_DESC, maxMessage = "Your descripsion is too long")
     */
    private $descripsion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\PlayerTag", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $tags;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $players;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\FeatureTag", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $features;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $events;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\EventTag", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $eventTags;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\StatTag", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $statTags;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\FieldTemplate", mappedBy="team", cascade={"persist"}, orphanRemoval=true)
    */
    private $fieldTemplates;

    /**
    * @ORM\OneToOne(targetEntity="App\Entity\BlogPost", cascade={"persist"}, orphanRemoval=true)
    */
    private $page;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->features = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->eventTags = new ArrayCollection();
        $this->statTags = new ArrayCollection();
        $this->fieldTemplates = new ArrayCollection();

        //create the default eventTag
        $tag = new EventTag();
        $tag->setName('trainning');
        $tag->setColor('success');
        $this->addEventTag($tag);

        $tag = new EventTag();
        $tag->setName('Race');
        $tag->setColor('primary');
        $this->addEventTag($tag);

        $tag = new EventTag();
        $tag->setName('other');
        $tag->setColor('warning');
        $this->addEventTag($tag);

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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescripsion(): ?string
    {
        return $this->descripsion;
    }

    public function setDescripsion(string $descripsion): self
    {
        $this->descripsion = $descripsion;

        return $this;
    }

    /**
     * @return Collection|PlayerTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(PlayerTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setTeam($this);
        }

        return $this;
    }

    public function removeTag(PlayerTag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getTeam() === $this) {
                $tag->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FeatureTag[]
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(FeatureTag $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
            $feature->setTeam($this);
        }

        return $this;
    }

    public function removeFeature(FeatureTag $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
            // set the owning side to null (unless already changed)
            if ($feature->getTeam() === $this) {
                $feature->setTeam(null);
            }
        }

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
            $event->setTeam($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getTeam() === $this) {
                $event->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventTag[]
     */
    public function getEventTags(): Collection
    {
        return $this->eventTags;
    }

    public function addEventTag(EventTag $eventTag): self
    {
        if (!$this->eventTags->contains($eventTag)) {
            $this->eventTags[] = $eventTag;
            $eventTag->setTeam($this);
        }

        return $this;
    }

    public function removeEventTag(EventTag $eventTag): self
    {
        if ($this->eventTags->contains($eventTag)) {
            $this->eventTags->removeElement($eventTag);
            // set the owning side to null (unless already changed)
            if ($eventTag->getTeam() === $this) {
                $eventTag->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StatTag[]
     */
    public function getStatTags(): Collection
    {
        return $this->statTags;
    }

    public function addStatTag(StatTag $statTag): self
    {
        if (!$this->statTags->contains($statTag)) {
            $this->statTags[] = $statTag;
            $statTag->setTeam($this);
        }

        return $this;
    }

    public function removeStatTag(StatTag $statTag): self
    {
        if ($this->statTags->contains($statTag)) {
            $this->statTags->removeElement($statTag);
            // set the owning side to null (unless already changed)
            if ($statTag->getTeam() === $this) {
                $statTag->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FieldTemplate[]
     */
    public function getFieldTemplates(): Collection
    {
        return $this->fieldTemplates;
    }

    public function addFieldTemplate(FieldTemplate $fieldTemplate): self
    {
        if (!$this->fieldTemplates->contains($fieldTemplate)) {
            $this->fieldTemplates[] = $fieldTemplate;
            $fieldTemplate->setTeam($this);
        }

        return $this;
    }

    public function removeFieldTemplate(FieldTemplate $fieldTemplate): self
    {
        if ($this->fieldTemplates->contains($fieldTemplate)) {
            $this->fieldTemplates->removeElement($fieldTemplate);
            // set the owning side to null (unless already changed)
            if ($fieldTemplate->getTeam() === $this) {
                $fieldTemplate->setTeam(null);
            }
        }

        return $this;
    }

    public function getPage(): ?BlogPost
    {
        return $this->page;
    }

    public function setPage(?BlogPost $page): self
    {
        $this->page = $page;

        return $this;
    }
}
