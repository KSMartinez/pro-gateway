<?php

namespace App\EventSubscriber;

use App\Event\CandidatureCreatedEvent;
use App\Service\NexusAPIService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
class CandidatureCreatedSubscriber implements EventSubscriberInterface
{

    /**
     * @param NexusAPIService $nexusAPIService
     */
    public function __construct(private NexusAPIService $nexusAPIService)
    {
    }

    /**
     * @param CandidatureCreatedEvent $event
     * @return void
     */
    public function dispatchCandidatureToIntermediateAPI(CandidatureCreatedEvent $event)
    {

        $this->nexusAPIService->executeCommandToSendCandidature($event->getCandidature());
    }

    /**
     * @return mixed[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CandidatureCreatedEvent::NAME => [
                'dispatchCandidatureToIntermediateAPI', 10],
        ];
    }
}
