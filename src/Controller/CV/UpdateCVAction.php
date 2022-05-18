<?php

namespace App\Controller\CV;

use App\Entity\CV;
use App\Service\CVService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * This action lets you update the pdf file for the CV.
 * //todo enforce validation to restrict to pdf files and size.
 */
class UpdateCVAction extends AbstractController
{

    /**
     * @param CVService $cVService
     */
    public function __construct(private CVService $cVService)
    {
    }


    /**
     * @param CV $data
     * @return CV
     * @throws Exception
     */
    public function __invoke(CV $data): CV
    {
        return $this->cVService->updateCVFile($data);
    }

}