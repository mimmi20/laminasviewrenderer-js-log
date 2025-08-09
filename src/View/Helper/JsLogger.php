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

use Laminas\View\Exception\DomainException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Renderer\PhpRenderer;

use function assert;
use function get_debug_type;
use function is_string;

final class JsLogger extends AbstractHelper
{
    /** @throws void */
    public function __construct(private readonly string | null $route)
    {
        // nothing to do
    }

    /**
     * Outputs message depending on flag
     *
     * @throws void
     */
    public function __invoke(): self
    {
        return $this;
    }

    /**
     * @throws void
     *
     * @api
     */
    public function getRoute(): string | null
    {
        return $this->route;
    }

    /**
     * @throws RuntimeException
     * @throws DomainException
     * @throws InvalidArgumentException
     *
     * @api
     */
    public function render(): string
    {
        if ($this->route === null) {
            throw new RuntimeException('A Route is required');
        }

        $view = $this->getView();

        if (!$view instanceof PhpRenderer) {
            throw new RuntimeException('A PHP View Renderer is required');
        }

        $url = $view->url($this->route);

        assert(is_string($url), 'expected string, got ' . get_debug_type($url));

        return $view->render('logger-template', ['url' => $url]);
    }
}
