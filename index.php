<?php

@include_once __DIR__.'/vendor/autoload.php';

use Bnomei\FontSelector\FontFamilyField;
use Bnomei\FontSelector\FontWeightField;
use Kirby\Cms\App as Kirby;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Obj;

load([
    FontFamilyField::class => 'classes/FontFamilyField.php',
    FontWeightField::class => 'classes/FontWeightField.php',
], __DIR__);

$configuredFonts = static function (): array {
    /** @var callable(): array{fonts: list<array{font: string, weight: list<int>}>} $fonts */
    $fonts = option('bnomei.fontselector.fonts');

    return $fonts()['fonts'];
};

Kirby::plugin('bnomei/fontselector', [
    'options' => [
        'fonts' => fn () =>
            // overwrite this config if you want to use a php array directly
            // for performance reasons or load file from somewhere else
            // return Json::read(__DIR__ . '/fonts.json');
            [
                'fonts' => [
                    [
                        'font' => 'Merriweather',
                        'weight' => [700, 600, 500, 400],
                    ],
                    [
                        'font' => 'Montserrat',
                        'weight' => [900, 800, 700, 600, 500, 400, 300, 200, 100],
                    ],
                ],
            ],
        'cache' => true,
        'expire' => 1, // 60*24*7, // in minutes
    ],
    'translations' => require __DIR__.'/i18n/index.php',
    'siteMethods' => [
        'fontFamilies' => function () use ($configuredFonts): array {
            $families = kirby()->cache('bnomei.fontselector')->get('families');

            if (! $families) {
                $families = array_map(
                    fn ($item) => A::get($item, 'font'),
                    $configuredFonts()
                );
                kirby()->cache('bnomei.fontselector')->set(
                    'families',
                    $families,
                    intval(option('bnomei.fontselector.expire'))
                );
            }

            return $families;
        },
        'fontWeight' => function (string $family) use ($configuredFonts): array {
            $weights = kirby()->cache('bnomei.fontselector')->get('family-'.$family);

            if (! $weights) {
                $fontWeights =
                    array_map(
                        fn ($item) => A::get($item, 'weight'),
                        array_filter(
                            $configuredFonts(),
                            fn ($item) => A::get($item, 'font') === $family
                        )
                    );
                if (count($fontWeights)) {
                    $fontWeights = array_shift($fontWeights);
                }
                $weights = [];
                foreach ($fontWeights as $weight) {
                    $weights[$weight] = t('fontselector.weight.'.$weight);
                }
                kirby()->cache('bnomei.fontselector')->set(
                    'family-'.$family,
                    $weights,
                    intval(option('bnomei.fontselector.expire'))
                );
            }

            return $weights;
        },
    ],
    'fields' => [
        'fontfamily' => FontFamilyField::class,
        'fontweight' => FontWeightField::class,
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'fontselector/families',
                'action' => function () {
                    if (get('reload') === '1') {
                        kirby()->cache('bnomei.fontselector')->flush();
                    }

                    $options = [];
                    foreach (site()->fontFamilies() as $value) {
                        $options[] = new Obj([
                            'value' => strval($value),
                            'text' => $value,
                        ]);
                    }

                    return [
                        'families' => $options,
                    ];
                },
            ],
            [
                'pattern' => 'fontselector/family/(:any)',
                'action' => function ($family) {
                    $family = urldecode((string) $family);
                    $options = [];
                    foreach (site()->fontWeight($family) as $key => $value) {
                        $options[] = new Obj([
                            'value' => strval($key),
                            'text' => $key.' - '.$value,
                        ]);
                    }

                    return [
                        'font' => $family,
                        'weight' => $options,
                    ];
                },
            ],
        ],
    ],
]);
