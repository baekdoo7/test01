<?php
    $adTime = date("YmdHis",time());
    $guid   = uniqid();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Treasure Data Testing...</title>


</head>
<body>
 테스트 <br />
<!-- Treasure Data -->
<script type="text/javascript">
 !function(t,e){if(void 0===e[t]){e[t]=function(){e[t].clients.push(this),this._init=[Array.prototype.slice.call(arguments)]},e[t].clients=[];for(var r=function(t){return function(){return this["_"+t]=this["_"+t]||[],this["_"+t].push(Array.prototype.slice.call(arguments)),this}},s=["addRecord","set","trackEvent","trackPageview","trackClicks","ready"],a=0;a<s.length;a++){var c=s[a];e[t].prototype[c]=r(c)}var n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=("https:"===document.location.protocol?"https:":"http:")+"//cdn.treasuredata.com/sdk/1.8.4/td.min.js";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(n,i)}}("Treasure",this);
</script>    
<script type="text/javascript">
  // Configure an instance for your database
  var td = new Treasure({
    host: 'in.treasuredata.com',
    writeKey: '9441/531f4249e6fcae47b248e42c8f79ad8cd203d9e7',
    database: 'testdb1'
  });
  // Enable cross-domain tracking
  td.set('$global', 'td_global_id', 'td_global_id');
  td.set('pageviews', {ad_date: '<?=$adTime?>',adopid:'<?=$guid?>'});    
  td.trackPageview('pageviews');
  
   
</script>
 <br />
 <br /> 
</body>
</html>