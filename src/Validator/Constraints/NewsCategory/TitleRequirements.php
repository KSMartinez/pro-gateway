<?php

namespace App\Validator\Constraints\NewsCategory;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[\Attribute]
class TitleRequirements extends Compound
{
    /**
     * @param Constraint[] $options
     * @return Constraint[]
     */
    protected function getConstraints(array $options) : array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(['min' => 4])
        ];
    }
}