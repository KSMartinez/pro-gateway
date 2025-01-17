<?php


namespace App\Controller\Event;


use App\Entity\UploadPictureCompatibleInterface;
use App\Service\UpdatePictureService;
use Exception;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UpdatePictureAction
 * @package App\Controller\Event
 */
#[AsController]
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
        return $this->updatePicture->process('App\Entity\Event');
    }
}     