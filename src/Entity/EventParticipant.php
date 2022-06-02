<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventParticipantRepository;
use App\Controller\EventParticipant\EventRegistrationAction;
   

#[ORM\Entity(repositoryClass: EventParticipantRepository::class)]
#[ApiResource(

    collectionOperations        : [  
        
        'event_registration' => [
            'method' => 'POST',
            'path' => '/eventRegistration',
            'controller' => EventRegistrationAction::class,
        ],
    ]
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

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'eventParticipants')]
    #[ORM\JoinColumn(nullable: false)]
    private $event;


  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self  
    {  
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
