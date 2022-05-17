<?php

namespace App\Service;

use App\Repository\OfferRepository;
use App\Repository\SavedOfferSearchRepository;
use Exception;

class OfferAlertService
{

    public function __construct(private EmailNotificationService $emailNotificationService, private SavedOfferSearchRepository $savedOfferSearchRepository, private OfferRepository $offerRepository)
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
                $this->emailNotificationService->createEmailNotification($search, $numberOfNewOffers);
            }
        }

    }

}