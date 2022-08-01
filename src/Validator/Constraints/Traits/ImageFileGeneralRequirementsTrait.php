<?php

namespace App\Validator\Constraints\Traits;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

trait ImageFileGeneralRequirementsTrait
{
    /**
     * @param Constraint[] $options
     * @return Constraint[]
     */
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\File(
                maxSize: '1024k',
                mimeTypes: ['image/png', 'image/jpeg', 'image/webp'],
            ),
            new Assert\Image(
                allowSquare: false,
                allowLandscape: true,
                allowPortrait: false
            )
        ];
    }
}