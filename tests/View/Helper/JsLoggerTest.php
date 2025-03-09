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

namespace Mimmi20\LaminasView\JsLogger\View\Helper;

use AssertionError;
use Laminas\View\Exception\DomainException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Renderer\RendererInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class JsLoggerTest extends TestCase
{
    /** @throws Exception */
    public function testInvoke(): void
    {
        $route = 'test-route';

        $object = new JsLogger($route);

        self::assertSame($object, $object());
    }

    /**
     * @throws RuntimeException
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithoutRoute(): void
    {
        $route = null;

        $object = new JsLogger($route);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A Route is required');

        $object->render();
    }

    /**
     * @throws RuntimeException
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithoutRenderer(): void
    {
        $route = 'test-route';

        $object = new JsLogger($route);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A PHP View Renderer is required');

        $object->render();
    }

    /**
     * @throws RuntimeException
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithWrongRenderer(): void
    {
        $route = 'test-route';

        $object = new JsLogger($route);

        $view = $this->createMock(RendererInterface::class);
        $view->expects(self::never())
            ->method('render');

        $object->setView($view);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A PHP View Renderer is required');

        $object->render();
    }

    /**
     * @throws Exception
     * @throws RuntimeException
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRender(): void
    {
        $route    = 'test-route';
        $url      = 'https://test-uri';
        $template = 'logger.phtml';
        $rendered = 'test';

        $object = new JsLogger($route);

        $view = $this->createMock(PhpRenderer::class);
        $view->expects(self::once())
            ->method('__call')
            ->with('url', [$route])
            ->willReturn($url);
        $view->expects(self::once())
            ->method('render')
            ->with($template, ['url' => $url])
            ->willReturn($rendered);

        $object->setView($view);

        self::assertSame($rendered, $object->render());
    }

    /**
     * @throws Exception
     * @throws RuntimeException
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithUrlError(): void
    {
        $route  = 'test-route';
        $object = new JsLogger($route);

        $view = $this->createMock(PhpRenderer::class);
        $view->expects(self::once())
            ->method('__call')
            ->with('url', [$route])
            ->willReturn(true);
        $view->expects(self::never())
            ->method('render');

        $object->setView($view);

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('expected string, got bool');

        $object->render();
    }
}
