# ControllerActionExtractorBundle

[![Build Status](https://travis-ci.com/GollumSF/controller-action-extractor-bundle.svg?branch=master)](https://travis-ci.com/GollumSF/controller-action-extractor-bundle)
[![Coverage](https://coveralls.io/repos/github/GollumSF/controller-action-extractor-bundle/badge.svg?branch=master)](https://coveralls.io/github/GollumSF/controller-action-extractor-bundle)
[![License](https://poser.pugx.org/gollumsf/controller-action-extractor-bundle/license)](https://packagist.org/packages/gollumsf/controller-action-extractor-bundle)
[![Latest Stable Version](https://poser.pugx.org/gollumsf/controller-action-extractor-bundle/v/stable)](https://packagist.org/packages/gollumsf/controller-action-extractor-bundle)
[![Latest Unstable Version](https://poser.pugx.org/gollumsf/controller-action-extractor-bundle/v/unstable)](https://packagist.org/packages/gollumsf/controller-action-extractor-bundle)
[![Discord](https://img.shields.io/discord/671741944149573687?color=purple&label=discord)](https://discord.gg/xMBc5SQ)

Extract controller class and action method from Request or Route

## Installation:

```shell
composer require gollumsf/controller-action-extractor-bundle
```

### config/bundles.php
```php
return [
    // [ ... ]
    GollumSF\RestBundle\ControllerActionExtractorBundle::class => ['all' => true],
];
```

## Usage

```php

use GollumSF\ControllerActionExtractorBundle\Extractor\ControllerActionExtractorInterface;

public function (ControllerActionExtractorInterface $extractor) { // Inject service
    
    // Get $route from router
    $controllerAction = $extractor->extractFromRoute($route);

    // Get $request
    $controllerAction = $extractor->extractFromRequest($request);

    // Get $request
    $controllerAction = $extractor->extractFromString('Controller::action');
    
    $controllerClass = $controllerAction->getControllerClass(); // Return controller class
    $actionMethod    = $controllerAction->getActionMethod();    // Return action method
    
}
```