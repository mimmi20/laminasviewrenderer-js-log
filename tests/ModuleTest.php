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

final class ModuleTest extends TestCase
{
    /** @throws Exception */
    public function testGetConfig(): void
    {
        $object = new Module();
        $config = $object->getConfig();

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

        self::assertArrayHasKey('view_manager', $config);

        $viewManagerConfig = $config['view_manager'];

        self::assertIsArray($viewManagerConfig);

        self::assertArrayHasKey('template_map', $viewManagerConfig);
        $maps = $viewManagerConfig['template_map'];
        self::assertIsArray($maps);
        self::assertArrayHasKey('logger-template', $maps);
    }
}
