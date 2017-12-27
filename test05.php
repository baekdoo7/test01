<?php

	$myfile = fopen("itemfeed.txt", "w") or die("Unable to open file!");

	
	
	for($i=0;$i<500000;$i++){
		$txt  = "<<<begin>>>\n";
		$txt .= "<<<mapid>>>".str_pad($i,"10","0",STR_PAD_LEFT)."\n";
		$txt .= "<<<pname>>>주방세정제\n";
		$txt .= "<<<price>>>".(20000 + $i*10)."\n";
		$txt .= "<<<pgurl>>>http://www.cosmeticscop.kr/FrontStore/iGoodsView.phtml?iCategoryId=&iGoodsId=0005_00012&iCategoryMainId=0\n";
		$txt .= "<<<mourl>>>http://www.cosmeticscop.kr/FrontStore/iGoodsView.phtml?iCategoryId=&iGoodsId=0005_00012&iCategoryMainId=0\n";
		$txt .= "<<<android_dp>>>shoppingmall_iosapp://product/No=0000001\n";
		$txt .= "<<<ios_dp>>>shoppingmall_iosapp://product/No=0000001\n";
		$txt .= "<<<igurl>>>http://www.cosmeticscop.kr/shop/data/0/0005_00012.jpg?T=1464300563\n";
		$txt .= "<<<cate1>>>생필품/주방\n";
		$txt .= "<<<cate2>>>세제/화장지\n";
		$txt .= "<<<cate3>>>주방/청소세제\n";
		$txt .= "<<<cate4>>>주방세\n";
		$txt .= "<<<caid1>A001\n";
		$txt .= "<<<caid2>>>A001B001\n";
		$txt .= "<<<caid3>>>A001B001C001\n";
		$txt .= "<<<caid4>>>A001B001C001D001\n";
		$txt .= "<<<ftend>>>\n";
		fwrite($myfile, $txt);
	}
	
	fwrite($myfile, $txt);
	fclose($myfile);
?>

//