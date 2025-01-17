<?php

namespace App\Validator\Constraints\Offer;

use App\Validator\Constraints\Traits\ImageFileGeneralRequirementsTrait;
use Symfony\Component\Validator\Constraints\Compound;


#[\Attribute]
class ImageFileRequirements extends Compound
{
    use ImageFileGeneralRequirementsTrait;
}