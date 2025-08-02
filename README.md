# laminasviewrenderer-js-log

[![Latest Stable Version](https://poser.pugx.org/mimmi20/laminasviewrenderer-js-log/v/stable?format=flat-square)](https://packagist.org/packages/mimmi20/laminasviewrenderer-js-log)
[![Latest Unstable Version](https://poser.pugx.org/mimmi20/laminasviewrenderer-js-log/v/unstable?format=flat-square)](https://packagist.org/packages/mimmi20/laminasviewrenderer-js-log)
[![License](https://poser.pugx.org/mimmi20/laminasviewrenderer-js-log/license?format=flat-square)](https://packagist.org/packages/mimmi20/laminasviewrenderer-js-log)

## Code Status

[![codecov](https://codecov.io/gh/mimmi20/laminasviewrenderer-js-log/branch/master/graph/badge.svg)](https://codecov.io/gh/mimmi20/laminasviewrenderer-js-log)
[![Average time to resolve an issue](https://isitmaintained.com/badge/resolution/mimmi20/laminasviewrenderer-js-log.svg)](https://isitmaintained.com/project/mimmi20/laminasviewrenderer-js-log "Average time to resolve an issue")
[![Percentage of issues still open](https://isitmaintained.com/badge/open/mimmi20/laminasviewrenderer-js-log.svg)](https://isitmaintained.com/project/mimmi20/laminasviewrenderer-js-log "Percentage of issues still open")
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fmimmi20%2Flaminasviewrenderer-js-log%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/mimmi20/laminasviewrenderer-js-log/master)

## Introduction

This component provides a Viewhelper to generate a logger for javascript errors

## Requirements

This library requires PHP 8.1+.

## Installation

Run

```shell
composer require mimmi20/laminasviewrenderer-js-log
```

## Config

This viewhelper needs a route where the logs are sent to. The route has to defined in the routing config.

```php
<?php
return [
    // ...
    'js-logger' => [
        'route' => '',
    ],
    // ...
];
```

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).
