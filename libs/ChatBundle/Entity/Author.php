<?php

namespace BornToBeAlive\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BornToBeAlive\ChatBundle\Repository\AuthorRepository")
 */
class Author
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string")
    */
    private $username;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(?string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
