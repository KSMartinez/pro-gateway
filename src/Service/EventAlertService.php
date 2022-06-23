<?php

namespace App\Service;

use App\Entity\NotificationSource;
use DateTime;
use Exception;
use App\Repository\EventRepository;  

use App\Repository\EventParticipantRepository;

   
class EventAlertService
{
  

    
    public function __construct(private EmailNotificationService $emailNotificationService, private EventRepository $eventRepository, private EventParticipantRepository $eventParticipantRepository)
    {
         


    }
  
    /**  
     * @return void
     * @throws Exception
     */
    public function alertUsersOneDayBeforeTheEvent()
    {


        $eventParticipants = $this->eventParticipantRepository->findAll();   

        $events_id =  array();         
        $events = array(); 

        foreach($eventParticipants as $ep){

            if( !in_array( $ep->getEvent()->getId(), $events_id))
            {    

                array_push($events_id, $ep->getEvent()->getId()); 

            }


        }
              
        
        $events = $this->eventRepository->userEvents($events_id); 
        $today = date("Y-m-d H:i:s"); 
        $today_day = substr($today, 8, -9);   
        $result = array(); 
        $allParticipants = array();
        
        
        foreach($events as $ev){ 
            
            $participants = array(); 
    
       
            $startingDate = DateTime::createFromInterface( $ev->getStartingAt())->format('Y-m-d H:i:s'); 
            $startingDate_day =  substr($startingDate, 8, -9); 


            if( (intval($startingDate_day) - intval($today_day)) == 1 ){  
                   

                foreach($eventParticipants as $ep){     
                

                if( $ep->getEvent()->getId() == $ev->getId() ){ 

                    
                    if( !in_array( $ep->getUser(), $participants))
                    {    
        
                        array_push($participants, $ep->getUser()); 

                    }     


                } 
     
                
            }

            array_push($result, $participants);   

        }
        
     }


    foreach($result as $participantArray){

            foreach($participantArray as $participant){
                 
                if( !in_array( $participant, $allParticipants))
                {    
                    array_push( $allParticipants, $participant); 

                }

            }
    }   
   
   // emailNotificationOneDayBeforeTheEndOfTheEvent
   // $this->emailNotificationService->createEmailNotificationOneDayBeforeTheEvent($allParticipants);  
  
        $this->emailNotificationService->emailNotificationOneDayBeforeTheEndOfTheEvent($allParticipants, NotificationSource::EVENT_NOTIFICATION_ONE_DAY_BEFORE);  
   
    }   



   

       

    /**    
     * @return void
     * @param boolean $forAdmin 
     */
    public function notificationOneDayBeforeTheEndOfEvents($forAdmin = true)
    {

        $eventParticipants = $this->eventParticipantRepository->findAll();      

        $events_id =  array();         
        $events = array();   

        foreach($eventParticipants as $ep){

            if( !in_array( $ep->getEvent()->getId(), $events_id))
            {    

                array_push($events_id, $ep->getEvent()->getId()); 

            }


        }
              
        
        $events = $this->eventRepository->userEvents($events_id); 
        $today = date("Y-m-d H:i:s"); 
        $today_day = substr($today, 8, -9);  
        $participants = array(); 
        
        foreach($events as $ev){ 
              
    
            $endingDate = DateTime::createFromInterface( $ev->getEndingAt())->format('Y-m-d H:i:s'); 
            $endingDate_day =  substr($endingDate, 8, -9); 
    

            if( (intval($endingDate_day) - intval($today_day)) == 1 ){  
                        
   
            # We have to select the one who created the event and check if he is an admin 

                $eventCreator = $ev->getCreatedBy();

                if( $forAdmin){   
                    
                    if(  $eventCreator !== null){
                        
                        if(  in_array( "ROLE_ADMIN",   $eventCreator->getRoles()) )
                         {
                    
                            array_push($participants,  $eventCreator); 
                                      
                        }    

                    } 

                }
                else{

                    if(  $eventCreator !== null){

                        if(  !in_array( "ROLE_ADMIN",   $eventCreator->getRoles()) )
                        {
                
                        array_push($participants,  $eventCreator); 
                                    
                        }     

                    }
                       
                }
                       
             
        }
        
     }  
   
    $this->emailNotificationService->createEmailNotificationOneDayBeforeTheEvent($participants, NotificationSource::EVENT_NOTIFICATION_ONE_DAY_BEFORE_THE_END);  
     
    }   

 }   

      

