<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Event\ParticipantListAction;
use App\Controller\Event\RandomEventsListAction;
use App\Controller\Event\DownloadParticipantListAction;


  
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    collectionOperations  : [
        'get',   
        'post',
        'randomEventsList' => [
            'method' => 'GET',
            'path' => '/randomEventsList',
            'controller' => RandomEventsListAction::class,
        ],
            
    ],  
      
    itemOperations  : [  
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
     * @var DateTimeImmutable 
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private $startingAt;
  
       /**
     * @var DateTimeImmutable 
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private $endingAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $createdBy;
   
      

    
    
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
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

     /**
     * @param DateTimeImmutable $createdAt
     * @return $this     
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
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

    public function getStartingAt(): ?\DateTimeImmutable
    {
        return $this->startingAt;
    }

    public function setStartingAt(\DateTimeImmutable $startingAt): self
    {
        $this->startingAt = $startingAt;

        return $this;
    }

    public function getEndingAt(): ?\DateTimeImmutable
    {
        return $this->endingAt;
    }

    public function setEndingAt(\DateTimeImmutable $endingAt): self
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

   
  
}
