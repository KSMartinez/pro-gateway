<?php

namespace App\Serializer;

use App\Entity\CV;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 *
 */
class CVNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{

    use NormalizerAwareTrait;

    /**
     *
     */
    private const ALREADY_CALLED = 'MEDIA_OBJECT_NORMALIZER_ALREADY_CALLED';

    /**
     * @param StorageInterface $storage
     */
    public function __construct(private StorageInterface $storage)
    {
    }


    /**
     * @param mixed $data
     * @param string|null $format
     * @param array<mixed> $context
     * @return bool
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof CV;
    }

    /**
     * @param object $object
     * @param string|null $format
     * @param array<mixed> $context
     * @return float|array<mixed>|CV|bool|int|string|null
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): float|array|CV|bool|int|string|null
    {
        $context[self::ALREADY_CALLED] = true;
        /** @var CV $object */
        $object->contentUrl = $this->storage->resolveUri($object, 'file');

        return $this->normalizer->normalize($object, $format, $context);
    }
}