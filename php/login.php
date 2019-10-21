<?php
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

$fileUrl = '../data/customer.xml';

$handle = fopen($fileUrl, "r");
if (authenticate($email, $password) != 'invalid') {
	echo $email;
	http_response_code(200);
} else {
	echo "invalid";
	http_response_code(401);
}

function authenticate($email, $password) {
	$xml = simplexml_load_file($GLOBALS['fileUrl']);
	$list = $xml->Customer;
	for ($i = 0; $i < count($list); $i++) {
		$e = $list[$i]->Email;
		$p = $list[$i]->Password;

		if ($email == $e && $password == $p) {
			return $list[$i]->Email;
		}
	}
	return 'invalid';
}
?>