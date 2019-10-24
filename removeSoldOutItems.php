<?php  
$item = filter_input(INPUT_POST, 'item');

$fileUrl = '../../data/goods.xml';

$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true; 
$xml->preserveWhiteSpace = false;
$xml->load($fileUrl);

//Get item Element
$nodes = $xml->getElementsByTagName('Good');
for ($i=0; $i < $nodes->length; $i++) { 
	$element = $nodes->item($i);
	if ($element->getElementsByTagName('Id')->item(0)->nodeValue == $item) {
		echo $element->getElementsByTagName('Name')->item(0)->nodeValue;
		break;
	}
}

// $element = $xml->getElementsByTagName('Good')->item($item-1);  

//Load child elements
$QtyAvailable = $element->getElementsByTagName('QtyAvailable')->item(0);
$QtyOnHold = $element->getElementsByTagName('QtyOnHold')->item(0);

if ($QtyAvailable->nodeValue == 0 AND $QtyOnHold->nodeValue == 0) {
	$element->parentNode->removeChild($element);
}


//Replace old elements with new
// $element->replaceChild($QtyAvailable, $QtyAvailable);

$xml->save($fileUrl);

echo "\nMarked processed for".$item;
?>