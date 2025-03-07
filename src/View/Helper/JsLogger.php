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

use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Renderer\PhpRenderer;

use function assert;
use function file_get_contents;
use function get_debug_type;
use function is_string;
use function sprintf;

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

        $js = @file_get_contents(__DIR__ . '/../../../template/logger.js');

        if (!$js) {
            return '';
        }

        $url = $view->url($this->route);

        assert(is_string($url), 'expected string, got ' . get_debug_type($url));

        return sprintf("<script>\n%s\n</script>\n", sprintf($js, $url));
    }
}
