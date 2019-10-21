<?php 

$fileUrl = '../data/goods.xml';

$xml = simplexml_load_file($fileUrl);
$list = $xml->Good;

for ($i = 0; $i < count($list); $i++) {
	$id = $list[$i]->Id;
	$n = $list[$i]->Name;
	$d = $list[$i]->Description;
	$p = $list[$i]->Price;
	$q = $list[$i]->QtyAvailable;
	if ($q > 0) {		
		echo $id.",".$n.",".$d.",".$p.",".$q;
		echo "\n";
	}
}

// $xmldoc = new DomDocument( '1.0' );
// $xmldoc->preserveWhiteSpace = false;
// $xmldoc->formatOutput = true;

// $xml = file_get_contents($fileUrl);

// $xmldoc->loadXML( $xml, LIBXML_NOBLANKS );
// 	//Defining XPATH
// $xpath = new DOMXPath($xmldoc);

// // $result = $xpath->query('//Good/Id[@QtyAvailable>0]');
// $result = $xpath->query("//GoodsList/Good[QtyAvailable/text()!='0']");
// // Getting last element
// if($result->length > 0) {
// 	for ($i=0; $i < $result->length ; $i++) { 
// 		# code...
// 		$node = $result->item($i);
// 		echo "$node->nodeValue\n";
// 	}
// }
?>