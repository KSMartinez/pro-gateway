<?php


namespace App\Service;

use Exception;
use DateTime;  
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Event;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventParticipantRepository;
use Symfony\Component\HttpFoundation\Response;


class EventService
{
   
  
     /**
     * Number of random events to display 
     */
    const random_Events_Value = 6; 

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

     /**     
     * @var EventRepository 
     */
    private EventRepository $eventRepository;   


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
     * @param EventParticipantRepository $eventParticipantRepository 
     */
    public function __construct(EventRepository $eventRepository, EventParticipantRepository $eventParticipantRepository,
     UserRepository $userRepository,  EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager; 
        $this->eventRepository = $eventRepository;   
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository; 
        $this->eventParticipantRepository = $eventParticipantRepository;  

    }    



    /**
     * @param Event[] $events
     * @param Event[] $result  
     * @return Event[]      
     */ 
    public function reOrderTable(array $events, array $result){   
           
       $array_dateTimes =  array(); 
  
       for($i = 0; $i< count($events); $i++){
     
           $val1 = DateTime::createFromInterface($events[$i]->getStartAt());  
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
 
            $dateTime = DateTime::createFromInterface($event->getStartAt())->format('Y-m-d H:i:s'); 

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
    
    /**   
     * @param Event $event
     * @return Event  
     * @throws Exception
     */
    public function updatePicture(Event $event)
    {

        if (!$event->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->eventRepository->find($event->getId())) {
            throw new Exception('The user should have an id for updating');
   
        }  
        
        $this->entityManager->persist($event);
        $this->entityManager->flush();
        return $event;       


    } 


    
    /**   
     * @param Event $data
     * @return User[]        
     */ 
    public function participantList(Event $data)
    {


        if (!$data->getId()) {
            throw new Exception('The event should have an id for getting the Participant List');
        }
        if (!$this->eventRepository->find($data->getId())) {
            throw new Exception('The event should have an id for Participant List');
   
        }     
   
        $eventParticipants =  $this->eventParticipantRepository->getParticipants($data->getId());
        $participants = array();

   
        foreach( $eventParticipants as $ep )
        {
            array_push($participants, $ep->getUser()->getId());  
        }  
  
        $users = $this->userRepository->userEvents($participants);  

        return $users;  

        
    } 


   
}