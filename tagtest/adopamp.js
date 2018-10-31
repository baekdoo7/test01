var adopamp  = adopamp || {};
adopamp.code =  adopamp.code || [];
adopamp.url  =  adopamp.url || [];

// url 주소를 base64/urlencode로 인코딩
var d = function(d){
    return encodeURIComponent(btoa(d));
}

//adopamp2.js 를 동적으로 호출
var b = function(){
    var a = '//amp.adop.cc/js/adopamp2.php';

    var f = a+'?d='+d(location.href);
        c(f);
}

//amppage 주소를 메타링크로 만들어 동적 삽입
var e = function(e){
    var d = document.getElementsByTagName('head')[0];    
    var f = document.createElement('link');
    var s = 'https://amp.adop.cc/view/ampview.php?u=';
    
    if(adopamp.url.length > 0){
        s = adopamp.url[0]+'view/ampview.php?u=';            
    }
        f.rel = 'amphtml';
        f.href = s+e;
        d.appendChild(f);
        //adopamp.code.pop();
    
}
//amppage 지정된 주소로 변경 처리
var h = function(e){
    var o = document.getElementsByTagName('link');
    for(var i=0;i<o.length;i++){
        //console.log(o[i].rel)
        if(o[i].rel == 'amphtml'){
            o[i].href = o[i].href.replace('https://amp.adop.cc/',e);
        }
    }
}
//배열 이벤트 체크
var a = function(a, k) {
    a.push = function(e) {
        Array.prototype.push.call(a, e);
        k(a);
        };
    };
    a(adopamp.code,e);
    a(adopamp.url,h);

//동적 자바스크립트 로딩
var c = function(c){
    var d = document.getElementsByTagName('head')[0];    
    var e = document.createElement('script');
        e.type = 'text/javascript';
        e.src = c;
        d.appendChild(e);
}

//배열 체크후 실행
var g = function(){
    if(typeof(adopamp.code) != 'undefined' && adopamp.code.length > 0 ){
        e(adopamp.code.pop());
    }else{
        b();
    }
}
g();
