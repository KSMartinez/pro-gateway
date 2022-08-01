<?php

namespace App\Validator\Constraints\NewsCategory;

use App\Validator\Constraints\Traits\TitleRequirementsTrait;
use Symfony\Component\Validator\Constraints\Compound;

#[\Attribute]
class TitleRequirements extends Compound
{
   use TitleRequirementsTrait;
}