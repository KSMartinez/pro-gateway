<?php

namespace App\Serializer;

use App\Entity\News;
use App\Entity\Offer;
use App\Service\ImageStockService;
use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

final class OfferNormalizer //implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'OFFER_NORMALIZER_ALREADY_CALLED';

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array<mixed> $context
     * @return array<mixed>|string|int|float|bool|ArrayObject<int,Offer>|null
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function normalize(
        $object, ?string $format = null, array $context = []
    ): array|string|int|float|bool|ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;
      //  $object = $this->buildImageUrl($object);

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
        //dump($data instanceof News);
        return $data instanceof News;
    }
}
