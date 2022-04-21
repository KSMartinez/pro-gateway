<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Group\ListGroupDemandsAction;
use App\Repository\GroupRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
#[ApiResource(collectionOperations: [
    'get', 'post',
    'list_group_demands' => [
        'method' => 'get',
        'path' => '/groups/demandesGroupe',
        'controller' => ListGroupDemandsAction::class
    ]
])]
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
}
