<?php

namespace App\Service;

use DateTime;
use Exception;
use App\Entity\SavedOfferSearch;
use App\Repository\EventRepository;
use App\Repository\OfferRepository;
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

            array_push($events_id, $ep->getId()); 

        }
        
        $events = $this->eventRepository->userEvents($events_id); 
    
        $today = date("Y-m-d H:i:s"); 
        $today_day = substr($today, 8, -9);   
        
        foreach($eventParticipants as $ep){


            foreach($events as $ev){

            # Check if today is "one day before the event" then send a notification to the eventParticipant 

            //$startingDate = $ev->getStartingAt(); 
       
            $startingDate = DateTime::createFromInterface($ev->getStartAt())->format('Y-m-d H:i:s'); 
            $startingDate_day =  substr($startingDate, 8, -9); 
    
   

            if( (intval($startingDate_day) - intval($today_day)) == 1 ){  

                # Send a notification to the eventParticipant 
                $justToPush = new SavedOfferSearch();    
                $this->emailNotificationService->createEmailNotification($justToPush, 10); 
            //  $this->emailNotificationService->createEmailNotificationOneDayBeforeTheEvent(); 


              //dd('y')
          

            }

            }

        }

       

    }

}