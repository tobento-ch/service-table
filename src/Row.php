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
 * Row
 */
class Row implements RowInterface
{
    /**
     * @var array<string, ColumnInterface>
     */    
    protected array $columns = [];
    
    /**
     * @var array<int, string>
     */    
    protected array $htmlColumns = [];
    
    /**
     * @var null|string
     */    
    protected null|string $prependHtml = null;
    
    /**
     * @var null|string
     */    
    protected null|string $appendHtml = null;    
    
    /**
     * Create a new Row
     *
     * @param array|object $columns The row columns
     * @param null|callable $callback A callback to create items
     * @param null|string|int $id
     * @param bool $isHeading
     * @param array<int, string> $activeColumns
     */
    public function __construct(
        array|object $columns,
        null|callable $callback = null,
        protected null|string|int $id = null,
        protected bool $isHeading = false,
        protected array $activeColumns = [],
    ) {
        $this->columns($columns, $callback);
    }
    
    /**
     * Returns a new instance with the specified active columns.
     *
     * @param array<int, string> $activeColumns
     * @return static
     */
    public function withActiveColumns(array $activeColumns): static
    {
        $new = clone $this;
        $new->columns = [];
        $new->activeColumns = $activeColumns;
        $new->columns($this->columns);
        return $new;
    }
    
    /**
     * Set the id.
     *
     * @param string|int $id
     * @return static $this
     */    
    public function id(string|int $id): static
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Returns the id.
     *
     * @return null|string|int
     */    
    public function getId(): null|string|int
    {
        return $this->id;
    }    
    
    /**
     * Set the if it is a heading row.
     *
     * @param string|int $id
     * @return static $this
     */    
    public function heading(bool $isHeading = true): static
    {
        $this->isHeading = $isHeading;
        return $this;
    }
        
    /**
     * Returns true if it is a heading, otherwise false.
     *
     * @return bool
     */    
    public function isHeading(): bool
    {
        return $this->isHeading;
    }
    
    /**
     * Add a column to be html, so no escaping is done, you need to do it by yourself.
     *
     * @param string $column
     * @return static $this
     */
    public function html(string ...$column): static
    {
        foreach($column as $name) {
            $this->htmlColumns[] = $name;
        }

        return $this;
    }
    
    /**
     * Returns true if the column is html, otherwise false.
     *
     * @param string $column
     * @return bool
     */    
    public function isHtml(string $column): bool
    {
        return in_array($column, $this->htmlColumns);
    }

    /**
     * Set the html to prepend.
     *
     * @param string $html
     * @return static $this
     */    
    public function prependHtml(string $html): static
    {
        $this->prependHtml = $html;
        return $this;
    }
    
    /**
     * Returns the html to prepend or null if none.
     *
     * @return null|string
     */    
    public function prependedHtml(): null|string
    {
        return $this->prependHtml;
    }    
    
    /**
     * Set the html to append.
     *
     * @param string $html
     * @return static $this
     */    
    public function appendHtml(string $html): static
    {
        $this->appendHtml = $html;
        return $this;
    }
    
    /**
     * Returns the html to append or null if none.
     *
     * @return null|string
     */    
    public function appendedHtml(): null|string
    {
        return $this->appendHtml;
    }    

    /**
     * Execute a callback over each item.
     *
     * @param iterable $items
     * @param callable $callback
     * @return static $this
     */    
    public function each(iterable $items, callable $callback): static
    {
        foreach($items as $key => $item)
        {
            call_user_func_array($callback, [$this, $item, $key]);
        }
        
        return $this;
    }
    
    /**
     * Applies the callback if the given "value" evaluates to true.
     *
     * @param bool $value
     * @param callable $callback
     * @return static $this
     */    
    public function when(bool $value, callable $callback): static
    {
        if ($value) {
            call_user_func_array($callback, [$this]);
        }
        
        return $this;
    }    
    
    /**
     * Add columns.
     *
     * @param array<mixed>|object $columns
     * @param null|callable $callback
     * @return static $this
     */    
    public function columns(array|object $columns, null|callable $callback = null): static
    {
        if (!is_null($callback))
        {
            call_user_func_array($callback, [$this, $columns]);
            return $this;
        }
        
        if (is_array($columns))
        {
            foreach($columns as $key => $text)
            {
                if ($text instanceof ColumnInterface) {
                    $this->column($this->ensureString($key), $text->text());
                } else {
                    $this->column($this->ensureString($key), $this->ensureString($text));
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Add a column.
     *
     * @param string $key
     * @param string|Stringable $text
     * @return static $this
     */    
    public function column(string $key, string|Stringable $text): static
    {
        if (
            empty($this->activeColumns)
            || in_array($key, $this->activeColumns)
        ) {
            $this->columns[$key] = new Column($key, $text);
        }
        
        return $this;
    }
    
    /**
     * Get the row columns.
     *
     * @return array<string, ColumnInterface>
     */    
    public function getColumns(): array
    {
        return $this->columns;
    }
    
    /**
     * Ensure string.
     *
     * @param mixed $value
     * @return string
     */    
    protected function ensureString(mixed $value): string
    {        
        if (is_scalar($value)) {
            return (string) $value;
        }
        
        if ($value instanceof Stringable) {
            return $value->__toString();
        }        
        
        return '';
    }    
}