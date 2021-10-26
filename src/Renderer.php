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
 * Renderer
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
            
        $html = '<div class="table">';
        
        foreach($table->getRows() as $row)
        {
            if (empty($row->getColumns())) {
                continue;
            }
                        
            if ($row->isHeading()) {
                $html .= '<div class="table-row th">';
            } else {
                $html .= '<div class="table-row">';
            }
            
            if ($row->prependedHtml()) {
                $html .= $row->prependedHtml();
            }
            
            foreach($row->getColumns() as $column)
            {
                $text = $row->isHtml($column->key())
                    ? $column->text()
                    : Str::esc($column->text());
                
                $size = $this->getColumnSize($table->getRows(), $column->key());
                
                $html .= '<div class="table-col grow-'.Str::esc((string)$size).'">'.$text.'</div>';
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
        
        foreach($rows as $row)
        {
            foreach($row->getColumns() as $col)
            {
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