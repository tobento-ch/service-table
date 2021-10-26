<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Table;

/**
 * RendererInterface
 */
interface RendererInterface
{
    /**
     * Render the table.
     *
     * @param TableInterface $table
     * @return string
     */
    public function render(TableInterface $table): string;
}