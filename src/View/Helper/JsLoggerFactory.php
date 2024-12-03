<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-js-log package.
 *
 * Copyright (c) 2023-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\JsLogger\View\Helper;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Override;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;
use function is_array;
use function is_string;

/**
 * Generates the BootstrapFlashMessenger view helper object
 */
final class JsLoggerFactory implements FactoryInterface
{
    /**
     * Create Service Factory
     *
     * @param string            $requestedName
     * @param array<mixed>|null $options
     * @phpstan-param array<mixed>|null $options
     *
     * @throws ContainerExceptionInterface
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    #[Override]
    public function __invoke(ContainerInterface $container, $requestedName, array | null $options = null): JsLogger
    {
        $config = $container->get('config');
        assert(is_array($config));

        $config = $config['js-logger'] ?? [];
        assert(is_array($config));

        $route = $config['route'] ?? null;

        if (!is_string($route)) {
            $route = null;
        }

        return new JsLogger($route);
    }
}
