<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use Kirby\Toolkit\Obj;

Kirby::plugin('bnomei/fontselector', [
    'options' => [
        'fonts' => function () {
            // overwrite this config if you want to use a php array directly
            // for performance reasons or load file from somewhere else
            // return Json::read(__DIR__ . '/fonts.json');
            return [
                'fonts' => [
                    [
                        'font' => 'Merriweather',
                        'weight' => [600, 500, 400,],
                    ],
                    [
                        'font' => 'Montserrat',
                        'weight' => [900, 800, 700, 600, 500, 400, 300, 200, 100,],
                    ],
                ],
            ];
        },
        'cache' => true,
        'expire' => 1, // 60*24*7, // in minutes
    ],
    'translations' => require __DIR__ . '/i18n/index.php',
    'siteMethods' => [
        'fontFamilies' => function (): array {
            $families = kirby()->cache('bnomei.fontselector')->get('families');

            if (!$families) {
                $families = array_map(
                    fn ($item) => A::get($item, 'font'),
                    option('bnomei.fontselector.fonts')()['fonts']
                );
                kirby()->cache('bnomei.fontselector')->set(
                    'families',
                    $families,
                    intval(option('bnomei.fontselector.expire'))
                );
            }

            return $families;
        },
        'fontWeight' => function (string $family): ?array {
            $weights = kirby()->cache('bnomei.fontselector')->get('family-' . $family);

            if (!$weights) {
                $fontWeights =
                    array_map(
                        fn ($item) => A::get($item, 'weight'),
                        array_filter(
                            option('bnomei.fontselector.fonts')()['fonts'],
                            fn ($item) => A::get($item, 'font') === $family
                        )
                    )
                ;
                if (count($fontWeights)) {
                    $fontWeights = array_shift($fontWeights);
                }
                $weights = [];
                foreach ($fontWeights as $weight) {
                    $weights[$weight] = t('fontselector.weight.' . $weight);
                }
                kirby()->cache('bnomei.fontselector')->set(
                    'family-' . $family,
                    $weights,
                    intval(option('bnomei.fontselector.expire'))
                );
            }

            return $weights;
        },
    ],
    'fields' => [
        'fontfamily' => [
            'props' => [
                'options' => function () {
                    return array_map(
                        fn ($item) => new Obj(['value' => $item, 'text' => $item]),
                        site()->fontFamilies()
                    );
                },
                'default' => fn ($default) => $default,
            ],
        ],
        'fontweight' => [
            'props' => [
                'default' => fn ($default) => $default,
                'watchField' => fn ($fieldname) => $fieldname,
            ],
        ],
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'fontselector/family/(:any)',
                'action' => function ($family) {
                    $family = urldecode($family);
                    $options = [];
                    foreach (site()->fontWeight($family) as $key => $value) {
                        $options[] = new Obj([
                            'value' => $key,
                            'text' => $key . ' - ' . $value
                        ]);
                    }
                    return [
                        'font' => $family,
                        'weight' => $options
                    ];
                },
            ],
        ],
    ],
]);
