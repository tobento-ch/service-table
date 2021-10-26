<?php
declare(strict_types=1);

error_reporting( -1 );
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';

use Tobento\Service\Table\Table;

$table = new Table('products');

$table->row([
    'id' => 'Id',
    'active' => 'Active',
    'sku' => 'Sku',
    'title' => 'Title',
    'description' => 'Description',
    'type' => 'Type',
    'price' => 'Price',
    'attr1' => 'Attr 1',
    'attr2' => 'Attr 2',
    'attr3' => 'Attr 3',
    'attr4' => 'Attr 4',
    'attr5' => 'Attr 5',
])->heading();

$table->row([
    'id' => '<input type="text" id="id" name="id">',
    'active' => '<input type="text" id="active" name="active">',
    'sku' => '<input type="text" id="sku" name="sku">',
    'title' => '<input type="text" id="title" name="title">',
    'description' => '<input type="text" id="description" name="description">',
    'type' => '<input type="text" id="type" name="type">',
    'price' => 'from: <input type="number" id="price_from" name="price_from"> to: <input type="number" id="price_to" name="price_to">',
    'attr1' => '',
    'attr2' => '',
    'attr3' => '',
    'attr4' => '',
    'attr5' => '',    
])->html('id')
  ->html('active')
  ->html('sku')
  ->html('title')
  ->html('description')
  ->html('type')
  ->html('price')
  ->prependHtml('<form>')
  ->appendHtml('</form>');

$table->row([
    'id' => 1,
    'active' => true,
    'sku' => 'Sku',
    'title' => 'Title',
    'description' => 'Description',
    'type' => 'Standard',
    'price' => 2.55,
    'attr1' => 'Attr 1',
    'attr2' => 'Attr 2',
    'attr3' => 'Attr 3',
    'attr4' => 'Attr 4',
    'attr5' => 'Attr 5',
]);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Table Demo</title>
        
        <link href="table.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <article>
            <h1>Table Demo</h1>
            
            <p>You might resize your browser to see a possible responsive implementation.</p>
            
            <section>
                <h2>Demo 1</h2>
                <?= $table ?>
            </section>
            
            <section>
                <h2>Demo 2 - with specific columns</h2>
                <?= $table->withColumns(['id', 'sku', 'title', 'price']) ?>
            </section>            

        </article>
    </body>
</html>