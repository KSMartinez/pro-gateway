<?php

namespace App\Service;

use App\Entity\CV;
use App\Repository\CVRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\File;

class CVService
{

    public function __construct(private EntityManagerInterface $entityManager, private CVRepository $cVRepository,)
    {
    }

    /**
     * @param CV $cv
     * @return CV
     * @throws Exception
     */
    public function updateCVFile(CV $cv): CV
    {

        if (!$cv->getId()) {
            throw new Exception('The CV should have an id for updating');
        }
        if (!$this->cVRepository->find($cv->getId())) {
            throw new Exception('The CV should have an id for updating');

        }
        $cv->setUpdatedAt(new DateTime('now'));
        $this->entityManager->persist($cv);
        $this->entityManager->flush();
        return $cv;

    }

    /**
     * @param File $file
     * @return CV
     */
    public function upload(File $file): CV
    {
        $cv = new CV();
        $cv->setFile($file);

        return $cv;

    }

}