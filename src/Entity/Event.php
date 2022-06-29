<?php

namespace App\Entity;


use App\Entity\User;
use DateTime;
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
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
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
                       
             
    ]
)]  
class Event
{


    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    /**
     * @var string   
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;



    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    private string $description;


    /**
     * @var boolean 
     */
    #[ORM\Column(type: 'boolean')]
    private bool $forAllUniversities;

    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $university;


    /**
     * @var boolean   
     */
    #[ORM\Column(type: 'boolean')]
    private bool $isPublic;

    /**
     * @var DateTimeImmutable 
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;


    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $company;


    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $maxNumberOfParticipants;


       /**
     * @var DateTime
     */
    #[ORM\Column(type: 'date')]
    private DateTime $startingAt;
     
       /**
     * @var DateTime
     */
    #[ORM\Column(type: 'date')]
    private DateTime $endingAt;


       /**
     * @var User|null  
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private User|null $createdBy;


    
    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $location;

     
    /**
     * @var string 
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $category;

     
    /**
     * @var string|null  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image;

    
    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="image")
     */
    #[Groups(['event:updatePicture'])]
    public ?File $imageFile = null;


    
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
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

}
