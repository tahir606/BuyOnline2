<?php
$user = filter_input(INPUT_POST, 'user');
$password = filter_input(INPUT_POST, 'password');

$fileUrl = '../data/manager.txt';

$handle = fopen($fileUrl, "r");
if ($handle) {
	while (($line = fgets($handle)) !== false) {
        // process the line read.
		$parts = explode(',', $line);
		// using trim to remove whitespaces
		if (strcmp($user, trim($parts[0])) == 0 AND strcmp($password, trim($parts[1])) == 0 ) {
			http_response_code(200);
			die();     	
		}
	}
	http_response_code(404);  

	fclose($handle);
} else {
    // error opening the file.
	echo "Error opening the file";
} 
?>