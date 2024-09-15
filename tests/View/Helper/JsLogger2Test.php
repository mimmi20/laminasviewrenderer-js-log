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

use Laminas\View\Exception\RuntimeException;
use Laminas\View\Renderer\PhpRenderer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function rename;

final class JsLogger2Test extends TestCase
{
    private JsLogger $object;

    /** @throws Exception */
    protected function setUp(): void
    {
        $route = 'test-route';

        $this->object = new JsLogger($route);

        $view = $this->createMock(PhpRenderer::class);
        $view->expects(self::never())
            ->method('__call');

        $this->object->setView($view);

        rename(__DIR__ . '/../../../template/logger.js', __DIR__ . '/../../../template/logger2.js');
    }

    /** @throws void */
    protected function tearDown(): void
    {
        rename(__DIR__ . '/../../../template/logger2.js', __DIR__ . '/../../../template/logger.js');
    }

    /**
     * @throws Exception
     * @throws RuntimeException
     */
    public function testRender(): void
    {
        self::assertSame('', $this->object->render());
    }
}
