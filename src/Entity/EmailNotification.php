<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EmailNotificationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: EmailNotificationRepository::class)]
//#[ApiResource]
class EmailNotification
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'emailNotifications')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;



    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $message;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isSent = false;

    /**
     * @var NotificationSource|null
     */
    #[ORM\ManyToOne(targetEntity: NotificationSource::class)]
    private ?NotificationSource $source;

    /**
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $sentAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return bool|null
     */
    public function getIsSent(): ?bool
    {
        return $this->isSent;
    }

    /**
     * @param bool $isSent
     * @return $this
     */
    public function setIsSent(bool $isSent): self
    {
        $this->isSent = $isSent;

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

    /**
     * @return DateTimeImmutable|null
     */
    public function getSentAt(): ?DateTimeImmutable
    {
        return $this->sentAt;
    }

    /**
     * @param DateTimeImmutable|null $sentAt
     * @return $this
     */
    public function setSentAt(?DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }
}
