<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\ImageStock;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class ImageStockDataProvider implements RestrictedDataProviderInterface, CollectionDataProviderInterface, ItemDataProviderInterface
{
    const RESOURCE_TYPE_GROUP = 'groups';
    const RESOURCE_TYPE_OFFER = 'offers';
    const RESOURCE_TYPE_USER = 'users';
    const RESOURCE_TYPE_GENERAL = 'general';
    const RESOURCE_TYPE_NAME = 'resource_type';
    const API_POST_CREATE_USER = '/api/users';
    const API_POST_CREATE_GROUP = '/api/groups';
    const API_POST_CREATE_OFFER = '/api/offers/create';

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
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ( $currentRequest === null) {
            throw new Exception('Request is null');
        }

        if($currentRequest->getMethod() === Request::METHOD_GET) {
            $resourceType = $currentRequest->query->get(self::RESOURCE_TYPE_NAME);
        }
        if($currentRequest->getMethod() === Request::METHOD_POST) {
            $resourceType = match ($currentRequest->getPathInfo()) {
                self::API_POST_CREATE_OFFER => self::RESOURCE_TYPE_OFFER,
                self::API_POST_CREATE_GROUP => self::RESOURCE_TYPE_GROUP,
                self::API_POST_CREATE_USER => self::RESOURCE_TYPE_USER,
                default => self::RESOURCE_TYPE_GENERAL
            };
        }

        $resourceType = $resourceType ?? self::RESOURCE_TYPE_GENERAL;

        $resourceNameDir = $this->getResourceNameDir($resourceType);
        $path = $this->directoryPath . '/' . $resourceNameDir;

        $ls = scandir($path);
        if ($ls === false) {
            throw new Exception();
        }
        //Easy way to get rid of the dots that scandir() picks up in Linux environments:
        $scannedDirectory = array_diff($ls, ['..', '.']);
        $imagesPath = [];
        foreach ($scannedDirectory as $filename) {
            $imgPath = '/' . $this->directoryPath . '/' . $resourceNameDir . '/';
            $imagesPath[] = new ImageStock($filename, $imgPath, $resourceNameDir);
        }

        return $imagesPath;
    }

    /**
     * @param string|null $resourceType
     * @return string
     */
    private function getResourceNameDir(?string $resourceType) : string
    {
        return match ($resourceType) {
            self::RESOURCE_TYPE_USER => self::RESOURCE_TYPE_USER,
            self::RESOURCE_TYPE_OFFER => self::RESOURCE_TYPE_OFFER,
            self::RESOURCE_TYPE_GROUP => self::RESOURCE_TYPE_GROUP,
            default => self::RESOURCE_TYPE_GENERAL,
        };
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