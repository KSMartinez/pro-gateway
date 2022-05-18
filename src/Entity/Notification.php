<?php

namespace App\Entity;

use App\Entity\Offer;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NotificationRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 *
 */
#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put', 'delete']
)]
class Notification
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $message;

    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $createdOn;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isRead = false;

    /**
     * @var NotificationSource|null
     */
    #[ORM\ManyToOne(targetEntity: NotificationSource::class)]
    private ?NotificationSource $source;


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
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

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
     * @return DateTimeInterface|null
     */
    public function getCreatedOn(): ?DateTimeInterface
    {
        return $this->createdOn;
    }

    /**
     * @param DateTimeInterface $createdOn
     * @return $this
     */
    public function setCreatedOn(DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     * @return $this
     */
    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * @return NotificationSource|null
     */
    public function getSource(): ?NotificationSource
    {
        return $this->source;
    }

    /**
     * @param NotificationSource|null $source
     * @return $this
     */
    public function setSource(?NotificationSource $source): self
    {
        $this->source = $source;

        return $this;
    }
}
