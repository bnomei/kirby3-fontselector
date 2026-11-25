<?php

namespace Bnomei\FontSelector;

use Kirby\Form\Field\StringField;
use Kirby\Reflection\Attributes\Prop;

class FontWeightField extends StringField
{
    #[Prop]
    protected string $watchField = '';
}
