var criteoZoneId = 1361905;
var criteoSlotId = "criteo_slot_1361905";
var passback = function () {
var width = "300px", height = "250px";
var div = document.getElementById('t200');
if (div) {
var ifr = document.createElement("iframe");
ifr.setAttribute("id", criteoSlotId+"_iframe"),
ifr.setAttribute("frameborder","0"),
ifr.setAttribute("allowtransparency","true"),
ifr.setAttribute("hspace","0"),
ifr.setAttribute("marginwidth","0"),
ifr.setAttribute("marginheight","0"),
ifr.setAttribute("scrolling","no"),
ifr.setAttribute("vspace","0"),
ifr.setAttribute("width", width),
ifr.setAttribute("height", height);
div.appendChild(ifr);
var htmlcode = "<scr"+"ipt async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></scr"+"ipt>";
htmlcode += "<scr"+"ipt>var googletag = googletag || {};googletag.cmd = googletag.cmd || [];</scr"+"ipt>";
htmlcode += "<scr"+"ipt>";
htmlcode += "googletag.cmd.push(function() {";
htmlcode += "googletag.defineSlot('/5932629/ca-pub-1474238860523410-tag/2320422152', [300, 250],'div-gpt-ad-1234567891234-0').addService(googletag.pubads());";
htmlcode += "googletag.pubads().addEventListener('slotRenderEnded', function(event) {";
htmlcode += "if(event.isEmpty){";
htmlcode += "alert('패스백 먹음');";
htmlcode += "}";
htmlcode += "});";
htmlcode += "googletag.pubads().set('page_url', 'portal.ado_p.co.kr');";
htmlcode += "googletag.enableServices();";
htmlcode += "try {";
htmlcode += "googletag.display('div-gpt-ad-1234567891234-0');";
htmlcode += "}";
htmlcode += "catch(err){";
htmlcode += "alert(err.message);";
htmlcode += "}";
htmlcode += "});";
htmlcode += "</scr"+"ipt>";
htmlcode += "<div id='div-gpt-ad-1234567891234-0' style='height:250px; width:300px;'></div>";

var ifrd = ifr.contentWindow.document;
ifrd.open();
ifrd.write(htmlcode);
ifrd.close();
}
}
passback();
