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
$QtySold = $element->getElementsByTagName('QtySold')->item(0);

$QtySold->nodeValue = 0;

//Replace old elements with new
$element->replaceChild($QtySold, $QtySold);

$xml->save($fileUrl);

echo "\nMarked processed for".$item;
?>