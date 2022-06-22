<?php

namespace App\Service;
use Exception;
use App\Entity\EventAnswer;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventQuestionRepository;

class EventQuestionService
{
   

    public function __construct(private EntityManagerInterface $entityManager, private EventQuestionRepository $eventQuestionRepository)    
    {
    }
    


      /**
     * @param EventAnswer $data    
     * @return EventAnswer   
     * @throws Exception
     */ 
    public function answerQuestion(EventAnswer $data) 
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

}