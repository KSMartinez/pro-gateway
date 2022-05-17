<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Group\ListGroupDemandsAction;
use App\Controller\Group\RejectGroupDemandAction;
use App\Controller\Group\ValidateGroupDemandAction;
use App\Model\GroupDemand;
use App\Repository\GroupRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
#[ApiResource(
    collectionOperations: [
        'get', 'post',
        'list_group_demands' => [
            'method' => 'get',
            'path' => '/groups/demandes/',
            'controller' => ListGroupDemandsAction::class
        ]],
    itemOperations: [
        'get','put','patch','delete',
        'validate_group_demand' => [
            'method' => 'post',
            'path' => '/groups/demande/{id}/validate',
            'security' => 'is_granted("ROLE_ADMIN")',
            'input'=> GroupDemand::class,
            'controller'  => ValidateGroupDemandAction::class
        ],
        'reject_group_demand' => [
            'method' => 'post',
            'path' => '/groups/demande/{id}/refuse',
            'security' => 'is_granted("ROLE_ADMIN")',
            'input' => GroupDemand::class,
            'controller' => RejectGroupDemandAction::class
        ]
    ])
]
class Group
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
    private string $name;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $dateCreated;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    /**
     * @var GroupStatus
     */
    #[ORM\ManyToOne(targetEntity: GroupStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    private GroupStatus $groupStatus;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $createdBy;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateCreated(): ?DateTimeInterface
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTimeInterface $dateCreated
     * @return $this
     */
    public function setDateCreated(DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return GroupStatus|null
     */
    public function getGroupStatus(): ?GroupStatus
    {
        return $this->groupStatus;
    }

    /**
     * @param GroupStatus $groupStatus
     * @return $this
     */
    public function setGroupStatus(GroupStatus $groupStatus): self
    {
        $this->groupStatus = $groupStatus;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
