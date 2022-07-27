<?php

namespace App\Controller\Event;

use App\Entity\Event;
use App\Service\EventService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdateImageStockAction extends AbstractController
{
    public function __construct(private EventService $eventService)
    {
    }

    /**
     * @return Event|void
     *
     * @throws Exception
     */
    public function __invoke()
    {
        return $this->eventService->updateImageStock();
    }
}