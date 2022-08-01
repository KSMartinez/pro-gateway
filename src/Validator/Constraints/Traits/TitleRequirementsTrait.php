<?php

namespace App\Validator\Constraints\Traits;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

trait TitleRequirementsTrait
{
    /**
     * @param Constraint[] $options
     * @return Constraint[]
     */
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(['min' => 4])
        ];
    }
}