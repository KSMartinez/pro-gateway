<?php

namespace App\Entity;

use App\Entity\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventParticipantRepository;
use App\Controller\EventParticipant\EventRegistrationAction;
   

#[ORM\Entity(repositoryClass: EventParticipantRepository::class)]
#[ApiResource(

    collectionOperations  : [  
        
        'event_registration' => [
            'method' => 'POST',
            'path' => '/eventRegistration',
            'controller' => EventRegistrationAction::class,
        ],

       
    ],

    itemOperations      : [
        'put', 'get', 'delete'
    ]  
    
    // itemOperations  : [  
    //     'event_unsubscription' => [
    //         'method' => 'delete',  
    //         'path' => '/EventUnsubscription/{id}',
    //         'controller' => EventUnsubscriptionAction::class,
    //     ],
    // ]      
)]  
class EventParticipant   
{
    
    
     /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;    
    
     /**
     * @var User 
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;


      /**  
     * @var Event 
     */
    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'eventParticipants')]
    #[ORM\JoinColumn(nullable: false)]
    private Event $event;

    #[ORM\Column(type: 'boolean')]
    private $registrationInPending;

    #[ORM\OneToMany(mappedBy: 'eventParticipant', targetEntity: EventQuestion::class, orphanRemoval: true)]
    private $eventQuestions;

    public function __construct()
    {
        $this->eventQuestions = new ArrayCollection();
    }

  
  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self  
    {  
        $this->user = $user;

        return $this;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getRegistrationInPending(): ?bool
    {
        return $this->registrationInPending;
    }

    public function setRegistrationInPending(bool $registrationInPending): self
    {
        $this->registrationInPending = $registrationInPending;

        return $this;
    }

    /**
     * @return Collection<int, EventQuestion>
     */
    public function getEventQuestions(): Collection
    {
        return $this->eventQuestions;
    }

    public function addEventQuestion(EventQuestion $eventQuestion): self
    {
        if (!$this->eventQuestions->contains($eventQuestion)) {
            $this->eventQuestions[] = $eventQuestion;
            $eventQuestion->setEventParticipant($this);
        }

        return $this;
    }

    public function removeEventQuestion(EventQuestion $eventQuestion): self
    {
        if ($this->eventQuestions->removeElement($eventQuestion)) {
            // set the owning side to null (unless already changed)
            if ($eventQuestion->getEventParticipant() === $this) {
                $eventQuestion->setEventParticipant(null);
            }
        }

        return $this;
    }
}
