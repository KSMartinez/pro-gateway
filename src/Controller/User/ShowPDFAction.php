<?php


namespace App\Controller\User;


   
use Exception;
use Dompdf\Dompdf; 
use App\Entity\User; 
use Dompdf\Options;     
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Repository\SkillRepository;
use App\Repository\EducationRepository;
use App\Repository\ExperienceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * Class ShowPDFAction
 * @package App\Controller\User
 */
#[AsController]
class ShowPDFAction extends AbstractController
{

   
     /**
     * @var EducationRepository 
     */
    private EducationRepository $educationRepository;

    
     /**
     * @var ExperienceRepository 
     */
    private ExperienceRepository $experienceRepository;


    
     /**
     * @var SkillRepository 
     */
    private SkillRepository $skillRepository;


      /**
     * @var UserRepository 
     */
    private UserRepository $userRepository;

   

    

    /**
     * ShowPDFAction constructor.
     * @param EducationRepository $educationRepository
     * @param ExperienceRepository $experienceRepository
     * @param SkillRepository $skillRepository
     * @param UserRepository $userRepository    
     */   
    public function __construct(EducationRepository $educationRepository, 
    ExperienceRepository $experienceRepository, SkillRepository $skillRepository,  UserRepository $userRepository)
    {

        $this->educationRepository = $educationRepository; 
        $this->experienceRepository = $experienceRepository; 
        $this->skillRepository = $skillRepository;  
        $this->userRepository = $userRepository;  
     
    }
 

    /**
     * @param User $data 
     * @return Response 
     */
    public function __invoke(User $data): Response  
    {
        
         # Great, we gonna give the cv fields to the view and master the pdf rendering like this 
   
         # What we need to render :  
         # education :  "diploma" => , "study_level" => , "domain" =>  , "school" =>  , "start_month" =>  , "end_month" => ,
         # "start_year" => ,  "end_year" => , "current_school" =>  ,  

         # experience : "description" =>  ,  "jobname" =>  ,  "company"  =>  , "category"  =>  , "country" =>  , "city" => , 
         # "start_month" =>  , "start_year" => , "end_month" => ,  "end_year" => ,  "current_job" =>  

         # skill :   "skill1" =>  ,    "skill1" =>  ,   "skill1" =>  ,   "skill1" =>  ,    "skill1" =>  ,
         # "complementary_skill" =>   
           
         if (!$data->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->userRepository->find($data->getId())) {
            throw new Exception('The user should have an id for updating');
                
        }   
        if ($data->getCV() == null) {      
            throw new Exception("The user doesn't have a cv");
        } 
     

        $educations = $this->educationRepository->userEducations($data->getCV()->getId()); 

        $experiences = $this->experienceRepository->userExperiences($data->getCV()->getId()); 

        $skills = $this->skillRepository->userSkills($data->getCV()->getId()); 
     
     
         $options = new Options();   
         $options->set('defaulFont', 'Courier');        
 
         $dompdf = new Dompdf($options); 
  
         $html = $this->render('test/index.html.twig', ['educations' => $educations, 'experiences' => $experiences, 
         'skills' => $skills]);    
 
         $dompdf->loadHtml($html);    
    
         $dompdf->setPaper('A4', 'portrait'); 
 
         $dompdf->render(); 
             
         $dompdf->stream();      
 
         return new Response('', 200, [  
             'Content-Type' => 'application/pdf',
         ]);

        

    }
}     