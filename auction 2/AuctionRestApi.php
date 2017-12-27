<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AuctionRestApi {
    
    private $soapAuction;
    private $login_session;

    private $action = "http://www.auction.co.kr/APIv1/ShoppingService/ViewItem";
    //private $serverUrl = "https://api.auction.co.kr/APIv1/AuctionService.asmx";
    private $serverUrl = "https://apitest.auction.co.kr/APIv1/ShoppingService.asmx";
    
    

    public function __construct()
    {
        $this->_session = array('devID' => "adop2016",'appID' => "adop2016",'appPWD' => "Adop123$123$");
        $requestApplicationTicket = new auctionSessionApi($this->_session);
        $this->requestTicket = $requestApplicationTicket->doService();
    }
        
    public function doService($itemKEYWORD){
        // Set Request SOAP Message
        
        $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>';
        $requestXmlBody .= '<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">';
        $requestXmlBody .= '<soap12:Header>';
        $requestXmlBody .= '<EncryptedTicket xmlns="http://www.auction.co.kr/Security">';
        $requestXmlBody .= "<Value>$this->requestTicket</Value>";
        $requestXmlBody .= '</EncryptedTicket>';
        $requestXmlBody .= '</soap12:Header>';
        $requestXmlBody .= '<soap12:Body>';
        $requestXmlBody .= '<GetSearchResultsBest100 xmlns="http://www.auction.co.kr/APIv1/AuctionService">
             <req>
                <Category xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">05000000</Category>
                <Gender xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">1</Gender>
                <Age xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">All</Age>
                <Pagination PageIndex="1" PageSize="100" xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd" />
                <PaginationUseYn xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">true</PaginationUseYn>

                <ExcludeMartOnItem xmlns="http://schema.auction.co.kr/Arche.APISvc.xsd">true</ExcludeMartOnItem>
              </req>
           </GetSearchResultsBest100>';
        $requestXmlBody .= '</soap12:Body>';
        $requestXmlBody .= '</soap12:Envelope>';

        // Load the XML Document to Print Request SOAP
        $requestDoc = new DomDocument();
        $requestDoc->loadXML($requestXmlBody);

//        // Print Request SOAP
//        echo "<PRE>";
//        echo "<STRONG>* REQUEST SOAP</STRONG><BR>";
//        echo htmlentities ($requestDoc->saveXML());
//        echo "</PRE>";

        //Create a new auction session with all details pulled in from included auctionSession.php
        $session = new AuctionCommon($this->serverUrl, $this->action);

        //send the request and get response
        $responseXml = $session->sendHttpRequest($requestXmlBody);

        // Process Response
        $this->processResponse ($responseXml);

        return $this->requestTicket;
    }

    private function processResponse($responseXml){
        if(stristr($responseXml, 'HTTP 404') || $responseXml == '') {
            die('<P>Error sending request');
        } else {
            //Xml string is parsed and creates a DOM Document object
            $responseDoc = new DomDocument();
            $responseDoc->loadXML($responseXml);
            
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

    private function processError($eleFaultcode, $eleFaultstring){
        if ($eleFaultcode != null) $_RETURN['faultcode'] = urldecode (htmlentities ($eleFaultcode->nodeValue, ENT_NOQUOTES, "UTF-8"));
        if ($eleFaultstring != null) $_RETURN['faultstring'] = urldecode (htmlentities ($eleFaultstring->nodeValue, ENT_NOQUOTES, "UTF-8"))."<BR>";
    }

    private function getString($value){
        return urldecode (htmlentities ($value, ENT_NOQUOTES, "UTF-8"));
    }
    
}