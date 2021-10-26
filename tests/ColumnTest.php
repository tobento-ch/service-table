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

namespace Tobento\Service\Table\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Table\Column;
use Tobento\Service\Table\ColumnInterface;

/**
 * ColumnTest tests
 */
class ColumnTest extends TestCase
{    
    public function testThatTableImplementsColumnInterface()
    {
        $this->assertInstanceOf(
            ColumnInterface::class,
            new Column('sku', 'SKU')
        );     
    }

    public function testKeyMethod()
    {        
        $this->assertSame(
            'sku',
            (new Column('sku', 'SKU'))->key()
        );
    }
    
    public function testTextMethod()
    {
        $this->assertSame(
            'SKU',
            (new Column('sku', 'SKU'))->text()
        );
    }    
}