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

use Tobento\Service\Tag\Attributes;
use Tobento\Service\Tag\Str;
use Stringable;

/**
 * Renders a table with DIV tags.
 */
class Renderer implements RendererInterface
{
    /**
     * @var null|array<string, int>
     */
    protected null|array $sizes = null;
    
    /**
     * Render the table.
     *
     * @param TableInterface $table
     * @return string
     */
    public function render(TableInterface $table): string
    {
        if (empty($table->getRows())) {
            return '';
        }
        
        $attributes = new Attributes($table->getAttributes());
        $attributes->add('class', 'table');
        $attributes->add('role', 'table');
        $html = '<div'.$attributes.'>';
        
        foreach($table->getRows() as $row) {
            if (empty($row->getColumns())) {
                continue;
            }
            
            $attributes = new Attributes($row->getAttributes());
            $attributes->add('class', 'table-row');
            $attributes->set('role', 'row');
            
            if ($row->isHeading()) {
                $attributes->add('class', 'th');
            }
            
            $html .= '<div'.$attributes.'>';
            
            if ($row->prependedHtml()) {
                $html .= $row->prependedHtml();
            }
            
            foreach($row->getColumns() as $column) {
                $text = $row->isHtml($column->key())
                    ? $column->text()
                    : Str::esc($column->text());
                
                $size = $this->getColumnSize($table->getRows(), $column->key());
                $role = 'cell';
                
                if ($row->isHeading()) {
                    $role = 'columnheader';
                }
                
                if (empty($column->attributes())) {
                    $html .= '<div class="table-col grow-'.Str::esc((string)$size).'" role="'.$role.'">'.$text.'</div>';
                } else {
                    $attributes = new Attributes($column->attributes());
                    $attributes->add('class', 'table-col grow-'.Str::esc((string)$size));
                    $attributes->set('role', $role);
                    $html .= '<div'.$attributes.'>'.$text.'</div>';
                }
            }
            
            if ($row->appendedHtml()) {
                $html .= $row->appendedHtml();
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        $this->sizes = null;
        
        return $html;
    }
    
    /**
     * Returns the size for the specified column.
     *
     * @param array<int|string, RowInterface> $rows
     * @param string $column
     * @return int
     */
    protected function getColumnSize(array $rows, string $column): int
    {
        if (!is_null($this->sizes)) {
            return $this->sizes[$column] ?? 1;
        }
        
        $sizes = [];
        
        foreach($rows as $row) {
            foreach($row->getColumns() as $col) {
                $sizes[$col->key()][] = strlen(strip_tags($col->text()));
            }
        }
        
        foreach($sizes as $key => $size) {
            
            $maxSize = max($size);

            if ($maxSize >= 80) {
                $maxSize = 4;
            } elseif ($maxSize >= 40) {
                $maxSize = 3;
            } elseif ($maxSize >= 10) {
                $maxSize = 2;
            } else {
                $maxSize = 1;
            }
            
            $this->sizes[$key] = $maxSize;
        }
        
        return $this->sizes[$column] ?? 1;      
    }    
}