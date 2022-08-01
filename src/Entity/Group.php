<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Group\CreateGroupDemandAction;
use App\Controller\Group\ListGroupDemandsAction;
use App\Controller\Group\RejectGroupDemandAction;
use App\Controller\Group\ValidateGroupDemandAction;
use App\Model\GroupDemand;
use App\Repository\GroupRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Constraints\Group as Assert;
use Symfony\Component\Validator\Constraints as AssertVendor;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiFilter(SearchFilter::class, properties={"name":"partial", "createdBy": "exact"})
 */
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
#[ApiResource(
       collectionOperations  : [
        'get',
        'list_group_demands' => [
            'method' => 'get',
            'path' => '/groups/demandes/',
            'controller' => ListGroupDemandsAction::class
        ],
        'create_new_group_demand' => [
            'method' => 'post',
            'path' => '/groups/demandes',
            'controller' => CreateGroupDemandAction::class
        ]
    ],
       itemOperations        : [
        'get','put', 'patch','delete',
        'validate_group_demand' => [
            'method' => 'post',
            'path' => '/groups/demande/{id}/validate',
            'security' => 'is_granted("ROLE_ADMIN")',
            'input' => GroupDemand::class,
            'controller' => ValidateGroupDemandAction::class
        ],
        'reject_group_demand' => [
            'method' => 'post',
            'path' => '/groups/demande/{id}/refuse',
            'security' => 'is_granted("ROLE_ADMIN")',
            'input' => GroupDemand::class,
            'controller' => RejectGroupDemandAction::class
        ]
    ],
       denormalizationContext: [
        'groups' => [
            'group:write'
        ]
    ], normalizationContext  : [
    'groups' => [
        'group:read'
    ]
]
)]
/**
 * @Vich\Uploadable()
 */
class Group implements ImageStockCompatibleInterface
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["group:read", "group:write"])]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @var string
     */
    #[Groups(["group:read", "group:write"])]
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    /**
     * @var DateTimeInterface
     */
    #[Groups(["group:read"])]
    #[ORM\Column(type: 'date')]
    private DateTimeInterface $dateCreated;

    /**
     * @var string|null
     */
    #[Groups(["group:read", "group:write"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    /**
     * @var GroupStatus
     */
    #[Groups(["group:read"])]
    #[ORM\ManyToOne(targetEntity: GroupStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    private GroupStatus $groupStatus;

    /**
     * @var User|null
     */
    #[Groups(["group:read"])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $createdBy;

    /**
     * @var Collection<int, GroupMember>
     */
    #[Groups(["group:read"])]
    #[ORM\OneToMany(mappedBy: 'groupOfMember', targetEntity: GroupMember::class, orphanRemoval: true)]
    private Collection $groupMembers;

    /**
     * @var Collection<int, Event>
     */
    #[Groups(["group:read"])]
    #[ORM\OneToMany(mappedBy: 'associatedGroup', targetEntity: Event::class)]
    private Collection $events;

    /**
     * @var bool|null
     */
    #[Groups(["group:read"])]
    #[ORM\Column(type: 'boolean', nullable:true)]
    private ?bool $isPublic;

    /**
     * @var bool|null
     */
    #[Groups(["group:read"])]
    #[ORM\Column(type: 'boolean', nullable:true)]
    private ?bool $isInstitutional;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(["group:read"])]
    private ?DateTimeInterface $graduationYear;

    /**
     * @var GroupCategory|null
     */
    #[ORM\ManyToOne(targetEntity: GroupCategory::class, inversedBy: 'groups')]
    #[Groups(["group:read"])]
    private ?GroupCategory $groupCategory;

    /**
     * @var EducationDomain|null
     */
    #[ORM\ManyToOne(targetEntity: EducationDomain::class, inversedBy: 'groups')]
    #[Groups(["group:read"])]
    private ?EducationDomain $educationDomain;

    /**
     * @var EducationComposante|null
     */
    #[ORM\ManyToOne(targetEntity: EducationComposante::class, inversedBy: 'groups')]
    #[Groups(['group:read'])]
    private ?EducationComposante $educationComposante;

    /**
     * @var EducationSpeciality|null
     */
    #[ORM\ManyToOne(targetEntity: EducationSpeciality::class, inversedBy: 'groups')]
    #[Groups(['group:read'])]
    private ?EducationSpeciality $educationSpeciality;

    #[ApiProperty(iri: 'http://schema.org/imageStockId')]
    #[Groups(['group:create'])]
    public ?string $imageStockId = null;

    #[ApiProperty(iri: 'http://schema.org/imageUrl')]
    #[Groups(['group:read'])]
    public ?string $imageUrl = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $imagePath = null;


    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="imagePath")
     */
    #[Groups(['group:updatePicture', 'group:create'])]
    #[Assert\ImageFileRequirements]
    public ?File $imageFile = null;


    public function __construct()
    {
        $this->groupMembers = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    /**
     * @return Collection<int, GroupMember>
     */
    public function getGroupMembers(): Collection
    {
        return $this->groupMembers;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAssociatedGroup($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAssociatedGroup() === $this) {
                $event->setAssociatedGroup(null);
            }
        }

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getIsInstitutional(): ?bool
    {
        return $this->isInstitutional;
    }

    public function setIsInstitutional(?bool $isInstitutional): self
    {
        $this->isInstitutional = $isInstitutional;

        return $this;
    }

    public function getGraduationYear(): ?\DateTimeInterface
    {
        return $this->graduationYear;
    }

    public function setGraduationYear(?\DateTimeInterface $graduationYear): self
    {
        $this->graduationYear = $graduationYear;

        return $this;
    }

    public function getGroupCategory(): ?GroupCategory
    {
        return $this->groupCategory;
    }

    public function setGroupCategory(?GroupCategory $groupCategory): self
    {
        $this->groupCategory = $groupCategory;

        return $this;
    }

    public function getEducationDomain(): ?EducationDomain
    {
        return $this->educationDomain;
    }

    public function setEducationDomain(?EducationDomain $educationDomain): self
    {
        $this->educationDomain = $educationDomain;

        return $this;
    }

    public function getEducationComposante(): ?EducationComposante
    {
        return $this->educationComposante;
    }

    public function setEducationComposante(?EducationComposante $educationComposante): self
    {
        $this->educationComposante = $educationComposante;

        return $this;
    }

    public function getEducationSpeciality(): ?EducationSpeciality
    {
        return $this->educationSpeciality;
    }

    public function setEducationSpeciality(?EducationSpeciality $educationSpeciality): self
    {
        $this->educationSpeciality = $educationSpeciality;

        return $this;
    }

    public function getImageStockId(): ?string
    {
        return $this->imageStockId;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }
}
