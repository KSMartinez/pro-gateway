<?php

namespace App\Serializer;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use Symfony\Component\PropertyInfo\Type;

class ItemNormalizer extends AbstractItemNormalizer
{
    /**
     * Validates the type of the value. Allows using integers as floats for JSON formats.
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    protected function validateType(string $attribute, Type $type, $value, string $format = null): void
    {
        $builtinType = $type->getBuiltinType();
        $value = $this->deserializeType($builtinType, $value);

        if (Type::BUILTIN_TYPE_FLOAT === $builtinType && null !== $format && false !== strpos($format, 'json')) {
            $isValid = \is_float($value) || \is_int($value);
        } else {
            /*  PHPStan Error: Parameter #1 $callback of function call_user_func expects callable(): mixed, non-empty-string given. */
            /* @phpstan-ignore-next-line */
            $isValid = \call_user_func('is_' . $builtinType, $value);
        }
        if (!$isValid) {
            throw new InvalidArgumentException(
                sprintf('The type of the "%s" attribute must be "%s", "%s" given.', $attribute, $builtinType, \gettype($value))
            );
        }
    }

    /**
     * @param string $builtinType
     * @param string|null|bool $value
     * @return mixed
     */
    private function deserializeType(string $builtinType, &$value): mixed
    {
        if ($builtinType === Type::BUILTIN_TYPE_INT) {
            $value = (int) $value;
        }

        return $value;
    }
}