<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
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
     * @param User $user
     * @return User  
     */
    public function charteUtilisation(User $user, $charteSigned)
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

   
}