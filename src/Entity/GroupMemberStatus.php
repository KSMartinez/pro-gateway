<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupMemberStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: GroupMemberStatusRepository::class)]
#[ApiResource(
       collectionOperations: ['get'],
       itemOperations      : ['get']
)]
class GroupMemberStatus
{
    const INVITE = 'INVITE';
    const ACTIF = 'ACTIF';
    const INACTIF = 'INACTIF';
    const REFUSE = 'REFUSE';
    const DEMANDE = 'DEMANDE';
    const IGNORE = 'IGNORE';
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["group_member.read"])]
    private ?int $id = null;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["group_member.read"])]
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

    public function __toString(): string
    {
        return $this->status;
    }
}
