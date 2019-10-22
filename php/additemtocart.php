<?php  
$item = filter_input(INPUT_POST, 'item');

$fileUrl = '../data/goods.xml';

$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true; 
$xml->preserveWhiteSpace = false;
$xml->load($fileUrl);

 //Get item Element
$element = $xml->getElementsByTagName('Good')->item(0);  

 //Load child elements
$name = $element->getElementsByTagName('Name')->item(0);
// $comment = $element->getElementsByTagName('comment')->item(0) ;

$name->nodeValue = 'SouuupHarpicCh';

 //Replace old elements with new
$element->replaceChild($name, $name);
// $element->replaceChild($comment, $comment);
$xml->save($fileUrl);
?>