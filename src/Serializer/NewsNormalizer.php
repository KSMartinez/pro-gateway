<?php

namespace App\Serializer;

use App\DataProvider\ImageStockDataProvider;
use App\Entity\News;
use ArrayObject;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

final class NewsNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'NEWS_NORMALIZER_ALREADY_CALLED';
    private const FIELD_NAME_IMAGE = 'imageFile';
    private const MEDIA_DIR_USER = '/media/default/user';
    private const MEDIA_DIR_GENERAL = '/media/default/general';

    public function __construct(
        private StorageInterface $storage,
        private ImageStockDataProvider $imageStockDataProvider
    )
    {
    }


    /**
     * @param mixed $object
     * @param string|null $format
     * @param array<mixed> $context
     * @return array<mixed>|string|int|float|bool|ArrayObject<int,News>|null
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;
        $object = $this->buildImageUrl($object);

        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * @param mixed $object
     * @return News
     * @throws Exception
     */
    private function buildImageUrl($object): News
    {
        /** @var  News $object */
        $imageStockIdReceived = $object->getImageStockId();
        if ($imageStockIdReceived) {
            $object->imageUrl = $this->imageStockIdExist($imageStockIdReceived);

            return $object;
        }

        $imgPath = $object->getImagePath();
        if ($imgPath !== null) {
            $pathParts =  pathinfo($imgPath);
            $dirname = strlen($pathParts['dirname']) !== 0;
            if($dirname && $pathParts['dirname'] !== self::MEDIA_DIR_USER && $pathParts['dirname'] !== self::MEDIA_DIR_GENERAL ) {
                $object->imageUrl = $this->storage->resolveUri($object, self::FIELD_NAME_IMAGE);

                return $object;
            }
        }

        $object->imageUrl = $imgPath;

        return $object;
    }

    /**
     * @param string $imageStockIdReceived
     * @return string|null
     * @throws Exception
     */
    public function imageStockIdExist (string $imageStockIdReceived): ?string
    {
        $arrayImagesStock = $this->imageStockDataProvider->getCollection('App\Entity\ImageStock');
        if(count($arrayImagesStock) > 0 ) {
            foreach ($arrayImagesStock as $imageStock) {
                if($imageStock->getId() === $imageStockIdReceived ) {
                    return $imageStock->getResourceUrl();
                }
            }
        }

        return null;
    }


    /**
     * @param mixed $data
     * @param string|null $format
     * @param array<mixed> $context
     * @return bool
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof News;
    }
}
