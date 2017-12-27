
/*
target_bd = "";
PREBID_TIMEOUT = 2000;
var pbjs = pbjs || {};
pbjs.que2 =  pbjs.que2 || {};
pbjs.que = pbjs.que || [];


//광고 스탑
pbjs.stopad02 = true;
//광고 콜백 처리
function floorPrice001(bidResponses) {
    if(pbjs.adopcalled) return;
    pbjs.adopcalled = true;
    var targetingParams = pbjs.getAdserverTargeting();
    target_bd = targetingParams;
    for(var areaid in target_bd){
        if(pbjs.floor_price <= target_bd[areaid].hb_pb){
            pbjs.que2[areaid] = target_bd[areaid].hb_adid;
        }
    }
    sendAdserverRequest();

}
//광고시작함수
function sendAdserverRequest(){
    if(pbjs.adserverRequestSent) return;
    pbjs.adserverRequestSent = true;
    pbjs.stopad02 = false;
    showAlladopAd01(false);
}
//타임아웃 후 기본 광고 노출
setTimeout(function(){floorPrice001(null);}, PREBID_TIMEOUT);
*/


eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('3="";8=o;9 2=2||{};2.5=2.5||{};2.c=2.c||[];2.f=!0;4 e(b){i(!2.6){2.6=!0;3=2.h();g(9 a k 3)2.p<=3[a].l&&(2.5[a]=3[a].m);d()}}4 d(){2.7||(2.7=!0,2.f=!1,n(!1))}q(4(){e(j)},8);',27,27,'||pbjs|target_bd|function|que2|adopcalled|adserverRequestSent|PREBID_TIMEOUT|var|||que|sendAdserverRequest|floorPrice001|stopad02|for|getAdserverTargeting|if|null|in|hb_pb|hb_adid|showAlladopAd01|2E3|floor_price|setTimeout'.split('|'),0,{}))
