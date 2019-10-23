<?php  
	
$fileUrl = '../../data/goods.xml';

$xml = simplexml_load_file($fileUrl);
$list = $xml->Good;

for ($i = 0; $i < count($list); $i++) {
	$id = $list[$i]->Id;
	$n = $list[$i]->Name;
	$d = $list[$i]->Price;
	$p = $list[$i]->QtyAvailable;
	$q = $list[$i]->QtyOnHold;
	$s = $list[$i]->QtySold;
	if ($s > 0) {		
		echo $id.",".$n.",".$d.",".$p.",".$q.",".$s;
		echo "\n";
	}
}
?>