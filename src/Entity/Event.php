<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use App\Controller\Event\UpdateImageStockAction;
use DateTime;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeInterface;
use Faker\Provider\UserAgent;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Event\UpdatePictureAction;
use App\Controller\Event\ParticipantListAction;
use Symfony\Component\HttpFoundation\File\File;
use App\Controller\Event\RandomEventsListAction;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Validator\Constraints\Event as Assert;
use Symfony\Component\Validator\Constraints as AssertVendor;
use App\Controller\Event\DownloadParticipantListAction;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiFilter(OrderFilter::class,properties={"startAt":"ASC"})
 * @ApiFilter(SearchFilter::class, properties={
 *      "date":"partial",
 *      "category":"exact",
 *      "description":"partial",
 *      "title":"partial",
 *      "university":"exact"
 * })
 */
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get'=> [
            'normalization_context' => [
                'groups' => [
                    'event:read'
                ],
                'openapi_definition_name' => 'read collection'
            ]
        ],
        'post' => [
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'randomEventsList' => [
            'method' => 'GET',
            'path' => '/eventsListRandom',
            'controller' => RandomEventsListAction::class,
        ],
    ],
    itemOperations: [
        'get' => [
            'path' => '/event/{id}',
            'normalization_context' => [
            'groups' => [
                'event:read',
                'event:read:item'
            ],
            'openapi_definition_name' => 'read item'
        ]
    ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['event:update'],
                'openapi_definition_name' => 'update item'
            ]
        ],
        'delete',
        'patch',
        'updatePicture' => [
            'method' => 'POST',
            'path' => '/event/{id}/updatePicture',
            'openapi_context' => [
                'summary'     => 'Use this endpoint to update only the picture of the event. Use the PUT endpoint for all other updating'
            ],
            'controller' => UpdatePictureAction::class,
            'denormalization_context' => ['groups' => ['event:updatePicture']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ]

            ],
        'updateImageStock' => [
            'method' => 'POST',
            'path' => '/event/{id}/updateImageStock',
            'openapi_context' => [
                'summary' => 'Use this endpoint to update only the picture of "banque d\'images"'
            ],
            'controller' => UpdateImageStockAction::class,
            'denormalization_context' => ['groups' => ['event:updateImageStock']],
            'input_formats' => [
                'json' => ['application/json'],
            ]
        ],
                'downloadParticipantList' => [
                    'method' => 'GET',
                    'path' => '/downloadParticipantList/{id}',
                    'controller' => DownloadParticipantListAction::class,
                ],

                'participantList' => [
                    'method' => 'GET',
                    'path' => '/participantList/{id}',
                    'controller' => ParticipantListAction::class,
                ],
    ],
    attributes: ['pagination_enabled' => false],
    denormalizationContext: ['groups' => ['event:create']],
    normalizationContext: [
        'groups' => [
            'event:read',
            'openapi_definition_name' => 'read collection'
        ]
    ]
)]
/**
 * @Vich\Uploadable()
 */
class Event implements ImageStockCompatibleInterface, UploadPictureCompatibleInterface
{

    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['event:read'])]
    private ?int $id = null;


    /**
     * @var string   
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\TitleRequirements]
    #[Groups(['event:read', 'event:create'])]
    private string $title;


    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    #[Assert\DescriptionRequirements]
    #[Groups(['event:read', 'event:create'])]
    private string $description;


    /**
     * @var boolean 
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['event:read', 'event:create'])]
    private bool $forAllUniversities;

    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['event:read', 'event:create'])]
    private ?string $university;


    /**
     * @var boolean   
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['event:read', 'event:create'])]
    private bool $public;

    /**
     * @var DateTimeImmutable 
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    #[Groups(['event:read'])]
    private DateTimeImmutable $createdAt;


    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['event:read', 'event:create'])]
    private ?string $company;



    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['event:read', 'event:create'])]
    private ?string $maxNumberOfParticipants;


    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'date')]
    #[Groups(['event:read', 'event:create'])]
    private DateTime $startingAt;
     
       /**
     * @var DateTime
     */
    #[ORM\Column(type: 'date')]
    #[Groups(['event:read', 'event:create'])]
    private DateTime $endingAt;


    /**
     * @var User|null  
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['event:read'])]
    private User|null $createdBy;


    
    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['event:read', 'event:create'])]
    private ?string $location;

     
    /**
     * @var EventCategory
     */
    #[ORM\ManyToOne(targetEntity: EventCategory::class, cascade: ['PERSIST'], inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    private EventCategory $category;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $imagePath = null;

    
    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="imagePath")
     */
    #[Groups(['event:updatePicture', 'event:create'])]
    #[Assert\ImageFileRequirements]
    public ?File $imageFile = null;

    #[ApiProperty(iri: 'http://schema.org/imageUrl')]
    #[Groups(['event:read'])]
    public ?string $imageUrl = null;

    #[ApiProperty(iri: 'http://schema.org/imageStockId')]
    #[Groups(['event:create'])]
    public ?string $imageStockId = null;


    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt;


    #[ORM\Column(type: 'time', nullable: true)]
    #[Groups(['event:create'])]
    private ?\DateTimeInterface $startingHour;


    #[ORM\Column(type: 'time', nullable: true)]
    #[Groups(['event:create'])]
    private ?\DateTimeInterface $endingHour;

    /**
     * @var boolean 
     */
    #[ORM\Column(type: 'boolean')]
    private bool $register = false;


    /**
     * @var DateTime|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['event:create'])]
    private ?DateTime $registerBegin;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['event:create'])]
    private ?DateTime $registerEnd;


    /**
     * @var boolean
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups(['event:create'])]
    private ?bool $accessibleForDisabled;


    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['event:read', 'event:create'])]
    private ?string $link;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['event:create'])]
    private array $questions = [];

    /**
     * @var Group|null
     */
    #[Groups(['event:read'])]
    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'events')]
    private ?Group $associatedGroup;

    /**
     * @var EventStatus
     */
    #[ORM\ManyToOne(targetEntity: EventStatus::class, cascade: ['persist'], inversedBy: 'news')]
    #[Groups(['event:read', 'event:read:item'])]
    #[ORM\JoinColumn(nullable: false)]
    private EventStatus $eventStatus;

    
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getForAllUniversities(): ?bool
    {
        return $this->forAllUniversities;
    }

    public function setForAllUniversities(bool $forAllUniversities): self
    {
        $this->forAllUniversities = $forAllUniversities;

        return $this;
    }

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(?string $university): self
    {
        $this->university = $university;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }


    /**
     * @return DateTimeImmutable 
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     * @return $this     
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getMaxNumberOfParticipants(): ?string
    {
        return $this->maxNumberOfParticipants;
    }


    public function setMaxNumberOfParticipants(?string $maxNumberOfParticipants): self
    {
        $this->maxNumberOfParticipants = $maxNumberOfParticipants;

        return $this;
    }

    public function getStartingAt(): DateTime
    {
        return $this->startingAt;
    }

    public function setStartingAt(DateTime $startingAt): self
    {
        $this->startingAt = $startingAt;

        return $this;
    }

    public function getEndingAt(): DateTime
    {
        return $this->endingAt;
    }

    public function setEndingAt(DateTime $endingAt): self
    {
        $this->endingAt = $endingAt;

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

   
    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCategory(): EventCategory
    {
        return $this->category;
    }

    public function setCategory(EventCategory $category): self
    {
        $this->category = $category;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStartingHour(): ?\DateTimeInterface
    {
        return $this->startingHour;
    }

    public function setStartingHour(?DateTimeInterface $startingHour): self
    {
        $this->startingHour = $startingHour;

        return $this;
    }

    public function getEndingHour(): ?\DateTimeInterface
    {
        return $this->endingHour;
    }

    public function setEndingHour(?DatetimeInterface $endingHour): self
    {
        $this->endingHour = $endingHour;

        return $this;
    }

    public function getRegister(): bool
    { 
        return $this->register;
    }

    public function setRegister(bool $register): self
    {
        $this->register = $register;

        return $this;
    }

    public function getRegisterBegin(): ?\DateTimeInterface
    {
        return $this->registerBegin;
    }

    public function setRegisterBegin(?\DateTime $registerBegin): self
    {
        $this->registerBegin = $registerBegin;

        return $this;
    }

    public function getRegisterEnd(): ?\DateTimeInterface
    {
        return $this->registerEnd;
    }

    public function setRegisterEnd(?\DateTime $registerEnd): self
    {
        $this->registerEnd = $registerEnd;

        return $this;
    }


    public function isAccessibleForDisabled(): ?bool
    {
        return $this->accessibleForDisabled;
    }


    public function setAccessibleForDisabled(?bool $accessibleForDisabled): self
    {
        $this->accessibleForDisabled = $accessibleForDisabled;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

   /**
     * @return string[]
     */
    public function getQuestions(): array
    {
        $questions = $this->questions;

        return array_unique($questions);
    }
  
    /**
     * @param string[] $questions
     * @return $this
     */
    public function setQuestions(array $questions): self
    {
        $this->questions = $questions;

        return $this;
    }

    public function getAssociatedGroup(): ?Group
    {
        return $this->associatedGroup;
    }

    public function setAssociatedGroup(?Group $associatedGroup): self
    {
        $this->associatedGroup = $associatedGroup;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageStockId(): ?string
    {
        return $this->imageStockId;
    }

    /**
     * @param string|null $imageStockId
     */
    public function setImageStockId(?string $imageStockId): void
    {
        $this->imageStockId = $imageStockId;
    }

    public function getEventStatus(): EventStatus
    {
        return $this->eventStatus;
    }

    public function setEventStatus(EventStatus $eventStatus): self
    {
        $this->eventStatus = $eventStatus;

        return $this;
    }

}
