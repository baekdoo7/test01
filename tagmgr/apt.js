/*
* <script src='http://compass.adop.cc/assets/js/adop/adop.js?v=14' ></script><ins class='adsbyadop' _adop_zon = '1ed0f68e-294d-431e-b51c-14f9608bb068' _adop_type = 'rs' style='display:inline-block;width:320px;height:50px;' _page_url='' _over_size='auto' _over_zone='{"468x60":"0ea72f32-ea0f-4e1b-a959-1f92367f8f03","728x90":"e9b139f6-84ea-485e-95bf-dec0aa3a939a"}' ></ins>
* */
var adoptag = adoptag || {};
adoptag.cmd = adoptag.cmd || [];
var adopHB = adopHB || {};
adopHB.que = adopHB.que || [];

target_bd = "";
PREBID_TIMEOUT = 3000;
var pbjs = pbjs || {};
pbjs.addCallback = function(a,b){return};
pbjs.allBidsAvailable = function(){return false};
pbjs.que2 =  pbjs.que2 || {};
pbjs.que = pbjs.que || [];

//광고 스탑
pbjs.stopad02 = true;


//비딩로그 전송 처리 함수
pbjs.sendingbidinfo = function (bidders,bidWinners) {

    if(bidders == null) return;
    var guid = adoptag._guid();
    for (var adunit in bidders) {
        if (bidders.hasOwnProperty(adunit)) {
            var bids = bidders[adunit].bids;
            for (var i = 0; i < bids.length; i++) {
                var b = bids[i];

                if(bidWinners[adunit] == b.adId){
                    var winned = true;
                }
                else{
                    var winned = false;
                }
                //var urltmp = "//dmp3.adop.cc/hb?ai="+adunit+"&b="+b.bidder+"&t="+b.timeToRespond+"&c="+b.cpm+"&ui="+guid+"&w="+winned;

                var urltmp = "//data.adop.cc/collect.php?log=hb&ai="+adunit+"&b="+b.bidder+"&t="+b.timeToRespond+"&c="+b.cpm+"&ui="+guid+"&w="+winned;
                //이미지 삽입
                if(urltmp != "") {
                    var head = document.getElementsByTagName("head");
                    var img  = document.createElement("img");
                    img.height = 0;
                    img.width = 0;
                    img.src = urltmp;
                    head[0].appendChild(img);
                }

            }
        }
    }
}


//광고 콜백 처리
adoptag.floorPrice001 = function (bidResponses) {
    if(pbjs.adopcalled) return;
    pbjs.adopcalled = true;

    var targetingParams = pbjs.getAdserverTargeting();
    target_bd = targetingParams;
    for(var areaid in target_bd){
        if(pbjs.floor_price <= target_bd[areaid].hb_pb){
            pbjs.que2[areaid] = target_bd[areaid].hb_adid;
        }
    }
console.log(pbjs.que2);
    //로깅전송 체크
    if(pbjs.bidtrace) {
        setTimeout(function () {
            pbjs.sendingbidinfo(pbjs.getBidResponses(), pbjs.que2);
        }, 1000);
    }
    //광고 노출
    adoptag.sendAdserverRequest();

}
adoptag.sendAdserverRequest = function () {

    if(pbjs.adserverRequestSent) return;
    pbjs.adserverRequestSent = true;
    pbjs.stopad02 = false;
    adoptag.showAlladopAd01(false);

}
//guid
adoptag._guid = function () {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}
//기초정보 초기화
adoptag.init = function () {
    adoptag.pageUrl  = window.location.href;
    adoptag.referrer = document.referrer;
    adoptag.screenWidth = window.innerWidth -16;
}
//광고영역 정보 설정 A:iframe B:noframe
adoptag.defineSlot = function (cd, size, itype) {
    adoptag[cd] = {};
    adoptag[cd].width   = size[0];
    adoptag[cd].height  = size[1];
    adoptag[cd].react   = false;
    adoptag[cd].itype   = itype;
    adoptag[cd].hbd     = false;
}
//광고영역 정보 설정 A:iframe B:noframe adinfo : 객체
adoptag.defineSlotR = function (adinfo, itype) {
    if(typeof(adinfo) != 'object'){
        return false;
    }

    var sortedadinfo = adoptag.sortReactTag(adinfo);

    if(sortedadinfo.length > 0){
        var ad = sortedadinfo.shift();

        adoptag[ad.cd] = {};
        adoptag[ad.cd].width   = ad.w;
        adoptag[ad.cd].height  = ad.h;
        adoptag[ad.cd].multi   = sortedadinfo;
        adoptag[ad.cd].react   = true;
        adoptag[ad.cd].itype   = itype;
        adoptag[ad.cd].hbd     = false;
    }
}
//헤더비딩 광고영역 정보 설정 A:iframe B:noframe
adoptag.defineSlotH = function (cd, size, itype) {
    adoptag[cd] = {};
    adoptag[cd].width   = size[0];
    adoptag[cd].height  = size[1];
    adoptag[cd].react   = false;
    adoptag[cd].itype   = itype;
    adoptag[cd].hbd     = true;
}
//비동기 커맨드 처리
adoptag.runcmd = function () {
    while (adoptag.cmd.length){
        (adoptag.cmd.shift())();
    }
    return this;
}
//객체 정보 받아 배열로 리턴
adoptag.sortReactTag = function (rInfo) {
    var retArray = [];
    var tmp = {};
    if(typeof(rInfo) != 'object' ){
        return [];
    }
    for(var key in rInfo){
        var keyTmp = key.split("x",2);
        tmp[keyTmp[0]] = JSON.parse('{"w":"'+keyTmp[0]+'","h":"'+keyTmp[1]+'","cd":"'+rInfo[key]+'"}');

    }
    return Object.values(tmp);
}
//광고 영역 삽입
adoptag.display = function (areaidx) {

    var reactive = "",haderbidding="";

    if (typeof(adoptag[areaidx]) == "undefined") {
        return false;
    }
    if(adoptag[areaidx].react == false ){
        reactive = "A"; //일반형
    }
    else{
        reactive = "B"; //반응형
    }

    if(adoptag[areaidx].hbd == false ){
        haderbidding = "A"; //no hader bidding
    }
    else{
        haderbidding = "B"; //hader bidding
    }
//console.log(adoptag[areaidx].itype+reactive+haderbidding);
    switch (adoptag[areaidx].itype+reactive+haderbidding) {
        case 'AAA':
            //console.log('A');
            adoptag.insertFrame(areaidx);
            break;
        case 'BAA':
            //console.log('B');
            adoptag.insertNoFrame(areaidx);
            break;
        case 'ABA':
            //console.log('B');
            adoptag.insertFrameReact(areaidx);
            break;
        case 'BBA':
            //console.log('B');
            adoptag.insertNoFrameReact(areaidx);
            break;
        case 'AAB':
            //console.log('A');
            adoptag.insertFrameHb(areaidx);
            break;
        case 'BAB':
            //console.log('A');
            adoptag.insertNoFrameHb(areaidx);
            break;

        default :
            break;
    }
    return true;
}
//광고 프레임형 삽입 고정형
adoptag.insertFrame = function (areaidx) {
    var $iframeTmp,obj;
    var iurl = "//compass.adop.cc/RD/" + areaidx;

    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    $iframeTmp = document.createElement("IFRAME");
    $iframeTmp.setAttribute('src',iurl);
    $iframeTmp.setAttribute('id',strIframeId);
    $iframeTmp.setAttribute('areaidx',areaidx+"_");
    $iframeTmp.setAttribute('border','none');
    $iframeTmp.setAttribute('frameBorder',0);
    $iframeTmp.setAttribute('marginWidth',0);
    $iframeTmp.setAttribute('marginHeight',0);
    $iframeTmp.setAttribute('paddingWidth',0);
    $iframeTmp.setAttribute('paddingHeight',0);
    $iframeTmp.setAttribute('width',adoptag[areaidx].width);
    $iframeTmp.setAttribute('height',adoptag[areaidx].height);
    $iframeTmp.setAttribute('scrolling','no');
    obj = document.getElementById(areaidx);
    if(obj){
        obj.setAttribute('class','adsbyadop_');
        obj.appendChild($iframeTmp);
    }
}
//광고 노프레임 JS 고정형
adoptag.insertNoFrame = function (areaidx) {
    var $iframeTmp,obj;
    var iurl = '//compasstest.adop.cc/RE/' + areaidx;
    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    $iframeTmp = document.createElement("IFRAME");
    $iframeTmp.setAttribute('id',strIframeId);
    $iframeTmp.setAttribute('areaidx',areaidx+"_");
    $iframeTmp.setAttribute('border','none');
    $iframeTmp.setAttribute('frameBorder',0);
    $iframeTmp.setAttribute('marginWidth',0);
    $iframeTmp.setAttribute('marginHeight',0);
    $iframeTmp.setAttribute('paddingWidth',0);
    $iframeTmp.setAttribute('paddingHeight',0);
    $iframeTmp.setAttribute('width',adoptag[areaidx].width);
    $iframeTmp.setAttribute('height',adoptag[areaidx].height);
    $iframeTmp.setAttribute('scrolling','no');

    var strScriptLink = "<script src='"+iurl+"'><\/script>";

    obj = document.getElementById(areaidx);
    if(obj){
        obj.setAttribute('class','adsbyadop_');
        obj.appendChild($iframeTmp);
    }

    var passbackIframe = document.getElementById(strIframeId);
    var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
    if(passbackIframeDoc != null){
        passbackIframeDoc.open();
        passbackIframeDoc.write(strScriptLink);
        passbackIframeDoc.close();
    }

}
//광고 프레임형 삽입 반응형
adoptag.insertFrameReact = function (areaidx) {

    var $iframeTmp,obj,areaidxTmp,widthTmp,heightTmp;
    var iurl = "//compass.adop.cc/RD/";

    areaidxTmp = areaidx;
    widthTmp   = adoptag[areaidx].width;
    heightTmp  = adoptag[areaidx].height;

    for(var i = 0 ; i < adoptag[areaidx].multi.length;i++){
        if(Number(adoptag[areaidx].multi[i].w) <= adoptag.screenWidth){
            areaidxTmp = adoptag[areaidx].multi[i].cd;
            widthTmp   = adoptag[areaidx].multi[i].w;
            heightTmp  = adoptag[areaidx].multi[i].h;

        }
    }
    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    iurl = iurl + areaidxTmp;
    $iframeTmp = document.createElement("IFRAME");
    $iframeTmp.setAttribute('src',iurl);
    $iframeTmp.setAttribute('id',strIframeId);
    $iframeTmp.setAttribute('areaidx',areaidxTmp+"_");
    $iframeTmp.setAttribute('border','none');
    $iframeTmp.setAttribute('frameBorder',0);
    $iframeTmp.setAttribute('marginWidth',0);
    $iframeTmp.setAttribute('marginHeight',0);
    $iframeTmp.setAttribute('paddingWidth',0);
    $iframeTmp.setAttribute('paddingHeight',0);
    $iframeTmp.setAttribute('width',widthTmp);
    $iframeTmp.setAttribute('height',heightTmp);
    $iframeTmp.setAttribute('scrolling','no');
    obj = document.getElementById(areaidx);
    if(obj){
        obj.setAttribute('class','adsbyadop_');
        obj.appendChild($iframeTmp);

    }
}
//광고 노프레임 JS 반응형
adoptag.insertNoFrameReact = function (areaidx) {
    var $iframeTmp,obj,areaidxTmp,widthTmp,heightTmp;
    var iurl = '//compasstest.adop.cc/RE/';

    areaidxTmp = areaidx;
    widthTmp   = adoptag[areaidx].width;
    heightTmp  = adoptag[areaidx].height;

    for(var i = 0 ; i < adoptag[areaidx].multi.length;i++){
        if(Number(adoptag[areaidx].multi[i].w) <= adoptag.screenWidth){
            areaidxTmp = adoptag[areaidx].multi[i].cd;
            widthTmp   = adoptag[areaidx].multi[i].w;
            heightTmp  = adoptag[areaidx].multi[i].h;

        }
    }
    iurl = iurl + areaidxTmp;
    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    //var strIframeId = areaidxTmp + "_";
    $iframeTmp = document.createElement("IFRAME");
    $iframeTmp.setAttribute('id',strIframeId);
    $iframeTmp.setAttribute('areaidx',areaidxTmp+"_");
    $iframeTmp.setAttribute('border','none');
    $iframeTmp.setAttribute('frameBorder',0);
    $iframeTmp.setAttribute('marginWidth',0);
    $iframeTmp.setAttribute('marginHeight',0);
    $iframeTmp.setAttribute('paddingWidth',0);
    $iframeTmp.setAttribute('paddingHeight',0);
    $iframeTmp.setAttribute('width',widthTmp);
    $iframeTmp.setAttribute('height',heightTmp);
    $iframeTmp.setAttribute('scrolling','no');

    var strScriptLink = "<script src='"+iurl+"'><\/script>";

    obj = document.getElementById(areaidx);
    if(obj){
        obj.setAttribute('class','adsbyadop_');
        obj.appendChild($iframeTmp);
    }

    var passbackIframe = document.getElementById(strIframeId);
    console.log(passbackIframe);
    var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
    if(passbackIframeDoc != null){
        passbackIframeDoc.open();
        passbackIframeDoc.write(strScriptLink);
        passbackIframeDoc.close();
    }

}
//광고 프레임형 삽입 헤더비딩형
adoptag.insertFrameHb = function (areaidx) {
    var $iframeTmp,obj;
    var iurl = "//compass.adop.cc/RD/" + areaidx;

    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    $iframeTmp = document.createElement("IFRAME");
    $iframeTmp.setAttribute('id',strIframeId);
    $iframeTmp.setAttribute('areaidx',areaidx+"_");
    $iframeTmp.setAttribute('border','none');
    $iframeTmp.setAttribute('frameBorder',0);
    $iframeTmp.setAttribute('marginWidth',0);
    $iframeTmp.setAttribute('marginHeight',0);
    $iframeTmp.setAttribute('paddingWidth',0);
    $iframeTmp.setAttribute('paddingHeight',0);
    $iframeTmp.setAttribute('width',adoptag[areaidx].width);
    $iframeTmp.setAttribute('height',adoptag[areaidx].height);
    $iframeTmp.setAttribute('scrolling','no');
    obj = document.getElementById(areaidx);
    if(obj){
        obj.setAttribute('class','adsbyadop_');
        obj.appendChild($iframeTmp);
    }

    //영역 정보 큐에 넣기
    var frameObj = {};
    frameObj.frameId = strIframeId;
    frameObj.scriptLink = iurl;
    adopHB.que[areaidx] = frameObj;
}
//광고 노프레임 삽입 헤더비딩형
adoptag.insertNoFrameHb = function (areaidx) {
    var $iframeTmp,obj;
    var iurl = "//compass.adop.cc/RE/" + areaidx;

    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    $iframeTmp = document.createElement("IFRAME");
    $iframeTmp.setAttribute('id',strIframeId);
    $iframeTmp.setAttribute('areaidx',areaidx+"_");
    $iframeTmp.setAttribute('border','none');
    $iframeTmp.setAttribute('frameBorder',0);
    $iframeTmp.setAttribute('marginWidth',0);
    $iframeTmp.setAttribute('marginHeight',0);
    $iframeTmp.setAttribute('paddingWidth',0);
    $iframeTmp.setAttribute('paddingHeight',0);
    $iframeTmp.setAttribute('width',adoptag[areaidx].width);
    $iframeTmp.setAttribute('height',adoptag[areaidx].height);
    $iframeTmp.setAttribute('scrolling','no');
    obj = document.getElementById(areaidx);
    if(obj){
        obj.setAttribute('class','adsbyadop_');
        obj.appendChild($iframeTmp);
    }

    //영역 정보 큐에 넣기
    var frameObj = {};
    frameObj.frameId = strIframeId;
    frameObj.scriptLink = "<script src='"+iurl+"'><\/script>";;
    adopHB.que[areaidx] = frameObj;

}
//헤더비딩 광고 노출
adoptag.showAlladopAd01 = function (viewing) {
console.log(viewing);
    if(viewing){
        return ;
    }
    if(typeof(pbjs) == "undefined" || typeof(pbjs.que2) == "undefined"){
        for(var areaId in adopHB.que){
            if(adoptag[areaId] == undefined){
                adoptag.adopHBshowNoFrame(areaId);
                continue;
            }
            if(adoptag[areaId].itype == 'A')
                adoptag.adopHBshowFrame(areaId);
            else
                adoptag.adopHBshowNoFrame(areaId);
        }
    }
    else{
        for(var areaId in adopHB.que){
            if(typeof(pbjs.que2[areaId]) == "undefined"){
                if(adoptag[areaId] == undefined){
                    adoptag.adopHBshowNoFrame(areaId);
                    continue;
                }
                if(adoptag[areaId].itype == 'A')
                    adoptag.adopHBshowFrame(areaId);
                else
                    adoptag.adopHBshowNoFrame(areaId);
            }
            else{
                if(adoptag[areaId] == undefined){
                    adoptag.adopHBshowNoFrame2(areaId);
                    continue;
                }
                if(adoptag[areaId].itype == 'A')
                    adoptag.adopHBshowFrame2(areaId);
                else
                    adoptag.adopHBshowNoFrame2(areaId);
            }
        }
    }
}
//헤더비딩 프레임형 광고노출(adop ad)
adoptag.adopHBshowFrame = function (invenCode) {
  console.log(invenCode+'_1');
    var passbackIframe = document.getElementById(adopHB.que[invenCode].frameId);
        passbackIframe.setAttribute('src',adopHB.que[invenCode].scriptLink);
console.log(passbackIframe.contentDocument.location);
}


//헤더비딩 프레임형 광고노출(prebid ad)
adoptag.adopHBshowFrame2 = function (invenCode) {
    console.log(invenCode+'_2');
    var passbackIframe = document.getElementById(adopHB.que[invenCode].frameId);
    var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
    if(passbackIframeDoc != null){
        pbjs.renderAd(passbackIframeDoc,pbjs.que2[invenCode]);
    }
}
//헤더비딩 노프레임형 광고노출(adop ad)
adoptag.adopHBshowNoFrame = function (invenCode) {
    console.log(invenCode+'_3');
    var passbackIframe = document.getElementById(adopHB.que[invenCode].frameId);
    var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
    if(passbackIframeDoc != null){
        passbackIframeDoc.open();
        passbackIframeDoc.write(adopHB.que[invenCode].scriptLink);
        passbackIframeDoc.close();
    }
}
//헤더비딩 노프레임형 광고노출(prebid ad)
adoptag.adopHBshowNoFrame2 = function (invenCode) {
    console.log(invenCode+'_4');
    var passbackIframe = document.getElementById(adopHB.que[invenCode].frameId);
    var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
    if(passbackIframeDoc != null){
        pbjs.renderAd(passbackIframeDoc,pbjs.que2[invenCode]);
    }
}


//배열푸시 재정의
adoptag.definepush = function (a,k) {
    a.push = function(e){
        Array.prototype.push.call(a,e);
        k(a);
    }
}
//타임아웃 후 기본 광고 노출
setTimeout(function(){adoptag.floorPrice001(null);}, PREBID_TIMEOUT);

adoptag.init();
adoptag.definepush(adoptag.cmd,adoptag.runcmd);
adoptag.runcmd();