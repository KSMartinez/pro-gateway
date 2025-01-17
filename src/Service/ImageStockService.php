<?php

namespace App\Service;

use App\DataProvider\ImageStockDataProvider;
use App\Entity\ImageStockCompatibleInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;

class ImageStockService
{
    const CONTENT_TYPE_JSON = 'json';
    const DATA_JSON_PARAM = 'id';

    /**
     * @param ImageStockDataProvider $imageStockDataProvider
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private ImageStockDataProvider $imageStockDataProvider,
        private RequestStack           $requestStack,
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param class-string<object> $fqn
     * @return ImageStockCompatibleInterface|void
     * @throws Exception
     */
    public function updatePicture($fqn)
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $request = $this->requestStack->getCurrentRequest();
        $object = $request->attributes->get('data');

        if (!($object instanceof ImageStockCompatibleInterface)) {
            throw new RuntimeException('The object does not match');
        }
        if (!$object->getId()) {
            throw new Exception('The object should have an id for updating');
        }
        if (!$this->entityManager->getRepository($fqn)->find($object->getId())) {
            throw new Exception('The object should have an id for updating');
        }

        if ($request->getContentType() === self::CONTENT_TYPE_JSON) {
            $arrayDataJson = json_decode($request->getContent(), true);
            if (is_array($arrayDataJson)) {
                $imageStockIdReceived = $arrayDataJson[self::DATA_JSON_PARAM];
                $pathFilename = $this->imageStockIdExist($imageStockIdReceived);
                $object->setImagePath($pathFilename);
                $this->entityManager->flush();

                return $object;
            }
            throw new Exception();
        }
    }

    /**
     * @param string $imageStockIdReceived
     * @return string|null
     * @throws Exception|Exception
     */
    public function imageStockIdExist(string $imageStockIdReceived): ?string
    {
        $arrayImagesStock = $this->imageStockDataProvider->getCollection('App\Entity\ImageStock');
        if (count($arrayImagesStock) > 0) {
            foreach ($arrayImagesStock as $imageStock) {
                if ($imageStock->getId() === $imageStockIdReceived) {
                    return $imageStock->getResourceUrl();
                }
            }
        }

        return null;
    }
}