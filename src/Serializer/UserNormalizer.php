<?php

namespace App\Serializer;

use App\Entity\User;
use ArrayObject;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

final class UserNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';

    public function __construct(private StorageInterface $storage)
    {
    }


    /**
     * @param mixed $object
     * @param string|null $format
     * @param array<mixed> $context
     * @return array<mixed>|string|int|float|bool|ArrayObject<int, User>|null
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|float|bool|int|string|ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;
        /** @var User $object */
        $object->imageUrl = $this->storage->resolveUri($object, 'imageFile');

        return $this->normalizer->normalize($object, $format, $context);
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

        return $data instanceof User;
    }
}
