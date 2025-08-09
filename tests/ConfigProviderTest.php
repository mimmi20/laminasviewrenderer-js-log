<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-js-log package.
 *
 * Copyright (c) 2023-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\JsLogger;

use Mimmi20\LaminasView\JsLogger\View\Helper\JsLogger;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function realpath;

final class ConfigProviderTest extends TestCase
{
    /** @throws Exception */
    public function testGetViewHelperConfig(): void
    {
        $object           = new ConfigProvider();
        $viewHelperConfig = $object->getViewHelperConfig();

        self::assertIsArray($viewHelperConfig);
        self::assertCount(2, $viewHelperConfig);

        self::assertArrayHasKey('factories', $viewHelperConfig);
        $factories = $viewHelperConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(JsLogger::class, $factories);

        self::assertArrayHasKey('aliases', $viewHelperConfig);
        $aliases = $viewHelperConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('jsLogger', $aliases);
    }

    /** @throws Exception */
    public function testGetTemplateConfig(): void
    {
        $object          = new ConfigProvider();
        $templatesConfig = $object->getTemplates();

        self::assertIsArray($templatesConfig);

        self::assertArrayHasKey('map', $templatesConfig);
        $maps = $templatesConfig['map'];
        self::assertIsArray($maps);
        self::assertArrayHasKey('logger-template', $maps);
        self::assertIsString($maps['logger-template']);
        self::assertSame(realpath(__DIR__ . '/../template/logger.phtml'), $maps['logger-template']);
    }

    /** @throws Exception */
    public function testInvoke(): void
    {
        $object = new ConfigProvider();
        $config = $object();

        self::assertIsArray($config);
        self::assertCount(2, $config);
        self::assertArrayHasKey('view_helpers', $config);

        $viewHelperConfig = $config['view_helpers'];

        self::assertIsArray($viewHelperConfig);

        self::assertArrayHasKey('factories', $viewHelperConfig);
        $factories = $viewHelperConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(JsLogger::class, $factories);

        self::assertArrayHasKey('aliases', $viewHelperConfig);
        $aliases = $viewHelperConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('jsLogger', $aliases);

        self::assertArrayHasKey('templates', $config);

        $templatesConfig = $config['templates'];

        self::assertIsArray($templatesConfig);

        self::assertArrayHasKey('map', $templatesConfig);
        $maps = $templatesConfig['map'];
        self::assertIsArray($maps);
        self::assertArrayHasKey('logger-template', $maps);
        self::assertIsString($maps['logger-template']);
        self::assertSame(realpath(__DIR__ . '/../template/logger.phtml'), $maps['logger-template']);
    }
}
