<!DOCTYPE html>
<html>
<head>
</head>
<body>
  <h3>Facebook Audience Network for Mobile Web</h3>
  <hr/>
  <h3>Native Ad Unit</h3>
  <div style="display:none; position: relative;">
    <iframe style="display:none;"></iframe>
    <script type="text/javascript">
      var data = {
        placementid: '215629088783673_215635105449738',
        format: 'native',
        testmode: false,
        onAdLoaded: function(element) {
          console.log('Audience Network ad loaded');
          element.style.display = 'block';
        },
        onAdError: function(errorCode, errorMessage) {
          console.log('Audience Network error (' + errorCode + ') ' + errorMessage);
        }
      };
      (function(w,l,d,t){var a=t();var b=d.currentScript||(function(){var c=d.getElementsByTagName('script');return c[c.length-1];})();var e=b.parentElement;e.dataset.placementid=data.placementid;var f=function(v){try{return v.document.referrer;}catch(e){}return'';};var g=function(h){var i=h.indexOf('/',h.indexOf('://')+3);if(i===-1){return h;}return h.substring(0,i);};var j=[l.href];var k=false;var m=false;if(w!==w.parent){var n;var o=w;while(o!==n){var h;try{m=m||(o.$sf&&o.$sf.ext);h=o.location.href;}catch(e){k=true;}j.push(h||f(n));n=o;o=o.parent;}}var p=l.ancestorOrigins;if(p){if(p.length>0){data.domain=p[p.length-1];}else{data.domain=g(j[j.length-1]);}}data.url=j[j.length-1];data.channel=g(j[0]);data.width=screen.width;data.height=screen.height;data.pixelratio=w.devicePixelRatio;data.placementindex=w.ADNW&&w.ADNW.Ads?w.ADNW.Ads.length:0;data.crossdomain=k;data.safeframe=!!m;var q={};q.iframe=e.firstElementChild;var r='https://www.facebook.com/audiencenetwork/web/?sdk=5.3';for(var s in data){q[s]=data[s];if(typeof(data[s])!=='function'){r+='&'+s+'='+encodeURIComponent(data[s]);}}q.iframe.src=r;q.tagJsInitTime=a;q.rootElement=e;q.events=[];w.addEventListener('message',function(u){if(u.source!==q.iframe.contentWindow){return;}u.data.receivedTimestamp=t();if(this.sdkEventHandler){this.sdkEventHandler(u.data);}else{this.events.push(u.data);}}.bind(q),false);q.tagJsIframeAppendedTime=t();w.ADNW=w.ADNW||{};w.ADNW.Ads=w.ADNW.Ads||[];w.ADNW.Ads.push(q);w.ADNW.init&&w.ADNW.init(q);})(window,location,document,Date.now||function(){return+new Date;});
    </script>
    <script type="text/javascript" src="https://connect.facebook.net/en_US/fbadnw.js" async></script>
    <style>
      .thirdPartyRoot {
        background-color: white;
        color: #444;
        border: 1px solid #ccc;
        border-left: 0;
        border-right: 0;
        font-family: sans-serif;
        font-size: 14px;
        line-height: 16px;
        width: 320px;
        text-align: left;
        position: relative;
      }
      .thirdPartyMediaClass {
        width: 320px;
        height: 168px;
        margin: 12px 0;
      }
      .thirdPartySubtitleClass {
        font-size: 18px;
        -webkit-line-clamp: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        height: 16px;
        -webkit-box-orient: vertical;
      }
      .thirdPartyTitleClass {
        padding-right: 12px;
        line-height: 18px;
        -webkit-line-clamp: 2;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        height: 36px;
        -webkit-box-orient: vertical;
      }
      .thirdPartyCallToActionClass {
        background-color: #416BC4;
        color: white;
        border-radius: 4px;
        padding: 6px 20px;
        float: right;
        text-align: center;
        text-transform: uppercase;
        font-size: 11px;
      }
      .fbDefaultNativeAdWrapper {
        font-size: 12px;
        line-height: 14px;
        margin: 12px 0;
        height: 36px;
        vertical-align: top;
      }
    </style>
    <div class="thirdPartyRoot">
      <a class="fbAdLink">
        <div class="fbAdMedia thirdPartyMediaClass"></div>
        <div class="fbAdSubtitle thirdPartySubtitleClass"></div>
        <div class="fbDefaultNativeAdWrapper">
          <div class="fbAdCallToAction thirdPartyCallToActionClass"></div>
          <div class="fbAdTitle thirdPartyTitleClass"></div>
        </div>
      </a>
    </div>
  </div>
<div style="display:none; position: relative;">
  <iframe style="display:none;"></iframe>
  <script type="text/javascript">
    var data = {
      placementid: '1884922578386269_1884930691718791',
      format: '300x250',
      testmode: false,
      onAdLoaded: function(element) {
        console.log('Audience Network [1884922578386269_1884930691718791] ad loaded');
        element.style.display = 'block';
      },
      onAdError: function(errorCode, errorMessage) {
        console.log('Audience Network [1884922578386269_1884930691718791] error (' + errorCode + ') ' + errorMessage);
      }
    };
    (function(w,l,d,t){var a=t();var b=d.currentScript||(function(){var c=d.getElementsByTagName('script');return c[c.length-1];})();var e=b.parentElement;e.dataset.placementid=data.placementid;var f=function(v){try{return v.document.referrer;}catch(e){}return'';};var g=function(h){var i=h.indexOf('/',h.indexOf('://')+3);if(i===-1){return h;}return h.substring(0,i);};var j=[l.href];var k=false;var m=false;if(w!==w.parent){var n;var o=w;while(o!==n){var h;try{m=m||(o.$sf&&o.$sf.ext);h=o.location.href;}catch(e){k=true;}j.push(h||f(n));n=o;o=o.parent;}}var p=l.ancestorOrigins;if(p){if(p.length>0){data.domain=p[p.length-1];}else{data.domain=g(j[j.length-1]);}}data.url=j[j.length-1];data.channel=g(j[0]);data.width=screen.width;data.height=screen.height;data.pixelratio=w.devicePixelRatio;data.placementindex=w.ADNW&&w.ADNW.Ads?w.ADNW.Ads.length:0;data.crossdomain=k;data.safeframe=!!m;var q={};q.iframe=e.firstElementChild;var r='https://www.facebook.com/audiencenetwork/web/?sdk=5.3';for(var s in data){q[s]=data[s];if(typeof(data[s])!=='function'){r+='&'+s+'='+encodeURIComponent(data[s]);}}q.iframe.src=r;q.tagJsInitTime=a;q.rootElement=e;q.events=[];w.addEventListener('message',function(u){if(u.source!==q.iframe.contentWindow){return;}u.data.receivedTimestamp=t();if(this.sdkEventHandler){this.sdkEventHandler(u.data);}else{this.events.push(u.data);}}.bind(q),false);q.tagJsIframeAppendedTime=t();w.ADNW=w.ADNW||{};w.ADNW.Ads=w.ADNW.Ads||[];w.ADNW.Ads.push(q);w.ADNW.init&&w.ADNW.init(q);})(window,location,document,Date.now||function(){return+new Date;});
  </script>
  <script type="text/javascript" src="https://connect.facebook.net/en_US/fbadnw.js" async></script>
</div>

    
</body>
</html>