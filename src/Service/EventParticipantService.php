<?php

namespace App\Service;


use Exception;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventParticipant;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventParticipantRepository;
use Symfony\Component\Validator\Constraints\Collection;

class EventParticipantService
{   

        
     /**     
     * @var EventRepository 
     */
    private EventRepository $eventRepository;   
    

     /**     
     * @var EntityManagerInterface 
     */
    private EntityManagerInterface $entityManagerInterface;   
    
  
    /**     
     * @var EventParticipantRepository 
     */
    private EventParticipantRepository $eventParticipantRepository;   
    

   
           
     /**
      * @param EventRepository $eventRepository   
      * @param EntityManagerInterface $entityManagerInterface      
     * @param EventParticipantRepository $eventParticipantRepository  
     */
    public function __construct(EventRepository $eventRepository, EntityManagerInterface $entityManagerInterface, 
    EventParticipantRepository $eventParticipantRepository)
    {
      //  $this->userRepository = $userRepository; 
        $this->eventRepository = $eventRepository; 
        $this->entityManagerInterface = $entityManagerInterface;   
        $this->eventParticipantRepository = $eventParticipantRepository;   
    }

   
    /**
     * @param EventParticipant $data   
   *  @return EventParticipant 
     * @throws Exception
     */
    public function eventRegistration(EventParticipant $data)   
    {
  

        # Before all we check if the user is connected 
        # To master later :  Check if the user is connected, we gonna do that after Akhil works on Authentification
 
        if (!$this->eventRepository->find($data->getEvent()->getId())) {
            throw new Exception('The Event should have an id for creating an Event_Participant');
  
        }  

        $event = $this->eventRepository->find($data->getEvent()->getId());  

        if( $event->getMaxNumberOfParticipants() == NULL){
                
            if( !$this->eventParticipantRepository->userIsAlreadyRegistered($data->getUser()->getId(), $data->getEvent()->getId()) ) 
            {

                $this->entityManagerInterface->persist($data);
                $this->entityManagerInterface->flush();
                return $data;  
            }
            else{

                throw new Exception('The user is already registered to the event');   

            }
                
            
        }
        else{  
   

            if(  count($this->eventParticipantRepository->getParticipants($event->getId())) < $event->getMaxNumberOfParticipants() ) 
            {
            
             if( !$this->eventParticipantRepository->userIsAlreadyRegistered($data->getUser()->getId(), $data->getEvent()->getId()) ) 
                {
    
                $this->entityManagerInterface->persist($data);
                $this->entityManagerInterface->flush();
                return $data;    
                
                }
                else{

                    throw new Exception('The user is already registered to the event'); 
  
                }

            }   
            else{
    
                throw new Exception('The user can not register to the event cause the Event is full');
    
            } 

        }

      
        
  


    }

  
}