<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\ImageStock;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class ImageStockDataProvider implements RestrictedDataProviderInterface, CollectionDataProviderInterface, ItemDataProviderInterface
{
    const RESOURCE_TYPE_USER = 'user';
    const RESOURCE_TYPE_GENERAL = 'general';
    const RESOURCE_TYPE_NAME = 'resource_type';

    /**
     * @param string $directoryPath
     * @param RequestStack $requestStack
     */
    public function __construct(private string $directoryPath, private RequestStack $requestStack)
    {
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array<mixed> $context
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass == ImageStock::class;
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @return array<ImageStock>
     * @throws Exception
     */
    public function getCollection(string $resourceClass, string $operationName = null): array
    {
        if ($this->requestStack->getCurrentRequest() === null) {
            throw new Exception('Request is null');
        }
        $resourceType = $this->requestStack->getCurrentRequest()->query->get(self::RESOURCE_TYPE_NAME);
        if ($resourceType !== self::RESOURCE_TYPE_USER) {
            $resourceType = self::RESOURCE_TYPE_GENERAL;
        }
        $path = $this->directoryPath . '/' . $resourceType;

        $ls = scandir($path);
        if ($ls === false) {
            throw new Exception();
        }
        //Easy way to get rid of the dots that scandir() picks up in Linux environments:
        $scannedDirectory = array_diff($ls, ['..', '.']);
        $imagesPath = [];
        foreach ($scannedDirectory as $filename) {
            $imgPath = '/' . $this->directoryPath . '/' . $resourceType . '/';
            $imagesPath[] = new ImageStock($filename, $imgPath, $resourceType);
        }

        return $imagesPath;
    }

    /**
     * @param string $resourceClass
     * @param array<mixed>|int|object|string $id
     * @param string|null $operationName
     * @param array<mixed> $context
     * @return ImageStock
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ImageStock
    {
        return new ImageStock('myImage.png', '/app/imagDir/', 'user');
    }
}