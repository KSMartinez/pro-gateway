<?php

namespace App\Entity;

use App\Entity\EventParticipant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventQuestionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use  App\Controller\EventQuestion\EventQuestionAnswerListAction; 


#[ORM\Entity(repositoryClass: EventQuestionRepository::class)]
#[ApiResource(

    itemOperations: [
              
        'get', 'put', 
        'eventAnswers' => [
            'method' => 'GET',      
            'path' => '/eventAnswers/{id}',
            'controller' => EventQuestionAnswerListAction::class,
        ],   

    ]
)]  
class EventQuestion
{  

     /**  
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

   
     /**
     * @var EventParticipant 
     */
    #[ORM\ManyToOne(targetEntity: EventParticipant::class, inversedBy: 'eventQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private EventParticipant $eventParticipant;


    /**
     * @var string  
     */
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $question;

  
    /**   
     * @var Collection<int,EventAnswer>
     */
    #[ORM\OneToMany(mappedBy: 'eventQuestion', targetEntity: EventAnswer::class)]
    private $eventAnswers;

    public function __construct()
    {
        $this->eventAnswers = new ArrayCollection();
    }


    public function getId(): ?int
    {  
        return $this->id;  
    }

    public function getEventParticipant(): EventParticipant
    {
        return $this->eventParticipant;
    }

    public function setEventParticipant(EventParticipant $eventParticipant): self
    {
        $this->eventParticipant = $eventParticipant;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, EventAnswer>
     */
    public function getEventAnswers(): Collection
    {
        return $this->eventAnswers;
    }

    public function addEventAnswer(EventAnswer $eventAnswer): self
    {
        if (!$this->eventAnswers->contains($eventAnswer)) {
            $this->eventAnswers[] = $eventAnswer;
            $eventAnswer->setEventQuestion($this);
        }

        return $this;
    }


    # If we remove an answer from the answers of a question, 
    public function removeEventAnswer(EventAnswer $eventAnswer): self
    {
        if ($this->eventAnswers->removeElement($eventAnswer)) {
            // set the owning side to null (unless already changed)
            if ($eventAnswer->getEventQuestion() === $this) {
                $eventAnswer->setEventQuestion(null);
            }
        }

        return $this;
    }
}
