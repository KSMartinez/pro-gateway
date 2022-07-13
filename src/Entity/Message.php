<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get', 'post'
    ],
    itemOperations      : [
        'get', 'delete'
    ])]
class Message
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $content;

    /**
     * @var Conversation
     */
    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private Conversation $conversation;

    /**
     * @var DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    /**
     * @var MessageStatus
     */
    #[ORM\ManyToOne(targetEntity: MessageStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    private MessageStatus $messageStatus;

    /**
     *
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable('now');
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Conversation|null
     */
    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    /**
     * @param Conversation $conversation
     * @return $this
     */
    public function setConversation(Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * @return MessageStatus|null
     */
    public function getMessageStatus(): ?MessageStatus
    {
        return $this->messageStatus;
    }

    /**
     * @param MessageStatus $messageStatus
     * @return $this
     */
    public function setMessageStatus(MessageStatus $messageStatus): self
    {
        $this->messageStatus = $messageStatus;

        return $this;
    }
}
