# Kirby Font Selector

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-fontselector?color=ae81ff)

Kirby Plugin to select font family and font weight with two synced fields

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-fontselector/archive/master.zip) as folder `site/plugins/kirby3-fontselector` or
- `git submodule add https://github.com/bnomei/kirby3-fontselector.git site/plugins/kirby3-fontselector` or
- `composer require bnomei/kirby3-fontselector`

## Screenshot

![fontselector](https://raw.githubusercontent.com/bnomei/kirby3-fontselector/master/screenshot.gif)

## Fonts

Set the fonts you want to use with a config value. You could use a custom JSON file or return a php array.

**assets/fonts.json**
```json
{
  "fonts": [
    {
      "font": "Merriweather",
      "weight": [
        600,
        700
      ]
    },
    {
      "font": "Montserrat",
      "weight": [
        300,
        400,
        500
      ]
    }
  ]
}
```

```php
<?php

return [
    'bnomei.fontselector.fonts' => function() {
        return Json::read(kirby()->roots()->assets() . '/fonts.json');
        // return ['fonts' => [...]];
    },
    // other options
];
```

## Usage

Add the fields to your page blueprint.

**site/blueprints/default.yml**
```yaml
fields:
  headlinefont:
    type: fontfamily
    default: Merriweather
    required: true
    # reload: true # optional reload on save  
    
  headlineh1weight:
    type: fontweight
    watchField: headlinefont
    default: 700
    required: true
    
  headlineh2weight:
    type: fontweight
    watchField: headlinefont
    default: 600
    required: true

  copytextfont:
    type: fontfamily
    default: Montserrat
    required: true

  copytextweight:
    type: fontweight
    watchField: copytextfont
    default: 400
    required: true
```

> ⚠️ This plugin has by default a 1 minute cache.


## Settings

| bnomei.fontselector. | Default              | Description                        |            
|----------------------|----------------------|------------------------------------|
| fonts                | `function(){...}`    | callback to return the fonts array |
| expire               | `1`                  | cache will expire n-minutes        |

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-fontselector/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
