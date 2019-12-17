<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use App\Entity\Comment;
use App\Entity\Gallery;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 * @UniqueEntity("gallery", message="Gallery for blog is unique")
 */
class BlogPost
{
    const MAX_SHORT = 200;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(max = BlogPost::MAX_SHORT, maxMessage = "Your short descripsion is too long")
     */
    private $short;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $editDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", cascade={"persist"}, orphanRemoval=true)
     */
    private $comments;

    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Gallery", cascade={"persist"},  mappedBy="blogPost")
    */
    private $gallery;

    public function __construct()
    {
        $this->setPublishDate();
        $this->active = false;
        $this->comments = new ArrayCollection();
        $this->setGallery(new Gallery);
    }

    public function __toString()
    {
        return substr($this->getTitle(), 0, 20);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->setSlug();

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): self
    {
        $this->short = $short;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(): self
    {
        $this->publishDate = new \DateTime();

        return $this;
    }

    public function getEditDate(): ?\DateTimeInterface
    {
        return $this->editDate;
    }

    public function setEditDate(): self
    {
        $this->editDate = new \DateTime();

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): self
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->title);
        $this->getGallery()->setName($this->getSlug());

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;
        if ($gallery->getBlogPost() != $this)
            $gallery->setBlogPost($this);

        return $this;
    }
}
