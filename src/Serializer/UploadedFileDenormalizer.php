<?php

namespace App\Serializer;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UploadedFileDenormalizer implements DenormalizerInterface
{

    /**
     * @param UploadedFile $data
     * @param string $type
     * @param string|null $format
     * @param array<mixed> $context
     * @return UploadedFile
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): UploadedFile
    {
        return $data;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $data instanceof UploadedFile;
    }
}