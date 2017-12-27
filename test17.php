<?php
$file = "tmp/count.txt";
file_put_contents($file, ".",FILE_APPEND);
$a = isset($_GET['a'])?$_GET['a']:0;

if($a==0){
	echo "counting...";
}
elseif($a== "1"){
	echo "<!DOCTYPE html>";
	echo "<html><head></head><body>";
	echo "<style>";
	echo "#counter1, #counter2, #counter3 { font-family: arial; font-size: 160px; font-weight: bold; }";
	echo "#wrap { display: table; margin-left: auto; margin-right: auto; }";
	echo "</style>";
	echo "<div id='wrap'>";
	echo "<p id='counter1'></p>";
	echo "</div>";
	echo "<script>";
	$s =  "function numberCounter(target_frame, target_number) { ";
	$s .= "	this.count = 0; this.diff = 0; ";
	$s .= "	this.target_count = parseInt(target_number); ";
	$s .= "	this.target_frame = document.getElementById(target_frame); ";
	$s .= "	this.timer = null; ";
	$s .= "	this.counter(); ";
	$s .= " }; ";
	$s .= "numberCounter.prototype.counter = function() { ";
	$s .= " 	var self = this; ";
	$s .= "	this.diff = this.target_count - this.count; ";	
	$s .= " 	if(this.diff > 0) { ";
	$s .= "		self.count += Math.ceil(this.diff / 5); ";
	$s .= " 	} ";
	$s .= "	this.target_frame.innerHTML = this.count.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); ";
	$s .= " 	if(this.count < this.target_count) { ";
	$s .= "		this.timer = setTimeout(function() { self.counter(); }, 20); ";
	$s .= " 	} else { ";
	$s .= "		clearTimeout(this.timer); ";
	$s .= " 	} ";
	$s .= " }; ";
	echo $s;
	echo "new numberCounter('counter1',".filesize($file).")";
	echo("</script>");
	echo("</body></html>"); 
	//echo filesize($file);
	
	
}elseif ($a== "2"){
	echo "initialize";
	unlink($file);
}else{
	echo "counting...";
}
	
		
?>

