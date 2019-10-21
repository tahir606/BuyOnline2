<?php  
$name = filter_input(INPUT_POST, 'name');
$price = filter_input(INPUT_POST, 'price');
$qty = filter_input(INPUT_POST, 'qty');
$desc = filter_input(INPUT_POST, 'desc');

$fileUrl = '../data/goods.xml';

if (!file_exists($fileUrl)) {   
	createGoodsXML($name, $price, $qty, $desc); 
	die();                  
}

//Append new customer to customer.xml file
// Reference: https://stackoverflow.com/questions/11012820/update-append-data-to-xml-file-using-php/11032722
$xmldoc = new DomDocument( '1.0' );
$xmldoc->preserveWhiteSpace = false;
$xmldoc->formatOutput = true;

$xml = file_get_contents($fileUrl); 

$xmldoc->loadXML( $xml, LIBXML_NOBLANKS );
//Defining XPATH
$xpath = new DOMXPath($xmldoc);
// find the GoodsList tag
$root = $xmldoc->getElementsByTagName('GoodsList')->item(0);
// create the <Good> tag
$good = $xmldoc->createElement('Good');
// add the Good tag to the <GoodsList> tag
$root->appendChild($good);
// create other elements and add it to the <Good> tag.

$id = generateID($xpath);
$idElement = $xmldoc->createElement('Id');
$good->appendChild($idElement);
$idText = $xmldoc->createTextNode($id);
$idElement->appendChild($idText);

$nameElement = $xmldoc->createElement('Name');
$good->appendChild($nameElement);
$nameText = $xmldoc->createTextNode($name);
$nameElement->appendChild($nameText);

$priceElement = $xmldoc->createElement('Price');
$good->appendChild($priceElement);
$priceText = $xmldoc->createTextNode($price);
$priceElement->appendChild($priceText);

$quantityElement = $xmldoc->createElement('QtyAvailable');
$good->appendChild($quantityElement);
$quantityText = $xmldoc->createTextNode($qty);
$quantityElement->appendChild($quantityText);

$qtyonholdElement = $xmldoc->createElement('QtyOnHold');
$good->appendChild($qtyonholdElement);
$qtyonholdText = $xmldoc->createTextNode('');
$qtyonholdElement->appendChild($qtyonholdText);

$qtysoldElement = $xmldoc->createElement('QtySold');
$good->appendChild($qtysoldElement);
$qtysoldText = $xmldoc->createTextNode('');
$qtysoldElement->appendChild($qtysoldText);

$descriptionElement = $xmldoc->createElement('Description');
$good->appendChild($descriptionElement);
$descriptionText = $xmldoc->createTextNode($desc);
$descriptionElement->appendChild($descriptionText);

$xmldoc->save($fileUrl);

echo "Good Successfully Registered at ID: $id";
exit;

function createGoodsXML($name, $price, $qty, $desc) {
	$dom = new DOMDocument();
	$dom->encoding = 'utf-8';
	$dom->xmlVersion = '1.0';
	$dom->formatOutput = true;
	$xml_file_name = $GLOBALS['fileUrl'];
	$root = $dom->createElement('GoodsList');
	$good_node = $dom->createElement('Good');
	
	$child_node_id = $dom->createElement('Id', '1');
	$good_node->appendChild($child_node_id);

	$child_node_name = $dom->createElement('Name', $name);
	$good_node->appendChild($child_node_name);
	
	$child_node_price = $dom->createElement('Price', $price);
	$good_node->appendChild($child_node_price);

	$child_node_quantity = $dom->createElement('QtyAvailable', $qty);
	$good_node->appendChild($child_node_quantity);

	$child_node_qtyonhold = $dom->createElement('QtyOnHold', '');
	$good_node->appendChild($child_node_qtyonhold);

	$child_node_qtysold = $dom->createElement('QtySold', '');
	$good_node->appendChild($child_node_qtysold);

	$child_node_description = $dom->createElement('Description', $desc);
	$good_node->appendChild($child_node_description);

	$root->appendChild($good_node);
	$dom->appendChild($root);
	$dom->save($xml_file_name);
	echo "$xml_file_name has been successfully created\n";
	echo "Good Successfully Registered at ID: 1";
}

//Generate ID for new Customer
//Using XPATH
function generateID($xpath) {
	$result = $xpath->query('(//Good/Id)[last()]');
		// Getting last element
	if($result->length > 0) {
		$node = $result->item(0);
		$id = $node->nodeValue;
		$id = $id + 1;
		return $id;
	} 
	else {
		return 1;
	}
}


?>