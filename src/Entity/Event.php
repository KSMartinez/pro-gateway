<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Event\RandomEventsListAction;
use Doctrine\Common\Collections\ArrayCollection;


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
     * @var Collection<int, User>  
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $participants;

    
     /**
     * @var boolean 
     */
    #[ORM\Column(type: 'boolean')]
    private bool $forAllUniversities;

     /**
     * @var string  
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
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $createdAt = null;

    
     /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $company;


    
    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

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
    public function getCreatedAt(): ?\DateTimeImmutable
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
}
