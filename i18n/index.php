<?php

use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;

$root = __DIR__;

$translations = [];
foreach (Dir::files($root) as $file) {
    $locale = basename($file, '.json');
    if ($content = F::read($root.'/'.$file)) {
        $translations[$locale] = json_decode($content, true);
    }
}

return $translations;
