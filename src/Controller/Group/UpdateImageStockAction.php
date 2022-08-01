<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Service\GroupService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateImageStockAction extends AbstractController
{

    public function __construct(private GroupService $groupService)
    {
    }

    /**
     * @return Group|void
     *
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->groupService->updateImageStock();
    }
}
