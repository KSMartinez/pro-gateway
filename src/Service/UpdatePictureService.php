<?php

namespace App\Service;

use App\Entity\UploadPictureCompatibleInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;

class UpdatePictureService
{
    /**
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param class-string<object> $fqn
     * @return UploadPictureCompatibleInterface
     * @throws Exception
     */
    public function process($fqn): UploadPictureCompatibleInterface
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof UploadPictureCompatibleInterface)) {
            throw new \RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->entityManager->getRepository($fqn)->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        $file = $request->files->get('imageFile');

        if ($file instanceof File) {
            $object->setFile($file);
            $object->setUpdatedAt(new \DateTime());
        }

        return $object;
    }
}