<?php

namespace App\Service;
use Exception;
use App\Entity\User;
use App\Entity\SavedOfferSearch;
use App\Entity\EmailNotification;
use App\Entity\NotificationSource;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmailTemplateRepository;
use App\Repository\NotificationSourceRepository;
use Symfony\Component\VarDumper\VarDumper;
use App\Service\EmailTemplateService;   
use App\Entity\EventParticipant;  
use Symfony\Component\DependencyInjection\Loader\Configurator\service;

class EmailNotificationService
{

    public function __construct(private EntityManagerInterface $entityManager, private NotificationSourceRepository $notificationSourceRepository, 
    private EmailTemplateRepository $emailTemplateRepository,  private EmailTemplateService $emailTemplateService)
    {
    }

    /**
     * For each user, if new offers are found, we create an email notification. We don't really care if notification is already created or not.
     * If there are multiple searches for the same user, multiple notifications will be created. We will deal with sending emails in another service
     * @param SavedOfferSearch $search
     * @param integer $numberOfNewOffers
     * @return void
     * @throws Exception
     */
    public function createEmailNotification(SavedOfferSearch $search, int $numberOfNewOffers)
    {
        $notificationSource = $this->notificationSourceRepository->findOneBy(array('sourceLabel' => NotificationSource::NEW_OFFER));

        if (!$notificationSource){
            throw new Exception("Notification Source for " . NotificationSource::NEW_OFFER . " not found. Please add this to the notificationSource table");
        }

        //todo Find a way to customize the message template for offers produced as a result of multiple searches.
        $messageTemplate = $this->emailTemplateRepository->getMessageTemplate($notificationSource);
        if (!$messageTemplate){
            throw new Exception("Message template for the selected notification source \"" . $notificationSource->getSourceLabel() . "\" was not found. Please add this to the messageTemplate table");
        }
        $emailNotification = new EmailNotification();
        $emailNotification->setUser($search->getUser())
                          ->setMessage($messageTemplate->getMessageTemplate())
                          ->setSource($notificationSource);

        $this->entityManager->persist($emailNotification);
        $this->entityManager->flush();

    }




     /**
     *  * Send notifications to the creator of an event one day before the end   
     * @param EventParticipant[] $eventParticipants
     * @param string $notificationSource  
     * @return void    
     * @throws Exception  
     */
    public function emailNotificationOneDayBeforeTheEvent(array $eventParticipants,  string $notificationSource)
    {
  

        $this->createEmailNotificationForEvents($eventParticipants, $notificationSource);            

    }

    
     /**
     * Send notifications to the creator of an event one day before the end of the event  
     * @param EventParticipant[] $eventParticipants
     * @param string $notificationSource  
     * @param bool $forAdmin  
     * @return void    
     * @throws Exception  
     */
    public function emailNotificationOneDayBeforeTheEndOfTheEvent(array $eventParticipants, string $notificationSource, bool $forAdmin)
    {

        $this->createEmailNotificationForEvents($eventParticipants, $notificationSource, $forAdmin); 
                

    }



     /**
     * For each user, if new offers are found, we create an email notification. We don't really care if notification is already created or not.
     * If there are multiple searches for the same user, multiple notifications will be created. We will deal with sending emails in another service
     * @param EventParticipant[] $eventParticipants    
     * @param string $notificationSource   
     * @param bool $forAdmin   
     * @return void          
     * @throws Exception  
     */
    public function createEmailNotificationForEvents(array $eventParticipants, string $notificationSource, bool $forAdmin = null)
    {

        $events_id  = array(); 

        foreach($eventParticipants as $ep){
        
            array_push($events_id,  $ep->getEvent()->getId());       
        }  

 
        $notificationSource = $this->notificationSourceRepository->findOneBy(array('sourceLabel' => $notificationSource));

     
        if (!$notificationSource){     
            throw new Exception("Notification Source for " . $notificationSource . " not found. Please add this to the notificationSource table");
        }

    
        //todo Find a way to customize the message template for offers produced as a result of multiple searches.
        $messageTemplate = $this->emailTemplateRepository->getMessageTemplate($notificationSource);
        if (!$messageTemplate){
            throw new Exception("Message template for the selected notification source \"" . $notificationSource->getSourceLabel() . "\" was not found. Please add this to the messageTemplate table");
        }


        $this->sendMessageForNotificationOneDayBeforeEvent($eventParticipants, $notificationSource, $forAdmin);

    }

   
     /**
     * @param EventParticipant[] $entities 
     * @param NotificationSource $notificationSource     
     * @param bool $forAdmin       
     * @return void       
     * @throws Exception             
     */
    public function sendMessageForNotificationOneDayBeforeEvent(array $entities, NotificationSource $notificationSource, bool $forAdmin = null){

        $messageTemplate = $this->emailTemplateRepository->getMessageTemplate($notificationSource);

        
        if (!$messageTemplate){
            throw new Exception("Message template for the selected notification source \"" . $notificationSource->getSourceLabel() . "\" was not found. Please add this to the messageTemplate table");
        }

        
      //  $k = 0; 
        foreach($entities as $ent){           
            $emailNotification = new EmailNotification();   

            $message = $this->emailTemplateService->setMessageTemplate($messageTemplate->getMessageTemplate(), $ent->getEvent());



            if($forAdmin){

                # For the alert one day before the end 
                if( in_array( "ROLE_ADMIN",   $ent->getUser()->getRoles() ) )
                {

                        $emailNotification->setUser($ent->getUser())
                        ->setMessage($message)
                        ->setSource($notificationSource);

                        $this->entityManager->persist($emailNotification);
                      //  $k++;  

                }

            }
            else{

                # For the alerts one day before the event or one day before the end for non admin users  
                if( !in_array( "ROLE_ADMIN",   $ent->getUser()->getRoles() ) )
                {
                    $emailNotification->setUser($ent->getUser())
                    ->setMessage($message)
                    ->setSource($notificationSource);
    
                    $this->entityManager->persist($emailNotification);
                  //  $k++;  

                }   
            }
                   
        }    
        
        $this->entityManager->flush();
    }


}