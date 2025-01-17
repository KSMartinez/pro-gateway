<?php

namespace App\Serializer;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Api\ResourceClassResolverInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Security\ResourceAccessCheckerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Entity\ImageStockCompatibleInterface;
use App\Service\ImageStockService;
use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class ItemNormalizer extends AbstractItemNormalizer
{
    private const ALREADY_CALLED = 'NORMALIZER_ALREADY_CALLED';
    private const FIELD_NAME_IMAGE = 'imageFile';
    private const MEDIA_DIR_USER = '/media/default/users';
    private const MEDIA_DIR_OFFER = '/media/default/offers';
    private const MEDIA_DIR_GROUPS = '/media/default/groups';
    private const MEDIA_DIR_GENERAL = '/media/default/general';
    private const DIRNAME = 'dirname';

    /**
     * @param StorageInterface $storage
     * @param EntityManagerInterface $entityManager
     * @param ImageStockService $imageStockService
     * @param PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory
     * @param PropertyMetadataFactoryInterface $propertyMetadataFactory
     * @param IriConverterInterface $iriConverter
     * @param ResourceClassResolverInterface $resourceClassResolver
     * @param PropertyAccessorInterface|null $propertyAccessor
     * @param NameConverterInterface|null $nameConverter
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param ItemDataProviderInterface|null $itemDataProvider
     * @param bool $allowPlainIdentifiers
     * @param array<int, mixed> $defaultContext
     * @param array<int, mixed> $dataTransformers
     * @param ResourceMetadataFactoryInterface|null $resourceMetadataFactory
     * @param ResourceAccessCheckerInterface|null $resourceAccessChecker
     */
    public function __construct(
        private StorageInterface               $storage,
        private EntityManagerInterface         $entityManager,
        private ImageStockService              $imageStockService,
        PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory,
        PropertyMetadataFactoryInterface       $propertyMetadataFactory,
        IriConverterInterface                  $iriConverter,
        ResourceClassResolverInterface         $resourceClassResolver,
        PropertyAccessorInterface              $propertyAccessor = null,
        NameConverterInterface                 $nameConverter = null,
        ClassMetadataFactoryInterface          $classMetadataFactory = null,
        ItemDataProviderInterface              $itemDataProvider = null,
        bool                                   $allowPlainIdentifiers = false,
        array                                  $defaultContext = [],
        iterable                               $dataTransformers = [],
        ResourceMetadataFactoryInterface       $resourceMetadataFactory = null,
        ResourceAccessCheckerInterface         $resourceAccessChecker = null
    )
    {
        parent::__construct(
            $propertyNameCollectionFactory,
            $propertyMetadataFactory,
            $iriConverter,
            $resourceClassResolver,
            $propertyAccessor,
            $nameConverter,
            $classMetadataFactory,
            $itemDataProvider,
            $allowPlainIdentifiers,
            $defaultContext,
            $dataTransformers,
            $resourceMetadataFactory,
            $resourceAccessChecker
        );
    }

    /**
     * Validates the type of the value. Allows using integers as floats for JSON formats.
     *
     * @param mixed $value
     * @throws InvalidArgumentException
     */
    public function validateType(string $attribute, Type $type, $value, string $format = null): void
    {
        $builtinType = $type->getBuiltinType();
        $value = $this->deserializeType($builtinType, $value);
        parent::validateType($attribute, $type, $value, $format);
    }

    /**
     * @param string $builtinType
     * @param string|null|bool $value
     * @return mixed
     */
    private function deserializeType(string $builtinType, &$value): mixed
    {
        if ($builtinType === Type::BUILTIN_TYPE_INT) {
            $value = (int)$value;
        }

        return $value;
    }

    /**
     * @param mixed $object
     * @param string $format
     * @param array<mixed> $context
     * @return array<int, mixed>|ArrayObject<int, mixed>|bool|float|int|string|null
     * @throws ExceptionInterface
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!array_key_exists(self::ALREADY_CALLED, $context)) {
            if ($object instanceof ImageStockCompatibleInterface) {
                $object = $this->buildImageUrl($object);
            }
        }
        $context[self::ALREADY_CALLED] = true;

        return parent::normalize($object, $format, $context);
    }

    /**
     * @param mixed $object
     * @return ImageStockCompatibleInterface
     * @throws Exception
     */
    private function buildImageUrl($object): ImageStockCompatibleInterface
    {
        /** @var ImageStockCompatibleInterface $object */
        $imageStockIdReceived = $object->getImageStockId();
        if ($imageStockIdReceived) {
            $object->imageUrl = $this->imageStockService->imageStockIdExist($imageStockIdReceived);
            $object->imagePath = $object->imageUrl;
        }

        $imgPath = $object->getImagePath();
        if ($imgPath !== null) {
            $pathParts = pathinfo($imgPath);
            $dirname = strlen($pathParts[self::DIRNAME]) !== 0;
            if (
                $dirname &&
                $pathParts[self::DIRNAME] !== self::MEDIA_DIR_USER &&
                $pathParts[self::DIRNAME] !== self::MEDIA_DIR_GROUPS &&
                $pathParts[self::DIRNAME] !== self::MEDIA_DIR_OFFER &&
                $pathParts[self::DIRNAME] !== self::MEDIA_DIR_GENERAL
            ) {
                $object->imageUrl = $this->storage->resolveUri($object, self::FIELD_NAME_IMAGE);
                return $object;
            }
        }

        $object->imageUrl = $imgPath;
        $this->entityManager->flush();
        return $object;
    }
}