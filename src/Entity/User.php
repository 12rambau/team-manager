<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\Image;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{

    // TODO clean the variable order 


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="author", cascade={"persist"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChatMessage", mappedBy="author", cascade={"persist"}, orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", cascade={"persist"}, orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $profilePic;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * true for male
     * false for female
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Regex(pattern="/^\+?[1-9]\d{1,14}$/", message="this is not a phone number")
     */
    private $phoneNumber;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
    */
    private $players;

    //magic function
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->players = new ArrayCollection();
    }

    public function __toString()
    {
        $str = $this->getFirstName() . " " . $this->getLastName();

        return $str;
    }

    //virtual properties
    public function getName(): string
    {
        return $this->getFirstName()." ".$this->getLastName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(BlogPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(BlogPost $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ChatMessage[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(ChatMessage $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(ChatMessage $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getProfilePic(): ?Image
    {
        return $this->profilePic;
    }

    public function setProfilePic(?Image $profilePic): self
    {
        $this->profilePic = $profilePic;

        return $this;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $player ? null : $this;
        if ($player->getUser() !== $newUser) {
            $player->setUser($newUser);
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
            $player->setUser($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getUser() === $this) {
                $player->setUser(null);
            }
        }

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }
}
