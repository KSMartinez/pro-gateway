<?php

namespace App\Controller\Offer;

use App\Entity\UploadPictureCompatibleInterface;
use App\Service\UpdatePictureService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdatePictureAction extends AbstractController
{
    /**
     * @param UpdatePictureService $updatePicture
     */
    public function __construct(private UpdatePictureService $updatePicture)
    {
    }

    /**
     * @return UploadPictureCompatibleInterface
     * @throws Exception
     */
    public function __invoke(): UploadPictureCompatibleInterface
    {
        return $this->updatePicture->process('App\Entity\Offer');
    }
}