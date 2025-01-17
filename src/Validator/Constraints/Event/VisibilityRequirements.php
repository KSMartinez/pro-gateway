<?php

namespace App\Validator\Constraints\Event;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[\Attribute]
class VisibilityRequirements extends Compound
{
    /**
     * @param Constraint[] $options
     * @return Constraint[]
     */
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\ExpressionLanguageSyntax(
                allowedVariables: ['private', 'public'],
            )
        ];
    }
}