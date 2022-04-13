<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EmailTemplateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: EmailTemplateRepository::class)]
#[ApiResource]
class EmailTemplate
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
    private string $messageTemplate;

    /**
     * @var NotificationSource
     */
    #[ORM\OneToOne(targetEntity: NotificationSource::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private NotificationSource $notificationSource;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessageTemplate(): string
    {
        return $this->messageTemplate;
    }

    /**
     * @param string $messageTemplate
     * @return $this
     */
    public function setMessageTemplate(string $messageTemplate): self
    {
        $this->messageTemplate = $messageTemplate;

        return $this;
    }

    /**
     * @return NotificationSource|null
     */
    public function getNotificationSource(): ?NotificationSource
    {
        return $this->notificationSource;
    }

    /**
     * @param NotificationSource $notificationSource
     * @return $this
     */
    public function setNotificationSource(NotificationSource $notificationSource): self
    {
        $this->notificationSource = $notificationSource;

        return $this;
    }
}
