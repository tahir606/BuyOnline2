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
$QtyAvailable = $element->getElementsByTagName('QtyAvailable')->item(0);
$QtyOnHold = $element->getElementsByTagName('QtyOnHold')->item(0);

if ($QtyAvailable->nodeValue == 0 AND $QtyOnHold->nodeValue == 0) {
	$QtyAvailable->nodeValue = 'SoldOut';
}


//Replace old elements with new
$element->replaceChild($QtyAvailable, $QtyAvailable);

$xml->save($fileUrl);

echo "\nMarked processed for".$item;
?>