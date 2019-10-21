<?php  
$name = filter_input(INPUT_POST, 'name');
$price = filter_input(INPUT_POST, 'price');
$qty = filter_input(INPUT_POST, 'qty');
$desc = filter_input(INPUT_POST, 'desc');

$fileUrl = '../data/goods.xml';

if (!file_exists($fileUrl)) {   
	createGoodsXML($name, $price, $qty, $desc);   
	echo "Good Successfully Registered";
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
$customer = $xmldoc->createElement('Good');
// add the Good tag to the <GoodsList> tag
$root->appendChild($customer);
// create other elements and add it to the <Good> tag.

$id = generateID($xpath);
$idElement = $xmldoc->createElement('Id');
$customer->appendChild($idElement);
$idText = $xmldoc->createTextNode($id);
$idElement->appendChild($idText);

$emailElement = $xmldoc->createElement('Email');
$customer->appendChild($emailElement);
$emailText = $xmldoc->createTextNode($email);
$emailElement->appendChild($emailText);

$firstNameElement = $xmldoc->createElement('FirstName');
$customer->appendChild($firstNameElement);
$firstNameText = $xmldoc->createTextNode($firstName);
$firstNameElement->appendChild($firstNameText);

$lastNameElement = $xmldoc->createElement('LastName');
$customer->appendChild($lastNameElement);
$lastNameText = $xmldoc->createTextNode($lastName);
$lastNameElement->appendChild($lastNameText);

$passwordElement = $xmldoc->createElement('Password');
$customer->appendChild($passwordElement);
$passwordText = $xmldoc->createTextNode($password);
$passwordElement->appendChild($passwordText);

$xmldoc->save($fileUrl);

echo "Good Successfully Registered";
exit;

function createGoodsXML($name, $price, $qty, $desc) {
	$dom = new DOMDocument();
	$dom->encoding = 'utf-8';
	$dom->xmlVersion = '1.0';
	$dom->formatOutput = true;
	$xml_file_name = $GLOBALS['fileUrl'];
	$root = $dom->createElement('CustomerList');
	$customer_node = $dom->createElement('Customer');
	
	$child_node_id = $dom->createElement('Id', '1');
	$customer_node->appendChild($child_node_id);

	$child_node_email = $dom->createElement('Email', $email);
	$customer_node->appendChild($child_node_email);
	
	$child_node_firstName = $dom->createElement('FirstName', $firstName);
	$customer_node->appendChild($child_node_firstName);

	$child_node_lastName = $dom->createElement('LastName', $lastName);
	$customer_node->appendChild($child_node_lastName);

	$child_node_password = $dom->createElement('Password', $password);
	$customer_node->appendChild($child_node_password);

	$root->appendChild($customer_node);
	$dom->appendChild($root);
	$dom->save($xml_file_name);
	echo "$xml_file_name has been successfully created<br/>";
}

//Generate ID for new Customer
//Using XPATH
function generateID($xpath) {
	$result = $xpath->query('(//Customer/Id)[last()]');
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