<?php


namespace App\Controller\User;


use Exception;
use App\Entity\User;
use App\Entity\Event;
use App\Service\EventParticipantService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserEventsAction
 * @package App\Controller\User 
 */
#[AsController]
class UserEventsAction extends AbstractController
{   
   
    /**
     * @var EventParticipantService
     */
    private EventParticipantService $eventParticipantService;

    /**
     * UserEventsAction constructor.
     * @param EventParticipantService $eventParticipantService
     */
    public function __construct(EventParticipantService $eventParticipantService)
    {
        $this->eventParticipantService = $eventParticipantService;
    }
   
 
  
    
    /**   
     * @param User $data        
    *  @return Event[]   
     * @throws Exception   
     */
    public function __invoke(User $data)  
    {
        return $this->eventParticipantService->userEvents($data);
 
    }
}         