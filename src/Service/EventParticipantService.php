<?php

namespace App\Service;


use Exception;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventParticipant;
use App\Repository\UserRepository;
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
     * @var UserRepository 
     */
    private UserRepository $userRepository;   
    

   
           
     /**
      * @param EventRepository $eventRepository   
      * @param EntityManagerInterface $entityManagerInterface      
     * @param EventParticipantRepository $eventParticipantRepository  
     */
    public function __construct(UserRepository $userRepository, EventRepository $eventRepository, EntityManagerInterface $entityManagerInterface, 
    EventParticipantRepository $eventParticipantRepository)
    {
        $this->userRepository = $userRepository; 
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
                if( $data->getRegistrationInPending() ){
                    // Waiting line registration
                    # Here, we have to
                    # Tell the admin that there is a new subscriber 
                    # Tell the user that we still got place for the event
                    
                      // MAIL PART TO MASTER LATER WE GONNA COME BACK ON IT 
     
                }
              

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
    
                # SEND A MAIL TO THE ADMIN TO NOTIFY THAT THE EVENT IS FULL 

                    

                # WAITING LINE  
                # The waiting line is only when the event is full 
                if( $data->getRegistrationInPending() ){

                    // Waiting line registration 

                     # Here, we have to
                     # Tell the admin that there is a new subscriber 

                    // MAIL PART TO MASTER LATER WE'LL COME BACK ON IT 
                     
                     $this->entityManagerInterface->persist($data);
                     $this->entityManagerInterface->flush();
                     return $data;    

                     
                     # Tell the user that we still got place for the event 

                     // MAIL PART TO MASTER LATER WE'LL COME BACK ON IT 
   

                }
                else{

                    // The case when the user don't register to the waiting line   
                    throw new Exception('The user can not register to the event cause the Event is full');
                }
   

    
            } 

        }
  
    }   

 
     /**
     * @param User $data   
    * @return Event[]  
     * @throws Exception   
     */
    public function userEvents(User $data)
    {
  
        if (!$this->userRepository->find($data)) {
            throw new Exception('The User should have an id for getting his events');
    
        }     

        # Get all the events participants of the user 
        # Get all the events by taking the id of the previous result 

        $eventParticipants =  $this->eventParticipantRepository->eventParticipants($data->getId());

        $events = array();


        foreach( $eventParticipants as $ep)
        {
            array_push($events, $ep->getEvent()->getId());  
        }  
   

        return $this->eventRepository->userEvents($events);
    

    }
     
  
}