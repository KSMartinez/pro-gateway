<?php


namespace App\Controller\EventParticipant;


use Exception;
use App\Entity\Event;
use App\Entity\User; 
use App\Entity\EventParticipant;
use App\Service\EventParticipantService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EventRegistrationAction
 * @package App\Controller\EventParticipant 
 */
#[AsController]
class EventRegistrationAction extends AbstractController
{   
   
    /**
     * @var EventParticipantService
     */
    private EventParticipantService $eventParticipantService;

    /**
     * EventRegistrationAction constructor.
     * @param EventParticipantService $eventParticipantService
     */
    public function __construct(EventParticipantService $eventParticipantService)
    {
        $this->eventParticipantService = $eventParticipantService;
    }
   


    
    /**   
     * @param EventParticipant $data        
    *  @return EventParticipant 
     * @throws Exception   
     */
    public function __invoke(EventParticipant $data)  
    {
        return $this->eventParticipantService->eventRegistration($data);

    }
}         