<?php


namespace App\Service;

use DateTime;  
use App\Entity\Event;
use App\Repository\EventRepository;

class EventService
{
   
  
     /**
     * Number of random events to display 
     */
    const random_Events_Value = 6; 

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
     * @param Event[] $events
     * @param array $result  
     * @return Event[]   
     */ 
    public function reOrderTable($events, $result){   
           
       $array_dateTimes =  array(); 
  
       for($i = 0; $i< count($events); $i++){
     
           $val1 = DateTime::createFromInterface($events[$i]->getCreatedAt());  
           $array_dateTimes[$i] = $val1->format('Y-m-d H:i:s');
              
       }

       usort($array_dateTimes, function($time1, $time2){
                 
            if (strtotime($time1) < strtotime($time2))
               return 1;
           
           else if (strtotime($time1) > strtotime($time2)) 
               return -1;
           else
               return 0;
       });

    
       usort($array_dateTimes, function($time1, $time2){
                 
            $hours1 = substr($time1, 11, -6);
            $minutes1 = substr($time1[0], 14, -3); 

            $hours2 = substr($time2, 11, -6);
            $minutes2 = substr($time2[0], 14, -3); 

            if( intval($hours1) <  intval($hours2) )
            {
                return 1; 
            }
            else if ( intval($hours1) >  intval($hours2) ){
                return -1;   
            }
            else{
                if( intval($minutes1) <  intval($minutes2) )
                {
                    return 1; 
                }
                else if( intval($minutes1) >  intval($minutes2) ){
                     return -1;    
                }
                else{
                    return 0; 
                }

            }
           
      });
         
   foreach( $array_dateTimes as $date){

        foreach( $events as $event){
 
            $dateTime = DateTime::createFromInterface($event->getCreatedAt())->format('Y-m-d H:i:s'); 

                    if( strcmp($dateTime, $date) == 0 ){  
        
                        if (!in_array( $event,  $result)){

                            array_push( $result, $event); 
                        }
            
                    }   
                        
                }    
        }   
      
        return $result; 
    }


    
    /**
    * @return Event[]   
     */ 
    public function randomEventsList() 
    {     
    
       $events = array(); 
       $result = array();    
    
       $allEvents = $this->eventRepository->allEvents();

       $rangeMax = count($allEvents) - 1;  
 
       if( count($allEvents) > self::random_Events_Value ){
          
        # Let's generate 6 Random events 
        while( count($events) !=  self::random_Events_Value ){

            for($i=0; $i< count($allEvents); $i++){
                
                $forRandomEvent = mt_rand(0,  $rangeMax);       
        
                    if( $i == $forRandomEvent){
        
                            if( !in_array( $allEvents[$i], $events ) ){
            
                                array_push($events, $allEvents[$i]); 
                                
                            } 
                    }            
            }   
                
        }

        return $this->reOrderTable($events, $result); 

       }
   
       return $this->reOrderTable($allEvents, $result); 
   

    }      
  
   
}