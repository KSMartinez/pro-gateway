<?php

namespace App\Encoder;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use function is_array;

/**
 *
 */
class MultipartDecoder implements DecoderInterface
{

    /**
     *
     */
    public const FORMAT = 'multipart';

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * @param string $data
     * @param string $format
     * @param mixed[] $context
     * @return array<mixed>|null
     */
    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return null;
        }

        //@phpstan-ignore-next-line
        return array_map(static function (string $element) {
                // Multipart form values will be encoded in JSON.
                $decoded = json_decode($element, true);
                return is_array($decoded) ? $decoded : $element;
            } , $request->request->all()) + $request->files->all();
    }

    /**
     * @param string $format
     * @return bool
     */
    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}