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

use Stringable;

/**
 * Column
 */
class Column implements ColumnInterface
{
    /**
     * Create a new Column
     *
     * @param string $key
     * @param string|Stringable $text
     */
    public function __construct(
        protected string $key,
        protected string|Stringable $text,
    ) {
        if ($text instanceof Stringable) {
            $this->text = $text->__toString();
        }
    }
    
    /**
     * Get the key.
     *
     * @return string
     */    
    public function key(): string
    {
        return $this->key;
    }
    
    /**
     * Get the text.
     *
     * @return string
     */    
    public function text(): string
    {
        if ($this->text instanceof Stringable) {
            return $this->text->__toString();
        }
        
        return $this->text;
    }
}