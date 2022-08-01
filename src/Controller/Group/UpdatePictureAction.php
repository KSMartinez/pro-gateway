<?php


namespace App\Controller\Group;


use App\Entity\Group;
use App\Service\GroupService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;


#[AsController]
class UpdatePictureAction extends AbstractController
{

    /**
     * @param GroupService $groupService
     */
    public function __construct(private GroupService $groupService)
    {
    }

    /**
     * @return Group
     * @throws Exception
     */
    public function __invoke(): Group
    {
        return $this->groupService->updatePicture();
    }
}