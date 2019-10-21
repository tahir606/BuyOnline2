<?php
$email = filter_input(INPUT_POST, 'email');
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$password = filter_input(INPUT_POST, 'password');
$confirmPassword = filter_input(INPUT_POST, 'confirmPassword');
$phoneNumber = filter_input(INPUT_POST, 'phoneNumber');

$fileUrl = '../data/customer.xml';

if (!file_exists($fileUrl)) {   
	createCustomerXML($email, $firstName, $lastName, $password, $phoneNumber);   
	echo "Customer Successfully Registered";
	die();                  
}

if (!emailExists($email)) {

	//Append new customer to customer.xml file
	// Reference: https://stackoverflow.com/questions/11012820/update-append-data-to-xml-file-using-php/11032722
	$xmldoc = new DomDocument( '1.0' );
	$xmldoc->preserveWhiteSpace = false;
	$xmldoc->formatOutput = true;

	$xml = file_get_contents($fileUrl); 

	$xmldoc->loadXML( $xml, LIBXML_NOBLANKS );
	//Defining XPATH
	$xpath = new DOMXPath($xmldoc);
	// find the CustomerList tag
	$root = $xmldoc->getElementsByTagName('CustomerList')->item(0);
	// create the <Customer> tag
	$customer = $xmldoc->createElement('Customer');
	// add the Customer tag to the <CustomerList> tag
	$root->appendChild($customer);
	// create other elements and add it to the <Customer> tag.

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

	$phoneNumberElement = $xmldoc->createElement('PhoneNumber');
	$customer->appendChild($phoneNumberElement);
	$phoneNumberText = $xmldoc->createTextNode($phoneNumber);
	$phoneNumberElement->appendChild($phoneNumberText);

	$xmldoc->save($fileUrl);

	echo "Customer Successfully Registered";
	exit;

} else {
	header("Refresh: 5; url='../xhtml/register.htm'");
	echo "This email is already in use. Please choose a different email.";
	// echo "<br/><br/><a href='../xhtml/buyonline.htm'>Back</a><br/>";
	die();
}

function createCustomerXML($email, $firstName, $lastName, $password, $phoneNumber) {
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

	$child_node_phoneNumber = $dom->createElement('PhoneNumber', $phoneNumber);
	$customer_node->appendChild($child_node_phoneNumber);

	$root->appendChild($customer_node);
	$dom->appendChild($root);
	$dom->save($xml_file_name);
	echo "$xml_file_name has been successfully created<br/>";
}

//Check if Email already exists
function emailExists($email) {
	// global $fileUrl;
	$xml = simplexml_load_file($GLOBALS['fileUrl']);
	$list = $xml->Customer;
	for ($i = 0; $i < count($list); $i++) {
		$e = $list[$i]->Email;
		if ($email == $e) {
			return true;
		}
	}
	return false;
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
