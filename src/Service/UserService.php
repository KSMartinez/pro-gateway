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
     * @param UserRepository $userRepository
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
    
       
       
        // Steps : 
        // in the front if ( charteSigned  == false ) => show the popup 
        // Get the response on this function    
        // if request->response == Yes, => redirection to the home site page & set charteSigned => true 
        // if request->response == No,  => redirection to the profil 
          
    
        if( $charteSigned ){    

            $user->setCharteSigned($charteSigned); 
            $this->entityManager->persist($user);
            $this->entityManager->flush();       
            
        }
        
        // According to the $user->getCharteSigned(), the front gonna do the redirection like this : 
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
     * @return Array<User>
     */ 
    public function userList() : Array 
    {  
     
        return $this->userRepository->annuaireList(); 
    
    }

    
      
    /**
     * @param User $user 
     * @return bool 
     * @throws Exception
     * return true if the button "modifier" can be displayed and false if is not the case 
     */ 
    public function checkFilledDatas(User $user) : bool
    {  
    
        if (!$user->getId()) {   
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->userRepository->find($user->getId())) {
            throw new Exception('The user should have an id for updating');
   
        }    

        // We check if all the mandatory datas have been filled  (mandatory == obligatoire)
        $check = 0; 

      // Vérifier que tous les champs obligatoires sont non null revient à vérifier qu'aucun de ces champs n'est null 
        $user = $this->userRepository->findById($user->getId())[0];    

        // How to check this field, it always null despite the fact that we don't get any error on Apiplatform 
        // if($user->getImageLink() == null){
        //     $check++;        
        // }  
        if( $user->getProfilTitle() == null){  
            $check++;   
        }
        if( $user->getFirstname() == null){
            $check++; 
        }    
        if( $user->getSurname() == null){
            $check++; 
        }
        if( $user->getUseFirstname() == null){
            $check++; 
        }   
        if( $user->getUseSurname() == null){
            $check++;         
        }             
            
         
        if( $check >= 1){        
          return false; 
        }
                   
        return true;    
    
    }


    // /**   
    //  * @param User $user
    //  * @return User  
    //  * @throws Exception
    //  */
    // public function changeBirthdayVisibility(User $user){


    //     if (!$user->getId()) {
    //         throw new Exception('The user should have an id for updating');
    //     }
    //     if (!$this->userRepository->find($user->getId())) {
    //         throw new Exception('The user should have an id for updating');
   
    //     }  
        
    //     $visible = !$user->getBirthdayIsPublic();        
    //     $user->setBirthdayIsPublic($visible); 
    //     $this->entityManager->persist($user);
    //     $this->entityManager->flush();
    //     return $user;           


    // }

      
    // /**   
    //  * @param User $user  
    //  * @return User  
    //  * @throws Exception
    //  */
    // public function ChangeCityCountryVisibility(User $user){

    //     if (!$user->getId()) {
    //         throw new Exception('The user should have an id for updating');
    //     }
    //     if (!$this->userRepository->find($user->getId())) {
    //         throw new Exception('The user should have an id for updating');
   
    //     }  



        
    //     $visible = !$user->getCityAndCountryIsPublic();   
        
    
    //     $user->setCityAndCountryIsPublic($visible); 
    //     $this->entityManager->persist($user);
    //     $this->entityManager->flush();
    //     return $user;               
   
    // }

    


}