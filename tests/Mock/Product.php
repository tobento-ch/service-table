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

namespace Tobento\Service\Table\Test\Mock;

/**
 * Product
 */
class Product
{
    public function __construct(
        protected string $sku,
        protected string $name,
        protected string $desc = '',
    ) {}

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }
    
    public function desc(): string
    {
        return $this->desc;
    }         
}