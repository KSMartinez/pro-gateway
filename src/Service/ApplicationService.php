<?php


namespace App\Service;

use Exception;
use App\Entity\Offer;
use DateTimeImmutable;
use App\Entity\Application;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class ApplicationService
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

   
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
   
    
    
     /**
     * @param Application $application 
     * @return Application    
     * @throws Exception   
     */        
    public function userApplication(Application $application)
    {
                
        if ( $application == null ) {   
            throw new Exception('The application should not be null');
        }
           
   
        # Send the user cv and the description of his application, it's a mail part to master with Akhil 

        $this->entityManager->persist($application);
        $this->entityManager->flush();
        return $application;                 
    }   



    
}   