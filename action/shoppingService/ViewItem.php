<?php
/*
 * ViewItem
 * [ø‰√ª«œ¥¬ ∞Ê∏≈π¯»£¿« ªÛººº≥∏Ì¿ª ¡¶∞¯]
 * http://api.auction.co.kr/APIv1/Auctionservice.asmx?op=ViewItem
 * 
 * º≠∫ÒΩ∫ πÆ¿«Ω√ø° Request SOAP∞˙ Response SOAP¿ª ∫∏≥ª¡÷Ω√∏È µÀ¥œ¥Ÿ.
 * ø¡º« API ∞≥πﬂ¿⁄ ƒøπ¬¥œ∆º : http://api.auction.co.kr/developer
 */
class viewItem
{
	// Declare Needed Variables
	private $action = "http://www.auction.co.kr/APIv1/ShoppingService/ViewItem";
	private $serverUrl = "https://api.auction.co.kr/APIv1/AuctionService.asmx";	// Ω«º≠πˆ
	//private $serverUrl = "https://apitest.auction.co.kr/APIv1/ShoppingService.asmx";	// ≈◊Ω∫∆Æº≠πˆ
	private $requestTicket;
	
	/**
	 * $reqestTicket : RequestTicket.php º≠∫ÒΩ∫ø°º≠ ∞°¡Æø¬ Ticket ∞™
	 */
	public function __construct($reqestTicket){
		$this->requestTicket = $reqestTicket;
	}
	/**
	 * º≠∫ÒΩ∫∏¶ Ω««‡(»£√‚)«—¥Ÿ.
	 */
	public function doService($itemID){
		// Set Request SOAP Message


		$requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
		$requestXmlBody .= '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">';
		$requestXmlBody .= '<soap:Header>';
		$requestXmlBody .= '<EncryptedTicket xmlns="http://www.auction.co.kr/Security">';
		$requestXmlBody .= "<Value>$this->requestTicket</Value>";
		$requestXmlBody .= '</EncryptedTicket>';
		$requestXmlBody .= '</soap:Header>';
		$requestXmlBody .= '<soap:Body>';
        $requestXmlBody .= '<GetSearchResults xmlns="http://www.auction.co.kr/APIv1/AuctionService">';
        $requestXmlBody .= '<req>';
        $requestXmlBody .= '<Query xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">나이키운동화</Query>';
        $requestXmlBody .= '<ItemType xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">AllItemTypes</ItemType>';
        $requestXmlBody .= '<Order xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">AuctionRanking</Order>';
        $requestXmlBody .= '<Pagination PageIndex="0" PageSize="10" xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd" />';
        $requestXmlBody .= '<TotalOnly xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">false</TotalOnly>';
        $requestXmlBody .= '</req>';
        $requestXmlBody .= '</GetSearchResults>';
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
		$this->processResponse ($responseXml);
		
		return $this->requestTicket;
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
			echo "<BR>".$this->getString($responseDoc->saveXML());
			echo "</PRE>";
				
			// Error
			$eleFaultcode = $responseDoc->getElementsByTagName('faultcode')->item(0);
			$eleFaultstring = $responseDoc->getElementsByTagName('faultstring')->item(0);
			
			if (empty ($eleFaultcode)){
				//Process Response
				$encryptedTicket = $responseDoc->getElementsByTagName('EncryptedTicket')->item(0);
				if ($encryptedTicket != null){
					//echo "EncryptedTicket : $encryptedTicket->nodeValue<BR>";
					$this->requestTicket = $encryptedTicket->nodeValue;
				}
				
				$eleItem = $responseDoc->getElementsByTagName('Item');
				
				for ($i = 0 ; $i < $eleItem->length ; $i++){
					echo "ItemID : ".$eleItem->item($i)->getAttribute("ItemID")."<BR>";
					echo "Name : ".$this->getString($eleItem->item($i)->getAttribute("Name"))." ";
					echo "<BR>";
				}
				
			}else{
				$this->processError($eleFaultcode, $eleFaultstring);
			}
		}
	}
	/**
	 * ø°∑Ø √≥∏Æ∏¶ «—¥Ÿ.
	 * $eleFaultcode	: ø¿∑˘ ƒ⁄µÂ ∏ﬁΩ√¡ˆ
	 * $eleFaultstring	: ø¿∑˘ ∏ﬁΩ√¡ˆ
	 */
	private function processError($eleFaultcode, $eleFaultstring){
		if ($eleFaultcode != null) echo "faultcode : ".iconv("UTF-8", "UTF-8", urldecode (htmlentities ($eleFaultcode->nodeValue, ENT_NOQUOTES, "UTF-8")))."<BR>";
		if ($eleFaultstring != null) echo "faultstring : ".iconv("UTF-8", "UTF-8", urldecode (htmlentities ($eleFaultstring->nodeValue, ENT_NOQUOTES, "UTF-8")))."<BR>";
	}
	
	private function getString($value){
		return iconv("UTF-8", "UTF-8", urldecode (htmlentities ($value, ENT_NOQUOTES, "UTF-8")));
	}
}	
?>