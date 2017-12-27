//====
//==== 작성자 : baekdoo
//==== 작성일 : 2016.11.29
//==== 기 능 : 기존 콤파스 adop.js 를 jQuery 없는 버전으로 리빌드   

//=================================================================================================
//======== 광고 코드 시작 ============================================================================	
//if(console != "undefined"){
//	console.log("adopNEW starting.....!");
//}


//랜덤 키생성(3자리 랜덤 문자열)
function makeid234()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 3; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

//콤파스 광고 로딩 시
function adopRun001(){
	//기초 변수 세팅 
	var $adpIns = document.getElementsByTagName("ins");
	var $adpSet =  []; //adop ins tag Elements
	var rand_code  = '' ; //rand string 3 length
	var getPageUrl = '' ; //loc info
	var __params = {}   ; //ins attr info
	var regExp   = null ; //regular expression for referer check
	var __url = "" ;
    var params = "";
    var iurl = ""  ;
    var $iframeTmp = null;
    var $adp = null;

    //console.log("ins tag count : "+$adpIns.length); //ins Tag Count print
   
    //adop ins tag 수집
    for(var i=0 ;i<$adpIns.length ; i++){
    	if($adpIns[i].className == "adsbyadop"){
    		$adpSet.push($adpIns[i]);
    	}
    }
    //adop ins tag가 없으면 종료
    if($adpSet.length <= 0){
    	return;
    }
    //console.log("adop ins tag count : " + $adpSet.length); //adop ins Tag count print
    
    //insert iframe to adop ins Tag
    for(var j=0;j<$adpSet.length;j++ ){
		//initialize
		rand_code  = makeid234();
		getPageUrl = "";
		__params   = {};
		regExp     = null;
		__url      = "";
		params     = "";
		iurl       = "";
		$iframeTmp = null;
		$adp       = null; 
		
		$adp = $adpSet[j];      
		getPageUrl = window.location.href;
		
		regExp = /compass\.adop\.cc/;
        if( getPageUrl.search( regExp ) != -1 ) {        	
            getPageUrl = unescape(document.referrer);
        }
        
        if( getPageUrl.length > 200 )   getPageUrl = '';
       
        __params['over-size'] = $adp.getAttribute('_over_size');
        __params['over-zone'] = $adp.getAttribute('_over_zone');
        __params['adop-zone'] = $adp.getAttribute('_adop_zon');
  // console.log( typeof(__params['over-size'])) ;   
        if( __params['over-size'] != null && document.body.clientWidth >= __params['over-size']){
            __params_zone = __params['over-zone'];
            __ori_zone    = __params['adop-zone'];
            over_size     = true;
        }
        else{
            __params_zone = $adp.getAttribute('_adop_zon');
            over_size     = false;
        }
// console.log(document.body.clientWidth);       
        __params['type'] = $adp.getAttribute('_adop_type');
        __params['loc']  = $adp.getAttribute('_page_url') ? $adp.getAttribute('_page_url') : escape(getPageUrl);
        
        __params['size_width'] = $adp.style.width.replace('px', '');
        __params['size_height'] = $adp.style.height.replace('px', '');
        
        if (over_size){
            if(__params['over-size'] == 728){
            	$adp.style.width = "728px" ;
            	$adp.style.height = "90px" ;
                __params['size_width'] = 728;
                __params['size_height'] = 90;
            }else if(__params['over-size'] == 970){
            	$adp.style.width = "970px" ;
            	$adp.style.height = "90px" ; 
                __params['size_width'] = 970;
                __params['size_height'] = 90;
            }else if(__params['over-size'] == 468){
            	$adp.style.width = "468px" ;
            	$adp.style.height = "60px" ;            
                __params['size_width'] = 468;
                __params['size_height'] = 60;
            }else if(__params['over-size'] == 336){
            	$adp.style.width = "336px" ;
            	$adp.style.height = "280px" ;             	
                __params['size_width'] = 336;
                __params['size_height'] = 280;
            }

            $adp.setAttribute('_adop_zon',__params_zone);
        }
        
        $adp.className = "adsbyadop_" + __params_zone + rand_code ;
        //$adp.removeClass('adsbyadop').addClass("adsbyadop_" + __params_zone + rand_code);
        
        __url = '//compass.adop.cc/RD/' + encodeURIComponent(__params_zone);        
        //params = Object.keys(a).map(function(k){return encodeURIComponent(k) + '=' + encodeURIComponent(a[k])}).join('&');
        //params = $.param(__params);
        for(var k in __params){
        	params += encodeURIComponent(k) + '=' + encodeURIComponent(__params[k]) + '&'
        }
        iurl = __url+((params)?'?'+params:'');
        
        $iframeTmp = document.createElement("IFRAME");
        $iframeTmp.setAttribute('src',iurl);
        $iframeTmp.setAttribute('id',__params_zone);
        $iframeTmp.setAttribute('frameborder',0);
        $iframeTmp.setAttribute('marginwidth',0);
        $iframeTmp.setAttribute('marginheight',0);
        $iframeTmp.setAttribute('paddingwidth',0);
        $iframeTmp.setAttribute('paddingheight',0);
        $iframeTmp.setAttribute('width',__params['size_width']);
        $iframeTmp.setAttribute('height',__params['size_height']);
        $iframeTmp.setAttribute('scrolling','no');
        $adp.appendChild($iframeTmp);
        
    }//for end
    
    
}
var checkLoad0999 = function() {   
    document.readyState !== "complete" ? setTimeout(checkLoad0999, 11) : adopRun001();   
};  

checkLoad0999();  
