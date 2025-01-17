<?php

namespace App\Event;

use App\Entity\Candidature;
use Symfony\Contracts\EventDispatcher\Event;

class CandidatureCreatedEvent extends Event
{

    public const NAME = 'candidature.create';

    public function  __construct(private Candidature $candidature)
    {
    }

    /**
     * @return Candidature
     */
    public function getCandidature(): Candidature
    {
        return $this->candidature;
    }

    /**
     * @param Candidature $candidature
     */
    public function setCandidature(Candidature $candidature): void
    {
        $this->candidature = $candidature;
    }

    public function __toString(): string
    {
       return 'Candidature ID: ' . $this->candidature->getId();
    }


}