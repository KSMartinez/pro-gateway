<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OfferStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: OfferStatusRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get']
)]
class OfferStatus
{

    const ATTENTE_DE_VALIDATION = 'ATTENTE_DE_VALIDATION';
    const REFUSE = 'REFUSE';
    const PUBLIEE = 'PUBLIEE';
    const ARCHIVEE = 'ARCHIVEE';
    const SUPPRIMEE = 'SUPPRIMEE';
    const POURVUE  = 'POURVUE';

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
    #[Groups(['offer:read'])]
    private string $label;

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
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
