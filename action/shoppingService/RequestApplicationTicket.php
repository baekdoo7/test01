<?php
/*
 * RequestApplicationTicket
 * ¿Œ¡ı∆ƒ∂Û∏ﬁ≈Õ∏¶ ¿¸¥ﬁ«œ∞Ì æ÷«√∏Æƒ…¿Ãº« ¿Œ¡ı∆ºƒœ¿ª ø‰√ª«’¥œ¥Ÿ.
 * http://api.auction.co.kr/APIv1/SecurityService.asmx?op=RequestApplicationTicket
 * 
 * º≠∫ÒΩ∫ πÆ¿«Ω√ø° Request SOAP∞˙ Response SOAP¿ª ∫∏≥ª¡÷Ω√∏È µÀ¥œ¥Ÿ.
 * ø¡º« API ∞≥πﬂ¿⁄ ƒøπ¬¥œ∆º : http://api.auction.co.kr/developer
 */
class requestApplicationTicket
{
	// Declare Needed Variables
	private $action = "http://www.auction.co.kr/ServiceInterfaces/RequestApplicationTicket";
	//private $serverUrl = "https://api.auction.co.kr/APIv1/SecurityService.asmx";
	private $serverUrl = "https://apitest.auction.co.kr/APIv1/SecurityService.asmx";
	//private $serverUrl = "https://api.auction.co.kr/APIv1/Securityservice.asmx";
	private $devID;
	private $appID; 
	private $appPWD;
	
	/**
	 * $devID	: ø¡º« API ∞≥πﬂ¿⁄ ID
	 * $appID	: ø¡º« API «¡∑Œ±◊∑• ID
	 * $appPWD	: ø¡º« API «¡∑Œ±◊∑• æœ»£
	 */
	public function __construct($devID, $appID, $appPWD){
		$this->devID = $devID;
		$this->appID = $appID;
		$this->appPWD = $appPWD;
	}
	/**
	 * º≠∫ÒΩ∫∏¶ Ω««‡(»£√‚)«—¥Ÿ.
	 */
	public function doService(){
		
		// Set Request SOAP Message
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">';
		$requestXmlBody .= '<soap:Body>';
		$requestXmlBody .= '<RequestApplicationTicket xmlns="http://www.auction.co.kr/ServiceInterfaces">';
		$requestXmlBody .= '<req DevID="'.$this->devID.'" AppID="'.$this->appID.'" AppPassword="'.$this->appPWD.'" />';
		$requestXmlBody .= '</RequestApplicationTicket>';
		$requestXmlBody .= '</soap:Body>';
		$requestXmlBody .= '</soap:Envelope>';
		
		// Load the XML Document to Print Request SOAP
		$requestDoc = new DomDocument();
		$requestDoc->loadXML($requestXmlBody);
		
		// Print Request SOAP
		echo "<PRE>";
		echo "<STRONG>* REQUEST SOAP</STRONG><BR>";
		echo htmlentities ($requestDoc->saveXML());
		echo "</PRE>";

	 	//Create a new auction session with all details pulled in from included auctionSession.php
		$session = new auctionSession($this->serverUrl, $this->action);
		
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
	
		// Process Response
		return $this->processResponse ($responseXml);
	}
	/**
	 * Request SOAP Message∏¶ º≠πˆø° ø‰√ª«œ∞Ì πﬁæ∆ø¬ Response SOAP Message∏¶ ∞°¡ˆ∞Ì √≥∏Æ«—¥Ÿ.
	 * $responseXml	: Response SOAP Message
	 */
	private function processResponse($responseXml){
		if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
			die('<P>Error sending request');
		} else {
			//Xml string is parsed and creates a DOM Document object
			$responseDoc = new DomDocument();
			$responseDoc->loadXML($responseXml);
			
			// Print Response SOAP
			echo "<PRE>";
			echo "<STRONG>* RESPONSE SOAP</STRONG><BR>";
			//echo "<BR>".iconv("UTF-8", "EUC-KR", urldecode (htmlentities ($responseDoc->saveXML(), ENT_NOQUOTES, "UTF-8")) );
            echo "<BR>".iconv("UTF-8", "EUC-KR", urldecode (htmlentities ($responseDoc->saveXML(), ENT_NOQUOTES, "UTF-8")) );
			echo "</PRE>";
				
			// Error
			$eleFaultcode = $responseDoc->getElementsByTagName('faultcode')->item(0);
			$eleFaultstring = $responseDoc->getElementsByTagName('faultstring')->item(0);
			if (empty ($eleFaultcode)){
				//Process Response
				$eleAuthenticationTicket = $responseDoc->getElementsByTagName('RequestApplicationTicketResult')->item(0);
	
				if ($eleAuthenticationTicket != null){
					return $eleAuthenticationTicket->nodeValue;
				}
             
			}else{
				$this->processError($eleFaultcode, $eleFaultstring);
           
			}
		}
		
		return "";
	}
	/**
	 * ø°∑Ø √≥∏Æ∏¶ «—¥Ÿ.
	 * $eleFaultcode	: ø¿∑˘ ƒ⁄µÂ ∏ﬁΩ√¡ˆ
	 * $eleFaultstring	: ø¿∑˘ ∏ﬁΩ√¡ˆ
	 */
	private function processError($eleFaultcode, $eleFaultstring){
        		
		if ($eleFaultcode != null) echo "faultcode : ".iconv("UTF-8", "EUC-KR", urldecode (htmlentities ($eleFaultcode->nodeValue, ENT_NOQUOTES, "UTF-8")))."<BR>";
		if ($eleFaultstring != null) echo "faultstring01 : ".iconv("UTF-8", "EUC-KR", urldecode (htmlentities ($eleFaultstring->nodeValue, ENT_NOQUOTES, "UTF-8")))."<BR>";
    
	}
}	
?>