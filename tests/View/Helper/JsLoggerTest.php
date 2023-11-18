<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-js-log package.
 *
 * Copyright (c) 2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\JsLogger\View\Helper;

use AssertionError;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Renderer\RendererInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function sprintf;

final class JsLoggerTest extends TestCase
{
    /** @throws Exception */
    public function testInvoke(): void
    {
        $route = 'test-route';

        $object = new JsLogger($route);

        self::assertSame($object, $object());
    }

    /** @throws RuntimeException */
    public function testRenderWithoutRoute(): void
    {
        $route = null;

        $object = new JsLogger($route);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A Route is required');

        $object->render();
    }

    /** @throws RuntimeException */
    public function testRenderWithoutRenderer(): void
    {
        $route = 'test-route';

        $object = new JsLogger($route);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A PHP View Renderer is required');

        $object->render();
    }

    /** @throws RuntimeException */
    public function testRenderWithWrongRenderer(): void
    {
        $route = 'test-route';

        $object = new JsLogger($route);

        $view = $this->createMock(RendererInterface::class);

        $object->setView($view);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A PHP View Renderer is required');

        $object->render();
    }

    /**
     * @throws Exception
     * @throws RuntimeException
     */
    public function testRender(): void
    {
        $route = 'test-route';
        $url   = 'https://test-uri';

        $js        = (string) file_get_contents(__DIR__ . '/../../../template/logger.js');
        $jsWithUrl = sprintf($js, $url);

        $object = new JsLogger($route);

        $view    = $this->createMock(PhpRenderer::class);
        $matcher = self::exactly(2);
        $view->expects($matcher)
            ->method('__call')
            ->willReturnCallback(
                static function (string $method, array $argv) use ($matcher, $url, $route, $jsWithUrl): mixed {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame('url', $method, (string) $invocation),
                        default => self::assertSame('escapeJs', $method, (string) $invocation),
                    };

                    match ($invocation) {
                        1 => self::assertSame([$route], $argv, (string) $invocation),
                        default => self::assertSame([$jsWithUrl], $argv, (string) $invocation),
                    };

                    return match ($invocation) {
                        1 => $url,
                        default => $jsWithUrl,
                    };
                },
            );

        $object->setView($view);

        self::assertSame(sprintf("<script>\n%s\n</script>\n", $jsWithUrl), $object->render());
    }

    /**
     * @throws Exception
     * @throws RuntimeException
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

        $object->setView($view);

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('expected string, got bool');

        $object->render();
    }

    /**
     * @throws Exception
     * @throws RuntimeException
     */
    public function testRenderWithEscapeError(): void
    {
        $route = 'test-route';
        $url   = 'https://test-uri';

        $js        = (string) file_get_contents(__DIR__ . '/../../../template/logger.js');
        $jsWithUrl = sprintf($js, $url);

        $object = new JsLogger($route);

        $view    = $this->createMock(PhpRenderer::class);
        $matcher = self::exactly(2);
        $view->expects($matcher)
            ->method('__call')
            ->willReturnCallback(
                static function (string $method, array $argv) use ($matcher, $url, $route, $jsWithUrl): mixed {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame('url', $method, (string) $invocation),
                        default => self::assertSame('escapeJs', $method, (string) $invocation),
                    };

                    match ($invocation) {
                        1 => self::assertSame([$route], $argv, (string) $invocation),
                        default => self::assertSame([$jsWithUrl], $argv, (string) $invocation),
                    };

                    return match ($invocation) {
                        1 => $url,
                        default => true,
                    };
                },
            );

        $object->setView($view);

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('expected string, got bool');

        $object->render();
    }
}
