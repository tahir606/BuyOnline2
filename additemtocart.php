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

$QtyAvailable->nodeValue = $QtyAvailable->nodeValue - 1;
if ($QtyOnHold->nodeValue === '') {
	$QtyOnHold->nodeValue = 0;
}
$QtyOnHold->nodeValue = $QtyOnHold->nodeValue + 1;

//Replace old elements with new
$element->replaceChild($QtyAvailable, $QtyAvailable);
$element->replaceChild($QtyOnHold, $QtyOnHold);

$xml->save($fileUrl);
?>