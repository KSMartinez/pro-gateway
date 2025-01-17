<?php


namespace App\Controller\EventQuestion; 

    
use Exception;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventAnswer;
use App\Entity\EventQuestion;
use App\Service\EventService;
use App\Service\EventQuestionService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EventAnswerQuestionAction     
 * @package App\Controller\EventQuestion
 */  
#[AsController]   
class EventAnswerQuestionAction extends AbstractController
{  
       

      
     /**    
     * @var EventQuestionService 
     */  
    private EventQuestionService $eventQuestionService;
  
    /**
     * EventAnswerQuestionAction constructor.  
     * @param EventQuestionService $eventQuestionService
     */
    public function __construct(EventQuestionService $eventQuestionService)
    {
 
        $this->eventQuestionService = $eventQuestionService;   
         

        
    }


   
   
     /**
     * @param EventAnswer $data
     * @return EventAnswer  
     */  
    public function __invoke(EventAnswer $data)   
    {  
  
        return  $this->eventQuestionService->saveAnswerQuestion($data);
      
    }
           
    
  
}      