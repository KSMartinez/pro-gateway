<?php


namespace App\Controller\Event;


use Exception;
use App\Entity\Event; 
use App\Service\EventService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UpdatePictureAction
 * @package App\Controller\Event
 */
#[AsController]
class UpdatePictureAction extends AbstractController
{

    /**
     * @var EventService
     */
    private EventService $eventService;

    /**
     * UpdatePictureAction constructor.
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
 

    /**
     * @param Event $data  
     * @return Event
     * @throws Exception
     */
    public function __invoke(Event $data): Event
    {
        return $this->eventService->updatePicture($data);
    }
}     