<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * //todo refactor with string as ID
 */
#[ORM\Entity(repositoryClass: GroupStatusRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get']
)]
class GroupStatus
{
    const EN_ATTENTE = 'EN_ATTENTE';
    const CONFIRME = 'CONFIRME';
    const REFUSE = 'REFUSE';
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
    private string $status;

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
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
