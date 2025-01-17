<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventAnswerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EventQuestion\EventAnswerQuestionAction;

#[ORM\Entity(repositoryClass: EventAnswerRepository::class)]
#[ApiResource(

    collectionOperations  : [
        'get',     
        'post',
        'randomEventsList' => [
            'method' => 'POST',
            'path' => '/answerQuestion',
            'controller' => EventAnswerQuestionAction::class,
            'validate' => false  
        ],
            
    ],  

)]   
class EventAnswer
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
    private string $response;


    /**   
     * @var EventQuestion   
     */
    #[ORM\ManyToOne(targetEntity: EventQuestion::class, inversedBy: 'eventAnswers')]
    private ?EventQuestion $eventQuestion;  


    /**    
     * @var User  
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $eventCreator;
        
       

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getEventQuestion(): ?EventQuestion
    {
        return $this->eventQuestion;
    }

    public function setEventQuestion(?EventQuestion $eventQuestion): self
    {
        $this->eventQuestion = $eventQuestion;  

        return $this;
    } 

    public function getEventCreator():?User
    {
        return $this->eventCreator;
    }

    public function setEventCreator(User $eventCreator): self
    {
        $this->eventCreator = $eventCreator;

        return $this;
    }
}
   