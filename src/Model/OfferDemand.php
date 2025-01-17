<?php

namespace App\Model;

use App\Entity\Offer;

class OfferDemand
{

    private Offer $offer;
    private string $notificationMessage;

    /**
     * @return Offer
     */
    public function getOffer(): Offer
    {
        return $this->offer;
    }

    /**
     * @param Offer $offer
     * @return OfferDemand
     */
    public function setOffer(Offer $offer): OfferDemand
    {
        $this->offer = $offer;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotificationMessage(): string
    {
        return $this->notificationMessage;
    }

    /**
     * @param string $notificationMessage
     * @return OfferDemand
     */
    public function setNotificationMessage(string $notificationMessage): OfferDemand
    {
        $this->notificationMessage = $notificationMessage;
        return $this;
    }


}