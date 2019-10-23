<?php 

$fileUrl = '../../data/goods.xml';

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

?>