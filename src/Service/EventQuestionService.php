<?php

namespace App\Service;
use Exception;
use App\Entity\EventQuestion;  
use App\Entity\EventAnswer;
use App\Repository\EventAnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventQuestionRepository;


class EventQuestionService
{
   

    public function __construct(private EntityManagerInterface $entityManager, private EventQuestionRepository $eventQuestionRepository,
    private EventAnswerRepository $eventAnswerRepository)    
    {
    }
    


      /**
     * @param EventAnswer $data    
     * @return EventAnswer   
     * @throws Exception
     */ 
    public function saveAnswerQuestion(EventAnswer $data) 
    {   


        if($data->getEventQuestion() == null ){

            throw new Exception('The  answer must come form an Event_Question');
  
        }
        else{


            if (!$this->eventQuestionRepository->find($data->getEventQuestion()->getId())) {
                throw new Exception('The Event_Question should have an id for creating the answer');
    
            }  

            if ($data->getEventQuestion()->getEventParticipant()->getRegistrationInPending() == true ){

                throw new Exception('The User has to be registered to the event for saving the answer');

            }                             
    

             # IF THE USER IS REGISTERED 

             $this->entityManager->persist($data);
             $this->entityManager->flush();  
             return $data;      

        }   
   
    }


     /**
     * @param EventQuestion $data
     * @return EventAnswer[]  
     */ 
    public function getAnswers(EventQuestion $data)
    {

        if($data->getId() == null ){

            throw new Exception('The question must have an id to get the answers');
  
        }


        return $this->eventAnswerRepository->getAnswers($data); 


    }

}