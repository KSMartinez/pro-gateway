<?php

namespace App\Serializer;

use App\Entity\News;
use ArrayObject;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

final class NewsNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'NEWS_NORMALIZER_ALREADY_CALLED';

    public function __construct(private StorageInterface $storage)
    {
    }


    /**
     * @param $object
     * @param string|null $format
     * @param array<mixed> $context
     * @return array<mixed>|string|int|float|bool|ArrayObject|null
     * @throws ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;
        /** @var News $object */
        $object->imageUrl = $this->storage->resolveUri($object, 'imageFile');

        return $this->normalizer->normalize($object, $format, $context);
    }


    /**
     * @param $data
     * @param string|null $format
     * @param array<mixed> $context
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof News;
    }
}
