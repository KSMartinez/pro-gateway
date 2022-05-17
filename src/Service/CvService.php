<?php


namespace App\Service;


use DateTime;
use Exception;  
use App\Entity\CV; 
use App\Repository\CVRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File; 

class CvService
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

     /**  
     * @var CvRepository 
     */
    private CVRepository $cVRepository;   


    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, CVRepository $cVRepository)
    {
        $this->entityManager = $entityManager;
        $this->cVRepository = $cVRepository; 
    }

    /**
     * @param File $file
     * @return CV
     */
    public function upload(File $file)
    {
    
       
        $cv = new CV(); 
        $cv->setFile($file); 

        return $cv;           
  
    }  

    
  /**
     * @param CV $cv
     * @return CV
     * @throws Exception
     */
    public function updateCVFile(CV $cv): CV
    {

        if (!$cv->getId()) {
            throw new Exception('The CV should have an id for updating');
        }
        if (!$this->cVRepository->find($cv->getId())) {
            throw new Exception('The CV should have an id for updating');

        }
        //$cv->setUpdatedAt(new DateTime('now'));
        $this->entityManager->persist($cv);
        $this->entityManager->flush();
        return $cv;

    }

    //  /**
    //  * @param 
    //  * @return void 
    //  */
    // public function testPDF()
    // {

    //     $test = "Test my nigga";   

    //     $html = $this->redirectToRoute('test/index.html.twig', ['test' => $test]);

    //     $html = $this->render('test/index.html.twig', ['test' => $test]);

    //     ///dd('kooooll');   

    //     $this->pdfService->showPdfFile($html);   
    // }  
     



   
}