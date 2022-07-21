<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationSourceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class NotificationService
{
    public function __construct(private EntityManagerInterface $entityManager, private NotificationSourceRepository $notificationSourceRepository)
    {
    }

    /**
     * A new notification is created for each notification.
     * //todo review squashing notifications together especially for Offers. Make new function that creates unique notifications?
     *
     * @param string $notificationMessage
     * @param string $source
     * @param User   $user
     * @throws Exception
     * @return void
     */
    public function createNotification(string $notificationMessage, string $source, User $user)
    {
        $groupDemandNotificationSource = $this->notificationSourceRepository->findOneBy(array('label' => $source));
        if (!$groupDemandNotificationSource) {
            throw new Exception("Notification Source for " . $source . " not found. Please add this to the notificationSource table");
        }
        $notification = new Notification();
        $notification->setSource($groupDemandNotificationSource)
                     ->setUser($user)
                     ->setMessage($notificationMessage)
                     ->setCreatedOn(new DateTime('now'));
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    //todo implement offer notification
    public function createOfferNotification(\App\Entity\Offer $offer) : void
    {
        //get admins
        // create notification with offer as source and admins as user.
    }
}