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
use Tobento\Service\Table\TableRenderer;
use Tobento\Service\Table\RendererInterface;
use Tobento\Service\Table\Table;
use Tobento\Service\Table\Test\Mock\Product;

/**
 * TableRendererTest
 */
class TableRendererTest extends TestCase
{    
    public function testThatImplementsRendererInterface()
    {
        $this->assertInstanceOf(
            RendererInterface::class,
            new TableRenderer()
        );     
    }
    
    public function testRenderMethod()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');
        
        $this->assertSame(
            '',
            $renderer->render($table)
        );
    }
    
    public function testRendersRowColumns()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');

        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
        ]);
        
        $this->assertSame(
            '<table><tr><td>shirt</td><td>Shirt</td></tr></table>',
            $renderer->render($table)
        );
    }
    
    public function testRendersTableAttributes()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');
        $table->attributes(['data-foo' => 'value', 'class' => 'bar']);

        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
        ]);
        
        $this->assertSame(
            '<table data-foo="value" class="bar"><tr><td>shirt</td><td>Shirt</td></tr></table>',
            $renderer->render($table)
        );
    }    
    
    public function testRendersRowColumnsAttributes()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');

        $table->row()
              ->column(key: 'sku', text: 'Sku')
              ->column(key: 'title', text: 'Title', attributes: ['data-foo' => 'Foo', 'class' => 'bar']);
        
        $this->assertSame(
            '<table><tr><td>Sku</td><td data-foo="Foo" class="bar">Title</td></tr></table>',
            $renderer->render($table)
        );
    }
    
    public function testRendersRows()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');

        $table->row([
            'sku' => 'shirt',
        ]);
        
        $table->row([
            'sku' => 'cap',
        ]);
        
        $this->assertSame(
            '<table><tr><td>shirt</td></tr><tr><td>cap</td></tr></table>',
            $renderer->render($table)
        );
    }
    
    public function testRendersHeading()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');

        $table->row([
            'sku' => 'shirt',
        ])->heading();
        
        $this->assertSame(
            '<table><tr><th>shirt</th></tr></table>',
            $renderer->render($table)
        );
    }
    
    public function testRendersPrependedAndAppendedHtmlIngoresAsItProducesInvalidHtml()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');

        $table->row([
            'sku' => 'shirt',
        ])->prependHtml('<form>')->appendHtml('</form>');   
        
        $this->assertSame(
            '<table><tr><td>shirt</td></tr></table>',
            $renderer->render($table)
        );
    }
    
    public function testRendersWithoutEscapingHtmlIfIsHtml()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');

        $table->row([
            'intro' => '<p>intro</p>',
            'desc' => '<p>desc</p>',
        ])->html('desc');
        
        $this->assertSame(
            '<table><tr><td>&lt;p&gt;intro&lt;/p&gt;</td><td><p>desc</p></td></tr></table>',
            $renderer->render($table)
        );        
    }
    
    public function testRendersRowsAttributes()
    {
        $renderer = new TableRenderer();
        $table = new Table('products');

        $table->row([
            'sku' => 'shirt',
        ])->attributes(['data-id' => 'foo']);
        
        $table->row([
            'sku' => 'cap',
        ]);
        
        $this->assertSame(
            '<table><tr data-id="foo"><td>shirt</td></tr><tr><td>cap</td></tr></table>',
            $renderer->render($table)
        );
    }
}