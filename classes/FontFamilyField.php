<?php

namespace Bnomei\FontSelector;

use Kirby\Form\Field\StringField;
use Kirby\Reflection\Attributes\Prop;

class FontFamilyField extends StringField
{
    #[Prop]
    protected bool $reload = false;
}
