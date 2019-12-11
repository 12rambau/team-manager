<?php

namespace btba\ChatBundle\Model;

class BaseAuthor
{
    /**
     * @var mixed
     */
    protected $id;

    /**
    * @var string
    */
    protected $username;

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
