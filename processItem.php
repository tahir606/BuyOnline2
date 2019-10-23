<?php  
	$item = filter_input(INPUT_POST, 'item');

$fileUrl = '../../data/goods.xml';

$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true; 
$xml->preserveWhiteSpace = false;
$xml->load($fileUrl);

//Get item Element
$element = $xml->getElementsByTagName('Good')->item($item-1);  

//Load child elements
$QtySold = $element->getElementsByTagName('QtySold')->item(0);

$QtySold->nodeValue = 0;

//Replace old elements with new
$element->replaceChild($QtySold, $QtySold);

$xml->save($fileUrl);

echo "\nMarked processed for".$item;
?>