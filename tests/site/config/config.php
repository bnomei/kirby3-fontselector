<?php

use Kirby\Data\Json;

return [
    'editor' => 'phpstorm',
    'debug' => true,

    'bnomei.fontselector.fonts' => function () {
        return Json::read(__DIR__.'/fonts.json');
    },
];
