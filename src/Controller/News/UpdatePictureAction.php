<?php


namespace App\Controller\News;


use App\Entity\UploadPictureCompatibleInterface;
use App\Service\UpdatePictureService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * Class UpdatePictureAction
 * @package App\Controller\News
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
        return $this->updatePicture->process('App\Entity\News');
    }
}     