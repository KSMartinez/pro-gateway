<?php

namespace App\Service;
use App\Entity\Group;
use App\Entity\GroupMember;
use App\Entity\GroupMemberStatus;
use App\Entity\GroupStatus;
use App\Entity\User;
use App\Model\GroupDemand;
use App\Repository\GroupMemberRepository;
use App\Repository\GroupMemberStatusRepository;
use App\Repository\GroupRepository;
use App\Repository\GroupStatusRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;

class GroupService
{
    const CONTENT_TYPE_JSON = 'json';
    const DATA_JSON_PARAM = 'id';

    /**
     * @param GroupMemberStatusRepository $groupMemberStatusRepository
     * @param EntityManagerInterface $entityManager
     * @param GroupRepository $groupRepository
     * @param GroupStatusRepository $groupStatusRepository
     * @param RequestStack $requestStack
     * @param ImageStockService $imageStockService
     */
    public function __construct(
        private GroupMemberStatusRepository $groupMemberStatusRepository,
        private EntityManagerInterface $entityManager,
        private GroupRepository $groupRepository,
        private GroupStatusRepository $groupStatusRepository,
        private RequestStack $requestStack,
        private ImageStockService $imageStockService
    )
    {
    }

    /**
     * @return Group[]
     * @throws Exception
     */
    public function getGroupDemands(): array
    {
        $groupDemandStatus = $this->getGroupStatus(GroupStatus::EN_ATTENTE);
        return $this->groupRepository->findBy(array('groupStatus' => $groupDemandStatus));
    }

    /**
     * @param GroupDemand $groupDemand
     * @return Group
     * @throws Exception
     * //todo notification for validating group
     */
    public function validateGroupDemand(GroupDemand $groupDemand): Group
    {
        $groupConfirmedStatus = $this->getGroupStatus(GroupStatus::ACTIF);
        $group = $groupDemand->getGroup();
        $group->setGroupStatus($groupConfirmedStatus);


        if (!$group->getCreatedBy()) {
            throw new Exception("Group createdBy is null. Cannot notify user");
        }

        //add the createdBy as the member of the group
        $groupMember = new GroupMember();
        $groupMember->setUser($group->getCreatedBy())
                    ->setGroupMemberStatus($this->getGroupMemberStatus(GroupMemberStatus::ACTIF))
                    ->setMemberRoles([GroupMember::ROLE_GROUP_USER])
                    ->setGroupOfMember($group);

        $this->entityManager->persist($group);
        $this->entityManager->persist($groupMember);
        $this->entityManager->flush();


//      $this->notificationService->createNotification($groupDemand->getNotificationMessage(),
//      NotificationSource::GROUP_DEMAND, $group->getCreatedBy());
        return $group;
    }

    /**
     * @param string $status
     * @return GroupMemberStatus
     * @throws Exception
     */
    private function getGroupMemberStatus(string $status): GroupMemberStatus
    {

        $groupMemberStatus = $this->groupMemberStatusRepository->findOneBy(['label' => $status]);

        if (!$groupMemberStatus) {
            throw new Exception('GroupMemberStatus with status ' . $status . ' not found. Please add this to the table');
        }

        return $groupMemberStatus;

    }

    /**
     * @param GroupDemand $groupDemand
     * @return Group
     * @throws Exception
     * //todo notification for rejecting group
     */
    public function rejectGroupDemand(GroupDemand $groupDemand): Group
    {
        $groupRefusedStatus = $this->getGroupStatus(GroupStatus::REFUSE);

        $group = $groupDemand->getGroup();
        $group->setGroupStatus($groupRefusedStatus);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * @param Group $group
     * @param User  $user
     * @return Group
     * @throws Exception
     * //todo notification for new group
     */
    public function createNewGroupDemand(Group $group, User $user): Group
    {
        $groupEnAttentStatus = $this->getGroupStatus(GroupStatus::EN_ATTENTE);

        if (empty($group->getName())){
            throw new Exception('Name for a group is mandatory');
        }
        if ($this->checkGroupWithNameExists($group->getName())){
            throw new Exception('Group with this name already exists');
        }

        $group->setDateCreated(new DateTime('now'))
              ->setCreatedBy($user)
              ->setGroupStatus($groupEnAttentStatus);

        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;



    }

    /**
     * @param string $status
     * @return GroupStatus
     * @throws Exception
     */
    private function getGroupStatus(string $status): GroupStatus
    {
        $groupStatus = $this->groupStatusRepository->findOneBy(array('label' => $status));
        if (!$groupStatus) {
            throw new Exception('GroupStatus with label ' . $status . ' was not found in the table. Please add it correctly.');
        }
        return $groupStatus;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkGroupWithNameExists(string $name): bool
    {

        $group = $this->groupRepository->findOneBy(['name' => $name]);
        return $group == null;
    }

    /**
     * @return Group
     * @throws Exception
     */
    public function updatePicture(): Group
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof Group)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->groupRepository->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        $file = $request->files->get('imageFile');

        if ($file instanceof File) {
            $object->setFile($file);
            $object->setUpdatedAt(new \DateTime());
        }

        return $object;
    }

    /**
     * @return Group|void
     * @throws Exception
     */
    public function updateImageStock()
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof Group)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->groupRepository->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        if ($request->getContentType() === self::CONTENT_TYPE_JSON) {
            $arrayDataJson = json_decode($request->getContent(), true);
            if (is_array($arrayDataJson)) {
                $imageStockIdReceived = $arrayDataJson[self::DATA_JSON_PARAM];
                $pathFilename = $this->imageStockService->imageStockIdExist($imageStockIdReceived);
                $object->setImagePath($pathFilename);
                $this->entityManager->flush();

                return $object;
            }
            throw new Exception();
        }
    }
}