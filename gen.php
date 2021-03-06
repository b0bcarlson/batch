<?php
require_once "sections.php";
foreach($aisles as $aislename => $sections)
	foreach($sections as $sectioncode => $v)
		$aisles[$aislename][$sectioncode] = [$v, []];

require_once "Item.php";
$batches = [];
$oneEach = [];
foreach($_FILES['csv']['tmp_name'] as $k => $v){
	$items = array_map('str_getcsv', file($v));
	$batches[$items[1][0]] = count($items)-1;
	for($i = 1; $i<count($items)-1; $i++){
		$ite = $items[$i];
		foreach($aisles as $aislename => $sections)
			foreach($sections as $sectioncode => $x){
				if(intval($ite[1]) === $sectioncode){
					$aisles[$aislename][$sectioncode][1][] = new Item($ite[0], $ite[2], $ite[3], $ite[4], $ite[5], $ite[6], $ite[7], $ite[8], $ite[9], $ite[11], $ite[12], $ite[13], $ite[14], $ite[15]);
					continue 3;
				}
		}
		reset($aisles);
		$aisles[key($aisles)][0][1][] = new Item($ite[0], $ite[2], $ite[3], $ite[4], $ite[5], $ite[6], $ite[7], $ite[8], $ite[9], $ite[11], $ite[12], $ite[13], $ite[14], $ite[15]);
	}
}
function cmp($a, $b)
{
    return strnatcmp($a->ordercode, $b->ordercode);
}
foreach($aisles as $aislename => $sections){
	foreach($sections as $sectioncode => $v){
		if(count($v[1]) == 0){
			unset($aisles[$aislename][$sectioncode]);
		}
		else{
			usort($aisles[$aislename][$sectioncode][1], "cmp");
		}
	}
	if(count($aisles[$aislename]) == 0)
		unset($aisles[$aislename]);
}
echo"<style>
table { border-collapse: collapse; }
td { padding-left: 10px;}
</style><table>";
foreach($batches as $batchname => $numitems)
	echo "$batchname $numitems items<br>";
foreach($aisles as $aislename => $sections){
	echo "<tr><td><br>$aislename</td></tr>";
	foreach($sections as $sectioncode => $v){
		$c = count($v[1]);
		echo "<tr style=\"border: solid thin; background-color: lightgray;-webkit-print-color-adjust:exact;\"><td>$c</td><td></td><td>$sectioncode $v[0]</td><td>Size</td><td>Base</td><td>Retail</td><td>New Retail</td><td>FS</td><td>New FS</td><td>Batch</td></tr>";
		foreach($v[1] as $ite){
			if($ite->size == "1 EA")
				$oneEach[] = $ite;
			echo"<tr>
			<td>$ite->ordercode</td>
			<td>$ite->upc</td>
			<td>$ite->description</td>
			<td>$ite->size</td>
			<td>$ite->base</td>
			<td>$ite->retail</td>
			<td>$ite->newretail</td>
			<td>$ite->fs</td>
			<td>$ite->newfs</td>
			<td>$ite->batch</td></tr>";
		}
	}
}
if(count($oneEach) >0){
	$str = "";
	foreach($oneEach as $ite)
		$str = $str . "\\n" . $ite->upc . " " . $ite->description . " from batch " . $ite->batch;
	echo "<script>alert(\"Default sizes found:" . $str . "\")</script>";
}
