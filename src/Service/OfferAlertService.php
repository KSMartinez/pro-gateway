<?php

namespace App\Service;

use App\Entity\EmailNotification;
use App\Entity\NotificationSource;
use App\Entity\SavedOfferSearch;
use App\Repository\EmailTemplateRepository;
use App\Repository\NotificationSourceRepository;
use App\Repository\OfferRepository;
use App\Repository\SavedOfferSearchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class OfferAlertService
{

    public function __construct(private EntityManagerInterface $entityManager, private EmailTemplateRepository $emailTemplateRepository, private NotificationSourceRepository $notificationSourceRepository, private SavedOfferSearchRepository $savedOfferSearchRepository, private OfferRepository $offerRepository)
    {
    }

    /**
     * We get all the searches that are saved for each user (make sure we only get the ones that are active)
     * For each search, we will check if new offers are available by comparing the posting date of the offer with
     * the date of last search. If new offers are found, we will create an email notification for the user with "new offer"
     * as the notification source.
     * @return void
     * @throws Exception
     */
    public function alertUsersWithSavedSearchForNewOffers()
    {
        $searches = $this->savedOfferSearchRepository->findBy(array('isActive' => true));

        foreach ($searches as $search) {
            $numberOfNewOffers = $this->offerRepository->getNumberOfNewOffers($search);
            //we create a new email notification if we find there are new offers. We don't care if notifications already exists.
            //we will worry about that when sending out the emails.
            if ($numberOfNewOffers > 0) {
                //@phpstan-ignore-next-line todo remove this. Issue with the type of numberOfNewOffers
                $this->createEmailNotification($search, $numberOfNewOffers);
            }
        }

    }

    /**
     * For each user, if new offers are found, we create an email notification. We don't really care if notification is already created or not.
     * If there are multiple searches for the same user, multiple notifications will be created. We will deal with sending emails in another service
     * @param SavedOfferSearch $search
     * @param integer $numberOfNewOffers
     * @return void
     * @throws Exception
     */
    private function createEmailNotification(SavedOfferSearch $search, int $numberOfNewOffers)
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

    }

}