<?php

namespace App\Entity;


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
use Symfony\Component\Validator\Constraints as Assert;
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

        'get',
        'post',  
        'randomEventsList' => [
            'method' => 'GET',
            'path' => '/eventsListRandom',
            'controller' => RandomEventsListAction::class,
            
        ],
  

    ],
    itemOperations: ['get','put','delete', 'patch',
           
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
    normalizationContext: [
        'groups' => [
            'event:read'
        ]
    ]
)]
/**
 * @Vich\Uploadable()
 */
class Event
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
    #[Groups(['event:read'])]
    private string $title;



    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    #[Groups(['event:read'])]
    private string $description;


    /**
     * @var boolean 
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['event:read'])]
    private bool $forAllUniversities;

    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['event:read'])]
    private ?string $university;


    /**
     * @var boolean   
     */
    #[ORM\Column(type: 'boolean')]
    #[Groups(['event:read'])]
    private bool $isPublic;

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
    #[Groups(['event:read'])]
    private ?string $company;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['event:read'])]
    private ?int $maxNumberOfParticipants;


       /**
     * @var DateTime
     */
    #[ORM\Column(type: 'date')]
    #[Groups(['event:read'])]
    private DateTime $startingAt;
     
       /**
     * @var DateTime
     */
    #[ORM\Column(type: 'date')]
    #[Groups(['event:read'])]
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
    #[Groups(['event:read'])]
    private ?string $location;

     
    /**
     * @var EventCategory
     */
    #[ORM\ManyToOne(targetEntity: EventCategory::class, cascade: ['PERSIST'], inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:read'])]
    private EventCategory $category;

     
    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['event:read'])]
    private ?string $image;

    
    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="image")
     */
    #[Groups(['event:updatePicture'])]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['image/png', 'image/jpeg', 'image/webp'],
    )]
    #[Assert\Image(
        allowLandscape: true,
        allowPortrait: false,
    )]
    public ?File $imageFile = null;



    /**
     * @var DatetimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DatetimeImmutable $updatedAt;


    /**
     * @var string  
     */  
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $startingHour;  

    
      /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $endingHour;

      /**
     * @var boolean 
     */
    #[ORM\Column(type: 'boolean')]
    private bool $register = false;


       /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $registerBegin;

       /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $registerEnd;


    /**
     * @var boolean
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $handicapes;  


    /**
     * @var string  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $link;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private array $questions = [];

    
    public function __construct()
    {
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

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

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

    public function getMaxNumberOfParticipants(): ?int
    {
        return $this->maxNumberOfParticipants;
    }

    public function setMaxNumberOfParticipants(?int $maxNumberOfParticipants): self
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStartingHour(): ?string
    {
        return $this->startingHour;
    }

    public function setStartingHour(?string $startingHour): self
    {
        $this->startingHour = $startingHour;

        return $this;
    }

    public function getEndingHour(): ?string
    {
        return $this->endingHour;
    }

    public function setEndingHour(?string $endingHour): self
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

    public function getHandicapes(): ?bool
    {
        return $this->handicapes;
    }

    public function setHandicapes(?bool $handicapes): self
    {
        $this->handicapes = $handicapes;

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



}
