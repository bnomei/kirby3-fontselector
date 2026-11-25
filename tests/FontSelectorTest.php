<?php

use Bnomei\FontSelector\FontFamilyField;
use Bnomei\FontSelector\FontWeightField;
use Kirby\Form\Field;

it('registers Kirby fields and exports the props used by the Panel', function () {
    expect(Field::resolve('fontfamily'))->toBe(FontFamilyField::class)
        ->and(Field::resolve('fontweight'))->toBe(FontWeightField::class);

    $family = new FontFamilyField(model: site(), name: 'headlinefont', value: 'Montserrat', reload: true);
    $weight = new FontWeightField(model: site(), name: 'weight', value: '900', watchField: 'headlinefont');

    expect($family->props()['reload'])->toBeTrue()
        ->and($family->value())->toBe('Montserrat')
        ->and($weight->props()['watchField'])->toBe('headlinefont')
        ->and($weight->value())->toBe('900');
});
