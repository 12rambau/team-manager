<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Btba\ChatBundle\Model\BaseChatMessage;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatMessageRepository")
 */
class ChatMessage extends BaseChatMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $author;

}
