<?php


namespace App\Service;

use Exception;
use App\Entity\Education;
use App\Repository\EducationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File; 

class EducationService
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

     /**     
     * @var EducationRepository 
     */
    private EducationRepository $educationRepository;   

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, EducationRepository $educationRepository)
    {
        $this->entityManager = $entityManager;
        $this->educationRepository = $educationRepository; 
    }

   

        
    /**
     * @param Education $education
     * @return bool 
     * @throws Exception
     */ 
    public function checkDatas(Education $education) : bool
    {  
    
        if (!$education->getId()) {   
            throw new Exception('The education should have an id for updating');
        }
        if (!$this->educationRepository->find($education->getId())) {
            throw new Exception('The education should have an id for updating');
   
        }    

        // We check if all the mandatory datas have been filled  (mandatory == obligatoire)
        $check = 0; 

      // Vérifier que tous les champs obligatoires sont non null revient à vérifier qu'aucun de ces champs n'est null 
        $education = $this->educationRepository->findById($education->getId())[0];    

        if( $education->getDiploma() == null){  
            $check++;   
        }
        if( $education->getStudyLevel() == null){
            $check++; 
        }      
              
         
        if( $check >= 1){        
          return false; 
        }
                   
        return true;    
    
    }   

    

   
}