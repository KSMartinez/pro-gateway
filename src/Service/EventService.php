<?php


namespace App\Service;

use Exception;
use App\Entity\Event;
use App\Entity\Education;
use App\Repository\EventRepository;
use App\Repository\EducationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File; 

class EventService
{


     /**     
     * @var EventRepository 
     */
    private EventRepository $eventRepository;   

    /**   
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
    
        $this->eventRepository = $eventRepository; 
    }

   

        
    /**
     * @param
     * @return Event[]
     */ 
    public function randomEventsList() 
    {  
    
       $events = array(); 
    
       $allEvents = $this->eventRepository->allEvents();
      // $universities = $this->eventRepository->onlyUniversities();
       $rangeMax = count($allEvents) - 1;  
             
              
       for($i=0; $i< count($allEvents); $i++){
             
        $forRandomEvent = mt_rand(0,  $rangeMax);       

            if( $i == $forRandomEvent){
   
                    if( !in_array( $allEvents[$i], $events ) ){
     
                        array_push($events, $allEvents[$i]); 
                        
                    }

                    if( count($events) == 6)
                    break; 
            }
             

       }   


        
        return $events;  
    }   
  
    

   
}