<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NotificationSourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * La source d'une notification (ou d'une notification par courriel).
 * Il peut s'agir d'alertes d'offres, d'actualités, d'administration, etc.
 */
#[ORM\Entity(repositoryClass: NotificationSourceRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get']
)]
class NotificationSource
{
    const NEW_OFFER = 'NEW_OFFER';
    const GROUP_DEMAND = 'GROUP_DEMAND';
    const EVENT_NOTIFICATION_ONE_DAY_BEFORE = 'EVENT_NOTIFICATION_ONE_DAY_BEFORE';
    const EVENT_NOTIFICATION_ONE_DAY_BEFORE_THE_END = 'EVENT_NOTIFICATION_ONE_DAY_BEFORE_THE_END';   

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
    private string $sourceLabel;

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
    public function getSourceLabel(): ?string
    {
        return $this->sourceLabel;
    }

    /**
     * @param string $sourceLabel
     * @return $this
     */
    public function setSourceLabel(string $sourceLabel): self
    {
        $this->sourceLabel = $sourceLabel;

        return $this;
    }
}
