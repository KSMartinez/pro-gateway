<?php

namespace App\Validator\Constraints\News;

use App\Validator\Constraints\Traits\DescriptionRequirementsTrait;
use Symfony\Component\Validator\Constraints\Compound;

#[\Attribute]
class DescriptionRequirements extends Compound
{
   use DescriptionRequirementsTrait;
}