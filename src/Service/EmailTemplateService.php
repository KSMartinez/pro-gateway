<?php

namespace App\Service;
use Exception;
use App\Entity\EventAnswer;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventQuestionRepository;
use App\Entity\Event; 

class EmailTemplateService
{
   

    public function __construct()    
    {
    }
    


      /**
     * @param string $messageTemplate   
     * @param Event $event        
     * @return string     
     * @throws Exception
     */ 
    public function setMessageTemplate(string $messageTemplate, Event $event) 
    {      

        # We gonna set the message later      
        return $messageTemplate . " " . $event->getId(); 

    }
   


}