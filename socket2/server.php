<?php
set_time_limit(0);
require 'class.PHPWebSocket.php';
function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}
	if ( sizeof($Server->wsClients) == 1 ){}
	else
		foreach ( $Server->wsClients as $id => $client )
			if ( $id != $clientID )
				$Server->wsSend($id, $message);
}
function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	$Server->log( "$ip ($clientID) has connected." );
	foreach ( $Server->wsClients as $id => $client )
		if ( $id != $clientID ){}
}
function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	$Server->log( "$ip ($clientID) has disconnected." );
	foreach ( $Server->wsClients as $id => $client ){}
}
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
$Server->wsStartServer('www.test01.com', 9300);
?>