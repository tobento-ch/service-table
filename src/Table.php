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
 * Table
 */
class Table implements TableInterface, Stringable
{    
    /**
     * @var array<mixed, RowInterface>
     */    
    protected array $rows = [];
    
    /**
     * @var int The rows index.
     */    
    protected int $rowsIndex = 0;
    
    /**
     * @var null|RowInterface
     */    
    protected null|RowInterface $lastRow = null;
    
    /**
     * Create a new Table
     *
     * @param string $name A table name
     * @param array<int, string> $columns
     * @param null|RendererInterface $renderer
     */
    public function __construct(
        protected string $name,
        protected array $columns = [],
        protected null|RendererInterface $renderer = null,
    ) {
        $this->renderer = $renderer ?: new Renderer();
    }

    /**
     * Returns a new instance with the specified columns.
     *
     * @param array<int, string> $columns
     * @return static
     */
    public function withColumns(array $columns): static
    { 
        $new = clone $this;
        $new->columns = $columns;
        $new->rows = [];
        $new->rowsIndex = $this->rowsIndex+1;
        $new->lastRow = null;
        
        //print_r($this->getRows());
        
        foreach($this->getRows() as $row) {
            $new->addRow($row->withActiveColumns($columns));
        }
        
        return $new;
    }
    
    /**
     * Returns a new instance with the specified renderer.
     *
     * @param RendererInterface $renderer
     * @return static
     */    
    public function withRenderer(RendererInterface $renderer): static
    {
        $new = clone $this;
        $new->renderer = $renderer;
        return $new;
    }    
    
    /**
     * Add a row and returns the row added.
     *
     * @param array|object $columns
     * @param null|callable $callback
     * @param null|string|int $id
     * @return Row
     */
    public function row(
        array|object $columns = [],
        null|callable $callback = null,
        null|string|int $id = null
    ): Row {
        
        $this->addLastRow();
        
        return $this->lastRow = new Row(
            $columns,
            $callback,
            $id,
            false,
            $this->columns,
        );
    }
    
    /**
     * Add rows.
     *
     * @param iterable $items
     * @param null|callable $callback
     * @return static $this
     */
    public function rows(iterable $items, null|callable $callback = null): static
    {        
        foreach($items as $columns)
        {
            $this->row($columns, $callback, $this->getNextRowsIndex());
        }
        
        return $this;
    }    
    
    /**
     * Add a row.
     *
     * @param RowInterface $row
     * @return static $this
     */
    public function addRow(RowInterface $row): static
    {
        if (is_null($row->getId())) {
            $row->id($this->getNextRowsIndex());
        }

        $this->rows[$row->getId()] = $row;
        
        return $this;
    }

    /**
     * Returns the row if exists, otherwise null.
     *
     * @param string|int $id
     * @return null|RowInterface
     */
    public function getRow(string|int $id): null|RowInterface
    {
        $this->addLastRow();
        
        return $this->rows[$id] ?? null;
    }
    
    /**
     * Returns the rows.
     *
     * @return array<int|string, RowInterface>
     */
    public function getRows(): array
    {
        $this->addLastRow();
        
        return $this->rows;
    }

    /**
     * Get the table name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }    

    /**
     * Get the evaluated contents of the table.
     *
     * @return string
     */    
    public function render(): string
    {
        return $this->renderer ? $this->renderer->render($this) : '';
    }
    
    /**
     * Returns the string representation of the menu.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
    
    /**
     * Adds the last row
     *
     * @return void
     */
    protected function addLastRow(): void
    {
        if ($this->lastRow) {
            $this->addRow($this->lastRow);
            $this->lastRow = null;
        }        
    }
    
    /**
     * Returns the next rows index.
     *
     * @return int
     */
    protected function getNextRowsIndex(): int
    {
        return $this->rowsIndex++;
    }    
}