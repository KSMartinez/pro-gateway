<?php


namespace App\Controller\Event; 

    
use Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Event;
use App\Service\EventService;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\EventParticipantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DownloadParticipantListAction     
 * @package App\Controller\Event   
 */  
#[AsController]   
class DownloadParticipantListAction extends AbstractController
{  
  
    /**    
     * @var EventService
     */  
    private EventService $eventService;


     /**    
     * @var EventParticipantRepository 
     */  
    private EventParticipantRepository $eventParticipantRepository;

    
     /**    
     * @var EventRepository   
     */  
    private EventRepository $eventRepository;


    /**    
     * @var EventRepository   
     */  
    private UserRepository $userRepository;

 
  
    /**
     * DownloadParticipantListAction constructor.  
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService,  EventParticipantRepository $eventParticipantRepository,  
    EventRepository $eventRepository,  UserRepository $userRepository)
    {
  
        $this->userRepository = $userRepository;   
        $this->eventRepository = $eventRepository;   
        $this->eventService = $eventService;
        $this->eventParticipantRepository = $eventParticipantRepository; 

        
    }


   
   
     /**
     * @param Event $data
     * @return Response 
     */  
    public function __invoke(Event $data)   
    {


        if (!$data->getId()) {
            throw new Exception('The event should have an id for getting the Participant List');
        }
        if (!$this->eventRepository->find($data->getId())) {
            throw new Exception('The event should have an id for Participant List');
   
        }     
   
        $eventParticipants =  $this->eventParticipantRepository->getParticipants($data->getId());
        $participants = array();


        foreach( $eventParticipants as $ep )
        {
            array_push($participants, $ep->getUser()->getId());  
        }  
   
  
        $users = $this->userRepository->userEvents($participants);  

        $options = new Options();       
        $options->set('defaulFont', 'Courier');                
 
        $dompdf = new Dompdf($options);   
 
        $html = $this->render('user/participantList.html.twig', ['participants' => $users]);        
   
        $dompdf->loadHtml($html);    
     
        $dompdf->setPaper('A4', 'portrait'); 

        $dompdf->render(); 
               
        $dompdf->stream();      

        return new Response('', 200, [  
            'Content-Type' => 'application/pdf',
        ]);

   
    }


    
    
  
}      