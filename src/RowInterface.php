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
 * RowInterface
 */
interface RowInterface
{
    /**
     * Returns a new instance with the specified active columns.
     *
     * @param array<int, string> $activeColumns
     * @return static
     */
    public function withActiveColumns(array $activeColumns): static;
    
    /**
     * Set the id.
     *
     * @param string|int $id
     * @return static $this
     */    
    public function id(string|int $id): static;
    
    /**
     * Returns the id.
     *
     * @return null|string|int
     */    
    public function getId(): null|string|int;

    /**
     * Set the if it is a heading row.
     *
     * @param string|int $id
     * @return static $this
     */    
    public function heading(bool $isHeading = true): static;
    
    /**
     * Returns true if it is a heading, otherwise false.
     *
     * @return bool
     */    
    public function isHeading(): bool;
    
    /**
     * Returns true if the column is html, otherwise false.
     *
     * @param string $column
     * @return bool
     */    
    public function isHtml(string $column): bool;
    
    /**
     * Returns the html to prepend or null if none.
     *
     * @return null|string
     */    
    public function prependedHtml(): null|string;
    
    /**
     * Returns the html to append or null if none.
     *
     * @return null|string
     */    
    public function appendedHtml(): null|string;
    
    /**
     * Get the row columns.
     *
     * @return array<string, ColumnInterface>
     */    
    public function getColumns(): array;
}