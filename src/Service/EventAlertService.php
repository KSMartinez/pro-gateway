<?php

namespace App\Service;

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
            # We have to plan Holidays to sign our music contract

            # Check if today is "one day before the event" then send a notification to the eventParticipant 
           
            // $date = new DateTime($ev->getStartingAt());  
            // $date = $ev->getStartingAt()->format('Y-m-d H:i:s');    
            // $startingDate = $date;  
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
    
    // var_dump('all participants'); 
    // dd( $allParticipants ); 

    foreach($result as $participantArray){

            foreach($participantArray as $participant){
                 
                if( !in_array( $participant, $allParticipants))
                {    
                    array_push( $allParticipants, $participant); 

                }

            }
    }   

    // var_dump("allParticipant"); 

    // dd( $allParticipants );  

    $this->emailNotificationService->createEmailNotificationOneDayBeforeTheEvent($allParticipants);  
   

   
            
    }   

}