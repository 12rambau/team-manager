<?php

namespace btba\ChatBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use btba\ChatBundle\Model\BaseAuthor;


class BaseChatMessage
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var \DatetimeInterface
     */
    protected $date;

    /**
     * @var mixed
     */
    protected $author;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function __toString()
    {
        //TODO use intl function to display time
        return date_format($this->getDate(), "Y-m-d H:i:s");
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author): self
    {

        // assert something 

        $this->author = $author;

        return $this;
    }
}
