<?php


namespace App\Service;

use Exception;
use App\Entity\Education;
use App\Entity\Experience;
use App\Repository\EducationRepository;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File; 

class ExperienceService
{

    

     /**     
     * @var ExperienceRepository 
     */
    private ExperienceRepository $experienceRepository;   

    /**
     * @param ExperienceRepository $experienceRepository   
     */
    public function __construct(ExperienceRepository $experienceRepository)
    {
        $this->experienceRepository = $experienceRepository; 
    }

   

            
    /**
     * @param Experience $experience    
     * @return bool 
     * @throws Exception
     */ 
    public function checkDatas(Experience $experience) : bool
    {  
    
        if (!$experience->getId()) {   
            throw new Exception('The experience should have an id for updating');
        }
        if (!$this->experienceRepository->find($experience->getId())) {
            throw new Exception('The experience should have an id for updating');
   
        }    

        // We check if all the mandatory datas have been filled  (mandatory == obligatoire)
        $check = 0; 

      // Vérifier que tous les champs obligatoires sont non null revient à vérifier qu'aucun de ces champs n'est null 
        $experience = $this->experienceRepository->findById($experience->getId())[0];    

        if( $experience->getJobname() == null){  
            $check++;   
        }
             
              
        if( $check >= 1){        
          return false; 
        }
                   
        return true;    
    
    }   

    

   
}