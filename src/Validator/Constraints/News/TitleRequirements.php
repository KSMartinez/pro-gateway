<?php

namespace App\Validator\Constraints\News;

use App\Validator\Constraints\Traits\TitleRequirementsTrait;
use Symfony\Component\Validator\Constraints\Compound;

#[\Attribute]
class TitleRequirements extends Compound
{
    use TitleRequirementsTrait;
}