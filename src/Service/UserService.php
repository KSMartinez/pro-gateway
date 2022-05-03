<?php


namespace App\Service;


use Exception;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;



    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository; 
    }

    /**
     * @param User $user
     * @return User  
     */
    public function charteUtilisation(User $user, bool $charteSigned)
    {
    
       
       
        # Steps : 
        # in the front if ( charteSigned  == false ) => show the popup 
        # Get the response on this function    
        # if request->response == Yes, => redirection to the home site page & set charteSigned => true 
        # if request->response == No,  => redirection to the profil 
          
    
        if( $charteSigned ){    

            $user->setCharteSigned($charteSigned); 
            $this->entityManager->persist($user);
            $this->entityManager->flush();       
            
        }
        
        # According to the $user->getCharteSigned(), the front gonna do the redirection like this : 
        // if $user->getCharteSigned() == true => return $this->redirectToRoute('homepage');
        // if $user->getCharteSigned() == false => return $this->redirectToRoute('profil');  

        return $user;       

    }  

    /**   
     * @param User $user
     * @return User  
     * @throws Exception
     */
    public function updatePicture(User $user)
    {

        if (!$user->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->userRepository->find($user->getId())) {
            throw new Exception('The user should have an id for updating');
   
        }  
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;       


    }

    
    /**
     * @param 
     * @return Array
     */
    public function userList()
    {
 
       
        return $this->userRepository->annuaireList(); 
        
     

    }

    


}