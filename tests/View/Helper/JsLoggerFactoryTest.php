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
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class JsLoggerFactoryTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testInvokeWithoutRoute(): void
    {
        $config = [];

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($config);
        $container->expects(self::never())
            ->method('has');

        $result = (new JsLoggerFactory())($container, '');

        self::assertInstanceOf(JsLogger::class, $result);
        self::assertNull($result->getRoute());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testInvokeWithRoute(): void
    {
        $route  = 'test-route';
        $config = ['js-logger' => ['route' => $route]];

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($config);
        $container->expects(self::never())
            ->method('has');

        $result = (new JsLoggerFactory())($container, '');

        self::assertInstanceOf(JsLogger::class, $result);
        self::assertSame($route, $result->getRoute());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testInvokeWithoutConfig(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(null);
        $container->expects(self::never())
            ->method('has');

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('assert(is_array($config))');

        (new JsLoggerFactory())($container, '');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testInvokeWithWongRouteType(): void
    {
        $config = ['js-logger' => ['route' => 42]];

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($config);
        $container->expects(self::never())
            ->method('has');

        $result = (new JsLoggerFactory())($container, '');

        self::assertInstanceOf(JsLogger::class, $result);
        self::assertNull($result->getRoute());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testInvokeWithWongRouteType2(): void
    {
        $config = ['js-logger' => 42];

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($config);
        $container->expects(self::never())
            ->method('has');

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('assert(is_array($config))');

        (new JsLoggerFactory())($container, '');
    }
}
