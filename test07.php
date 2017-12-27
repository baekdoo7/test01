<?php
//배열 머지 예제 for문 한번만 타게...
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

$a = array();
$b = array();

//배열세팅
for($i=0 ;$i < 1000;$i++){
	$bb = array();
	$bb['area_idx'] = mt_rand(0,10);
	$bb['name']     = mt_rand(0,10);
	$bb['req']      = mt_rand(0,10);
	$bb['joinKey']  = $i%3;
	$bb['bkey']     = array();
	$a['akey_'.$i]   = $bb;	
}

//배열하나더 세팅
for($i=0 ;$i < 1000;$i++){
	$bb = array();
	$bb['area_idx'] = mt_rand(0,10);
	$bb['name']     = mt_rand(0,10);
	$bb['req']      = mt_rand(0,10);
	$bb['joinKey']  = $i%20;
    $b['bkey_'.$i]   = $bb;
}


$time01 = get_time();

/*
foreach($a as $key => $val) {
	foreach($b as $subKey => $subVal) {
		if($val["joinKey"] == $subVal["joinKey"]) {
			//echo $val[‘joinKey’]."/".$subVal[‘joinKey’]."<br />";
			array_push($a[$key]['bkey'], $subKey);
		}
	}
}
*/
/**/

$c =array();
foreach($b as $key => $val){
	//echo $val["joinKey"]."<br />";
	if(isset($c[$val["joinKey"]])){		
		array_push($c[$val["joinKey"]], $key);
	}
	else{
		$c[$val["joinKey"]] =array();
		array_push($c[$val["joinKey"]], $key);
	}
	
}

foreach($a as $key => $val) {
	 if(isset($c[$val[joinKey]])){
	 	$a[$key]['bkey'] = $c[$val[joinKey]];
	 }
				
}
/**/

echo "처리시간 : ".(get_time() - $time01)."초";

echo "<pre>";
print_r($a);
echo "</pre>";

?>

