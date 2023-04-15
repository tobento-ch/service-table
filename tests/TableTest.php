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
use Tobento\Service\Table\Table;
use Tobento\Service\Table\TableInterface;
use Tobento\Service\Table\RowInterface;
use Tobento\Service\Table\Row;
use Tobento\Service\Table\Renderer;
use Tobento\Service\Table\RendererInterface;
use Tobento\Service\Table\Test\Mock\Product;

/**
 * TableTest tests
 */
class TableTest extends TestCase
{    
    public function testThatTableImplementsTableInterface()
    {
        $this->assertInstanceOf(
            TableInterface::class,
            new Table('products')
        );     
    }
    
    public function testTableConstructorParameters()
    {
        $table = new Table(
            name: 'products',
            columns: ['sku', 'title'],
            renderer: new Renderer(),
        );
        
        $this->assertInstanceOf(
            TableInterface::class,
            $table
        );        
    }

    public function testNameMethod()
    {
        $table = new Table('products');
        
        $this->assertSame('products', $table->name());
    }
    
    public function testWithColumnsMethodReturnsNewInstance()
    {
        $table = new Table('products');

        $newTable = $table->withColumns(['sku', 'title']);

        $this->assertFalse($table === $newTable);
    }
    
    public function testWithColumnsMethodRowsShouldBeWithColumnsOnly()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
        ]);

        $newTable = $table->withColumns(['sku', 'title']);

        $this->assertSame(
            ['sku', 'title'],
            array_keys($newTable->getRow(0)->getColumns())
        );
    }
    
    public function testWithColumnsMethodKeepsId()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
        ])->id('header');

        $newTable = $table->withColumns(['sku', 'title']);
        
        $this->assertInstanceOf(
            RowInterface::class,
            $newTable->getRow('header')
        );
    }
    
    public function testWithColumnsMethodKeepsHeading()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
        ])->heading();

        $newTable = $table->withColumns(['sku', 'title']);
        
        $this->assertTrue($newTable->getRow(0)->isHeading());
    }
    
    public function testWithColumnsMethodKeepsHtml()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
        ])->html('title');

        $newTable = $table->withColumns(['sku', 'title']);
        
        $this->assertTrue($newTable->getRow(0)->isHtml('title'));
    }
    
    public function testWithColumnsMethodKeepsPrependAndAppendHtml()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
        ])->prependHtml('<form>')->appendHtml('</form>');

        $newTable = $table->withColumns(['sku', 'title']);
        
        $this->assertSame(
            '<form>',
            $newTable->getRow(0)->prependedHtml()
        );
        
        $this->assertSame(
            '</form>',
            $newTable->getRow(0)->appendedHtml()
        );        
    }
    
    public function testWithColumnsMethodKeepsRowsIndex()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
        ]);
        
        $table->row([
            'sku' => 'shorts',
            'title' => 'Shorts',
            'description' => 'Nice shorts.',
        ]);

        $newTable = $table->withColumns(['sku', 'title']);

        $newTable->row([
            'sku' => 'cap',
            'title' => 'Cap',
            'description' => 'A nice cap.',
        ]);
        
        $rows = $newTable->getRows();
            
        $this->assertSame(
            [
                'shirt',
                'shorts',
                'cap',
            ],
            [
                $newTable->getRow(0)->getColumns()['sku']->text(),
                $newTable->getRow(1)->getColumns()['sku']->text(),
                $newTable->getRow(2)->getColumns()['sku']->text(),
            ]
        );       
    }
    
    public function testWithRendererReturnsInstance()
    {
        $table = new Table('products');

        $newTable = $table->withRenderer(new Renderer());

        $this->assertFalse($table === $newTable);       
    }
    
    public function testRowsMethodFromArrayItems()
    {
        $table = new Table('products');
        
        $table->rows([
            [
                'sku' => 'shirt',
                'title' => 'Shirt',
                'description' => 'A nice shirt in blue color.',
            ],
            [
                'sku' => 'cap',
                'title' => 'Cap',
                'description' => 'A nice cap.',
            ],    
        ]);
        
        $this->assertSame(
            [
                'shirt',
                'cap',
            ],
            [
                $table->getRow(0)->getColumns()['sku']->text(),
                $table->getRow(1)->getColumns()['sku']->text(),
            ]
        );
    }
    
    public function testRowsMethodWithCallback()
    {
        $table = new Table('products');
        
        $table->rows([
            new Product('shirt', 'Shirt'),
            new Product('cap', 'Cap'),  
        ], function(Row $row, Product $product): void {
            $row->column(key: 'sku', text: $product->sku());
            $row->column(key: 'name', text: $product->name());
        });
        
        $this->assertSame(
            [
                'shirt',
                'cap',
            ],
            [
                $table->getRow(0)->getColumns()['sku']->text(),
                $table->getRow(1)->getColumns()['sku']->text(),
            ]
        );
    }
    
    public function testRowsMethodWithCallbackFromArrayItems()
    {
        $table = new Table('products');
        
        $table->rows([
            [
                'sku' => 'shirt',
                'title' => 'Shirt',
                'description' => 'A nice shirt in blue color.',
                'price' => 19.99,
            ],
            [
                'sku' => 'cap',
                'title' => 'Cap',
                'description' => 'A nice cap.',
                'price' => 11.99,
            ],
        ], function(Row $row, array $item): void {
            $row->column(key: 'title', text: $item['title']);
            $row->column(key: 'price', text: (string)$item['price']);
        });
        
        $this->assertSame(
            [
                'Shirt',
                'Cap',
            ],
            [
                $table->getRow(0)->getColumns()['title']->text(),
                $table->getRow(1)->getColumns()['title']->text(),
            ]
        );
    }
    
    public function testRowMethod()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
        ]);
        
        $table->row([
            'sku' => 'cap',
            'title' => 'Cap',
        ]);
        
        $this->assertSame(
            [
                'Shirt',
                'Cap',
            ],
            [
                $table->getRow(0)->getColumns()['title']->text(),
                $table->getRow(1)->getColumns()['title']->text(),
            ]
        );
    }
    
    public function testRowMethodWithCallback()
    {
        $table = new Table('products');
        
        $table->row(
            new Product('shirt', 'Shirt'),
            function(Row $row, Product $product): void {
                $row->column(key: 'sku', text: $product->sku())
                    ->column(key: 'title', text: $product->name());
            }
        );
        
        $this->assertSame(
            [
                'shirt',
                'Shirt',
            ],
            [
                $table->getRow(0)->getColumns()['sku']->text(),
                $table->getRow(0)->getColumns()['title']->text(),
            ]
        );
    }
    
    public function testRowMethodWithHeading()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'Sku',
            'title' => 'Title',
        ])->heading();
        
        $this->assertTrue($table->getRow(0)->isHeading());
    }
    
    public function testRowMethodWithId()
    {
        $table = new Table('products');
        
        $row = $table->row([
            'sku' => 'Sku',
            'title' => 'Title',
        ])->id('header');
        
        $this->assertSame(
            $row,
            $table->getRow('header')
        );
    }
    
    public function testRowMethodEach()
    {
        $item = [
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
            'price' => 19.99,
        ];

        $table = new Table('products');

        $table->row()
              ->each($item, function(Row $row, $value, $key): void {
                    $row->column($key, (string)$value);      
              });
        
        $this->assertSame(
            ['sku', 'title', 'description', 'price'],
            array_keys($table->getRow(0)->getColumns())
        );
    }
    
    public function testRowMethodWhenIsTrue()
    {
        $table = new Table('products');

        $table->row()
              ->when(true, function(Row $row): void {
                  $row->column('actions', 'Actions');
              });
        
        $this->assertSame(
            ['actions'],
            array_keys($table->getRow(0)->getColumns())
        );
    }
    
    public function testRowMethodWhenIsFalse()
    {
        $table = new Table('products');

        $table->row()
              ->when(false, function(Row $row): void {
                  $row->column('actions', 'Actions');
              });
        
        $this->assertSame(
            [],
            array_keys($table->getRow(0)->getColumns())
        );
    }
    
    public function testRowMethodHtml()
    {
        $table = new Table('products');

        $row = $table->row([
            'sku' => 'Sku',
            'desc' => '<p>Description</p>',
        ])->html('desc');
        
        $this->assertTrue($row->isHtml('desc'));
        $this->assertFalse($row->isHtml('sku'));
    }
    
    public function testRowMethodHtmlMulitple()
    {
        $table = new Table('products');

        $row = $table->row([
            'id' => 'Id',
            'sku' => 'Sku',
            'desc' => '<p>Description</p>',
        ])->html('sku', 'desc');
        
        $this->assertFalse($row->isHtml('id'));
        $this->assertTrue($row->isHtml('sku'));
        $this->assertTrue($row->isHtml('desc'));
    }
    
    public function testRowMethodPrependHtml()
    {
        $table = new Table('products');

        $row = $table->row([
            'sku' => 'Sku',
            'desc' => 'Description',
        ])->prependHtml('<form>');
        
        $this->assertSame(
            '<form>',
            $row->prependedHtml()
        );
    } 
    
    public function testRowMethodAppendHtml()
    {
        $table = new Table('products');

        $row = $table->row([
            'sku' => 'Sku',
            'desc' => 'Description',
        ])->appendHtml('</form>');
        
        $this->assertSame(
            '</form>',
            $row->appendedHtml()
        );
    }
    
    public function testAddRowMethod()
    {
        $table = new Table('products');

        $row = new Row([]);

        $table->addRow($row);
        
        $this->assertSame(
            $row,
            $table->getRow(0)
        );
    }
    
    public function testGetRowsMethod()
    {
        $table = new Table('products');
        
        $row1 = $table->row([
            'sku' => 'shirt',
            'title' => 'Shirt',
            'description' => 'A nice shirt in blue color.',
        ]);
        
        $row2 = $table->row([
            'sku' => 'shorts',
            'title' => 'Shorts',
            'description' => 'Nice shorts.',
        ]);
            
        $this->assertSame(
            [
                $row1,
                $row2,
            ],
            $table->getRows()
        );       
    }

    public function testRenderMethod()
    {
        $table = new Table('products');
        
        $table->row([
            'sku' => 'shirt',
        ]);
            
        $this->assertSame(
            '<div class="table"><div class="table-row"><div class="table-col grow-1">shirt</div></div></div>',
            $table->render()
        );       
    }
    
    public function testRenderMethodOnEmptyRowsReturnsEmptyString()
    {
        $table = new Table('products');
            
        $this->assertSame(
            '',
            $table->render()
        );       
    }    
}