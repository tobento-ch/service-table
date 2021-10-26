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
 * TableInterface
 */
interface TableInterface
{
    /**
     * Returns a new instance with the specified columns.
     *
     * @param array<int, string> $columns
     * @return static
     */
    public function withColumns(array $columns): static;

    /**
     * Returns a new instance with the specified renderer.
     *
     * @param RendererInterface $renderer
     * @return static
     */    
    public function withRenderer(RendererInterface $renderer): static;
    
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
    ): Row;
    
    /**
     * Add rows.
     *
     * @param iterable $items
     * @param null|callable $callback
     * @return static $this
     */
    public function rows(iterable $items, null|callable $callback = null): static;    
    
    /**
     * Add a row.
     *
     * @param RowInterface $row
     * @return static $this
     */
    public function addRow(RowInterface $row): static;

    /**
     * Returns the row if exists, otherwise null.
     *
     * @param string|int $id
     * @return null|RowInterface
     */
    public function getRow(string|int $id): null|RowInterface;
    
    /**
     * Returns the rows.
     *
     * @return array<int|string, RowInterface>
     */
    public function getRows(): array;

    /**
     * Get the table name
     *
     * @return string
     */
    public function name(): string;  

    /**
     * Get the evaluated contents of the table.
     *
     * @return string
     */    
    public function render(): string;
}