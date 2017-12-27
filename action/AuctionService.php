<?php
/*
 * 로그인이 필요없는 서비스 호출
 * 1. RequestApplicationTicket.php를 호출해서 인증티켓을 받아온다.
 * 2. AddItem을 호출 할때, 1번에서 받아온 인증티켓을 헤더에 입력하고 호출한다.
 * 3. 2번에서 호출하고 받아온 새 인증티켓을 다음 서비스를 호출 할때, 헤더에 입력 후 호출한다.
 * 
 * 서비스 문의시에 Request SOAP과 Response SOAP을 보내주시면 됩니다.
 * 옥션 API 개발자 커뮤니티 : http://api.auction.co.kr/developer 
 */
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php require_once('./commons/Keys.php') ?>
<?php require_once('./commons/AuctionSession.php') ?>
<?php require_once('./shoppingService/RequestApplicationTicket.php') ?>
<?php require_once('./shoppingService/ViewItem.php') ?>
<?php
	/*
	 * 1. RequestApplicationTicket
	 */
	echo "<p>********* RequestApplicationTicket *********</p>";
	$requestApplicationTicket = new requestApplicationTicket ($devID, $appID, $appPWD);
    $encryptedTicket = $requestApplicationTicket->doService();	// 서비스를 호출하고 새 인증키를 가져온 후 다음 서비스를 호출 할 때, 헤더값에 입력하고 호출한다. 
	echo $encryptedTicket;
		
	/*
	 * 2. ViewItem
	 */
	echo "<p>********* ViewItem *********</p>";
	$viewItem = new viewItem ($encryptedTicket);
	$encryptedTicket = $viewItem->doService("B379392253");	// 서비스를 호출하고 새 인증키를 가져온 후 다음 서비스를 호출 할 때, 헤더값에 입력하고 호출한다.
	
    echo $encryptedTicket;
?>