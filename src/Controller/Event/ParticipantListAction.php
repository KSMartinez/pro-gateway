<?php


namespace App\Controller\Event; 

    
use Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Event;
use App\Service\EventService;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\EventParticipantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ParticipantListAction     
 * @package App\Controller\Event   
 */  
#[AsController]   
class ParticipantListAction extends AbstractController
{  
  

      
     /**    
     * @var EventService 
     */  
    private EventService $eventService;
  
    /**
     * ParticipantListAction constructor.  
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService)
    {
 
        $this->eventService = $eventService;
      

        
    }


   
   
     /**
     * @param Event $data
     * @return User[]   
     */  
    public function __invoke(Event $data)   
    {

        return  $this->eventService->participantList($data);
   
    }


    
    
  
}      