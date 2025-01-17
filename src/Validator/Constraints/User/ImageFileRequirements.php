<?php

namespace App\Validator\Constraints\User;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;


#[\Attribute]
class ImageFileRequirements extends Compound
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
                allowSquare: true,
                allowLandscape: false,
                allowPortrait: false,
            )
        ];
    }
}