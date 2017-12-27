<?php
/*
 * 로그인이 필요한 서비스 호출
 * 1. AuctionLogin.php 페이지를 통해 옥션에서 제공하는 로그인으로 인증 티켓을 받아온다.
 * 2. AddItem을 호출 할때, 1번에서 받아온 인증티켓을 헤더에 입력하고 호출한다.
 * 3. 2번에서 호출하고 받아온 새 인증티켓을 다음 서비스를 호출 할때, 헤더에 입력 후 호출한다.
 * 
 * 서비스 문의시에 Request SOAP과 Response SOAP을 보내주시면 됩니다.
 * 옥션 API 개발자 커뮤니티 : http://api.auction.co.kr/developer 
 */
?>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<?php require_once('./commons/AuctionSession.php') ?>
<?php require_once('./shoppingService/AddItem.php') ?>
<?php
	$requestTicket = "";	// AuctionLogin.php에서 옥션 로그인을 통해 받아온 인증 티켓을 입력합니다.
	
	/*
	 * RequestTicket
	 */
	echo "<p>********* AddItem *********</p>";
	$addItem = new addItem ($requestTicket);
	$encryptedTicket = $addItem->doService();	// 서비스를 호출하고 새 인증키를 가져온 후 다음 서비스 호출시에, 헤더값에 입력하고 호출한다. 
	echo $encryptedTicket;
?>
<html>
	<head>
		<script language="JavaScript" type="text/javascript">
			function OpenAuctionLogin(){
				window.open ('AuctionLogin.php', '', '');
			}
		</script>
	</head>
	<body>
		
		<a href="javascript:OpenAuctionLogin();">인증티켓 가져오기</a>
	
	</body>
</html>