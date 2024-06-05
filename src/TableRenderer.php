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
 * Renders a table with the table tag.
 */
class TableRenderer implements RendererInterface
{
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
        $html = '<table'.$attributes.'>';
        
        foreach($table->getRows() as $row) {
            if (empty($row->getColumns())) {
                continue;
            }
            
            $attributes = new Attributes($row->getAttributes());
            
            $html .= '<tr'.$attributes.'>';
            
            // would produce invalid html!
            /*if ($row->prependedHtml()) {
                $html .= $row->prependedHtml();
            }*/
            
            foreach($row->getColumns() as $column) {
                $text = $row->isHtml($column->key())
                    ? $column->text()
                    : Str::esc($column->text());
                
                if (empty($column->attributes())) {
                    $attributes = '';
                } else {
                    $attributes = (new Attributes($column->attributes()))->render();
                }
                
                if ($row->isHeading()) {
                    $html .= '<th'.$attributes.'>'.$text.'</th>';
                } else {
                    $html .= '<td'.$attributes.'>'.$text.'</td>';
                }
            }
            
            // would produce invalid html!
            /*if ($row->appendedHtml()) {
                $html .= $row->appendedHtml();
            }*/
            
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }
}