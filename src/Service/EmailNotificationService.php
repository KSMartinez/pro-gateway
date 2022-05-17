<?php

namespace App\Service;
use App\Entity\EmailNotification;
use App\Entity\NotificationSource;
use App\Entity\SavedOfferSearch;
use App\Repository\EmailTemplateRepository;
use App\Repository\NotificationSourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class EmailNotificationService
{

    public function __construct(private EntityManagerInterface $entityManager, private NotificationSourceRepository $notificationSourceRepository, private EmailTemplateRepository $emailTemplateRepository)
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


}