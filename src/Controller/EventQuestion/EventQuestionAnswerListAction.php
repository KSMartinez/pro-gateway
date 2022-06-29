<?php


namespace App\Controller\EventQuestion; 

    
use App\Entity\EventQuestion;  
use App\Entity\EventAnswer;
use App\Service\EventQuestionService;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EventQuestionAnswerListAction     
 * @package App\Controller\EventQuestion
 */  
#[AsController]   
class EventQuestionAnswerListAction extends AbstractController
{  
       

      
     /**    
     * @var EventQuestionService 
     */  
    private EventQuestionService $eventQuestionService;
  
    /**
     * EventQuestionAnswerListAction constructor.  
     * @param EventQuestionService $eventQuestionService
     */
    public function __construct(EventQuestionService $eventQuestionService)
    {
 
        $this->eventQuestionService = $eventQuestionService;   

        
    }


   
   
     /**
     * @param EventQuestion $data
     * @return EventAnswer[]  
     */  
    public function __invoke(EventQuestion $data)   
    {  
  
        return  $this->eventQuestionService->getAnswers($data);
      
    }
           
    
  
}      