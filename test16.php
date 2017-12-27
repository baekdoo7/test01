<?php
/*
 include "adopRTclass.php";
include "adopRTdata.php";

$tmp = false;

$test03 = new rtObject;
if($test03){
$tmp = $test03->isADs();
}

if($tmp === false){
echo "test";
}
else{
//echo "1234:";
//echo $tmp;
}


echo json_encode($test03->getADs());

//echo $test03->haveADs();
*/
?>

<!DOCTYPE html>
<html lang="kr">
    <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>리타게팅 테스트</title>    
    <style type="text/css">
 
    </style>
    </head>
    <body>
       테스트 ... <br />
  	   *        <br />
  	   **        <br />
  	   ***        <br />
  	   ****        <br />
  	   *****        <br />
  	   ******        <br />
  	   *******        <br />
  	   
  	   <br />
  	   <br />
  	   <br />
  	   
  	  <script type="text/javascript">
 // 	  	var adopRT011 = {
 // 	    	  		     atype  : "it",
 // 	    	  		     btype  : "itemRT",
 // 	    	  		     taridx : "t64",
 // 	    	  		     item   : "12345"
 // 	    	  	       }
        
 	  	var adopRT011 = {
 	  			     atype  : "bt",
	  			     btype  : "brandRT",
 	  		    	 taridx : "t1000",
 	  		     	 item   : "16645"
	  	       			}   
//  	  	var adopRT011 = {
//  	  				atype  : "roi",
// 	  				btype  : "itemRT",
//  	  				taridx : "t3000",
//  	  				orderNo: "1234-2", /*오더넘버*/
//  	  				saleAmt: "30000",
//  	  				items  : ["상품1","상품2","상품3"]  
//  	  				}  
 		
  	  </script>
  	  <script type="text/javascript" src="http://www.adoprt.com/adopRTloader.js?a=a"></script> 	
  	  <a href="javascript:post_to_url('http://www.adoprt.com/adopRTloader.js',{'type1':'aaa','type2':'bbb'})">POST보내기</a>
    </body>
</html>