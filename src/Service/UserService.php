<?php

namespace App\Service;

use App\Entity\Conversation;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Serializer\UserNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\File\File;

class UserService
{
    const CONTENT_TYPE_JSON = 'json';
    const DATA_JSON_PARAM = 'id';

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param RequestStack $requestStack
     * @param UserNormalizer $userNormalizer
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private RequestStack $requestStack,
        private UserNormalizer $userNormalizer
    )
    {
    }

    /**
     * @param User $user
     * @param bool $charteSigned
     * @return User
     */
    public function charteUtilisation(User $user, bool $charteSigned)
    {


        // Steps : 
        // in the front if ( charteSigned  == false ) => show the popup 
        // Get the response on this function    
        // if request->response == Yes, => redirection to the home site page & set charteSigned => true 
        // if request->response == No,  => redirection to the profil 


        if ($charteSigned) {

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
     * @return User
     * @throws Exception
     */
    public function updatePicture(): User
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }

        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');


        if (!($object instanceof User)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->userRepository->find($object->getId())) {
            throw new Exception('The user should have an id for updating');
        }

        $file = $request->files->get('imageFile');
        if ($file instanceof File) {
            $object->setFile($file);
            $object->setUpdatedAt(new \DateTime());
        }

        return $object;
    }

    /**
     * @return User|void
     * @throws Exception
     */
    public function updateImageStock()
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof User)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->userRepository->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        if ($request->getContentType() === self::CONTENT_TYPE_JSON) {
            $arrayDataJson = json_decode($request->getContent(), true);
            if (is_array($arrayDataJson)) {
                $imageStockIdReceived = $arrayDataJson[self::DATA_JSON_PARAM];
                $pathFilename = $this->userNormalizer->imageStockIdExist($imageStockIdReceived);
                $object->setImagePath($pathFilename);
                $this->entityManager->flush();

                return $object;
            }
            throw new Exception();
        }
    }


    /**
     * @return Array<User>
     */
    public function userList(): array
    {

        return $this->userRepository->annuaireList();

    }


    /**
     * @param User $user
     * @return bool
     * @throws Exception
     * return true if the button "modifier" can be displayed and false if is not the case
     */
    public function checkFilledDatas(User $user): bool
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
        if ($user->getProfilTitle() == null) {
            $check++;
        }
        if ($user->getFirstname() == null) {
            $check++;
        }
        if ($user->getSurname() == null) {
            $check++;
        }
        if ($user->getUseFirstname() == null) {
            $check++;
        }
        if ($user->getUseSurname() == null) {
            $check++;
        }


        if ($check >= 1) {
            return false;
        }

        return true;

    }


    /**
     * @param User $user
     * @return User
     * @throws Exception
     */
    public function rejectedCharte(User $user)
    {

        if (!$user->getId()) {
            throw new Exception('The user should have an id for updating');
        }
        if (!$this->userRepository->find($user->getId())) {
            throw new Exception('The user should have an id for updating');

        }

        $user->setCharteSigned(false);
        $user->setRejectedCharte(true);
        // $this->entityManager->persist($user);   
        $this->entityManager->flush();
        return $user;


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
    /**
     * @param User $user
     * @return array<Group>
     */
    public function getGroupsCreatedByUser(User $user): array
    {
        return $this->entityManager->getRepository(Group::class)->findBy(['createdBy' => $user]);
    }




}