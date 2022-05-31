<?php


namespace App\Controller\Event; 

    
use Exception;
use App\Service\EventService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class RandomEventsListAction     
 * @package App\Controller\Event   
 */  
#[AsController]
class RandomEventsListAction extends AbstractController
{

    /**
     * @var EventService
     */  
    private EventService $eventService;

    /**
     * CheckDatasAction constructor.  
     * @param EventService $EventService
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }



    /**
     * @return Event[]       
     */
    public function __invoke()   
    {

       return  $this->eventService->randomEventsList();
        
    }
}      