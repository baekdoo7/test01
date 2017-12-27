<?php require_once('./commons/Keys.php') ?>
<?php
/*
 * AuctionLogin
 * ø¡º«ø°º≠ ¡¶∞¯«œ¥¬ ∑Œ±◊¿Œ ∆‰¿Ã¡ˆ∑Œ ¿Ãµø«ÿº≠ ¿Œ¡ı ∆ºƒœ¿ª πﬁæ∆ø¬¥Ÿ.
 */
 
 	$loginPageUrl = "https://memberssl.auction.co.kr/API/Login/WebServiceLogin2.aspx";	// ø¡º« ∑Œ±◊¿Œ ∆‰¿Ã¡ˆ
 	$ticket = "";
	if (isset($_POST['ticket']))	{
		$ticket = $_POST['ticket'];
	}
?>
	<form method="post" action="<?php echo $loginPageUrl; ?>">
		<div>
			<span>DevID :</span>
			<input type="text" value="<?php echo $devID; ?>" name="DevID">
		</div>
		<div>
			<span>AppID :</span>
			<input type="text" value="<?php echo $appID; ?>" name="AppID">
		</div>
		<div>
			<span>AppID :</span>
			<input type="text" value="<?php echo $appPWD; ?>" name="APPPASSWORD">
		</div>
		<div>
			<span>ReturnUrl :</span>
			<input type=text value="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>" name=ReturnUrl style="WIDTH: 400px;">
		</div>
		<div>
			<span>ticket :</span>
			<input type=text id="ticket" value="<?php echo $ticket; ?>" style="WIDTH: 520px;" size=108>
		</div>
		
		<input type="submit" value="Ticket ∞°¡Æø¿±‚">
	</form>