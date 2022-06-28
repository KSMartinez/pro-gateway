<?php

namespace App\Service;

use App\Entity\NotificationSource;
use DateTime;
use Exception;
use App\Repository\EventRepository;  

use App\Repository\EventParticipantRepository;
use Symfony\Component\VarDumper\VarDumper;

class EventAlertService
{
  

    
    public function __construct(private EmailNotificationService $emailNotificationService, private EventParticipantRepository $eventParticipantRepository)
    {
         


    }
  

    /**  
     * Send alert to users one day before the event   
     * @return void
     * @throws Exception
     */
    public function alertUsersOneDayBeforeTheEvent()
    {

        $eventParticipants = $this->eventParticipantRepository->usersToNotifyOneDayBeforeTheEvent();  

       
        $this->emailNotificationService->emailNotificationOneDayBeforeTheEvent($eventParticipants, NotificationSource::EVENT_NOTIFICATION_ONE_DAY_BEFORE);  


    }   

  
     /**  
     * Send alert to users one day before the event   
     * @return void
     * @throws Exception
     */
    public function alertUsersOneWeekBeforeTheEvent()
    {

        $eventParticipants = $this->eventParticipantRepository->usersToNotifyOneWeekBeforeTheEvent();  
       
        $this->emailNotificationService->emailNotificationOneDayBeforeTheEvent($eventParticipants, NotificationSource::EVENT_NOTIFICATION_ONE_WEEK_BEFORE);  

    }


    

    /**    
     * @return void
     * @param boolean $forAdmin 
     */
    public function notificationOneDayBeforeTheEndOfEvents($forAdmin = true)
    {

 
      $eventParticipants = $this->eventParticipantRepository->usersToNotifyOneDayBeforeTheEvent();  

   
      $this->emailNotificationService->emailNotificationOneDayBeforeTheEndOfTheEvent($eventParticipants, NotificationSource::EVENT_NOTIFICATION_ONE_DAY_BEFORE_THE_END, $forAdmin);  
     
    }   


 }   

      

