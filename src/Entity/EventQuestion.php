<?php

namespace App\Entity;

use App\Entity\EventParticipant;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventQuestionRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: EventQuestionRepository::class)]
#[ApiResource()]   
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
    private $eventParticipant;


    /**
     * @var string  
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $question;


    public function getId(): ?int
    {
        return $this->id;  
    }

    public function getEventParticipant(): ?EventParticipant
    {
        return $this->eventParticipant;
    }

    public function setEventParticipant(?EventParticipant $eventParticipant): self
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
}
