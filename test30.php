
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>videoAD Testing</title>
</head>
<body id='test' style='padding:0; margin:0;'>

    <br />
    <br />
    <br />
    <br />
    <br />
        Testing.......
    <br />
    <br />
    <br />
    <br />
<?php
/*    
    $test001 = " <script type=\"text/javascript\" src=\"//static.criteo.net/js/ld/publishertag.js\"></script>
<script type=\"text/javascript\">
Criteo.DisplayAd({
    \"zoneid\": 257990,
    \"async\": false});
</script>         ";
    
 echo make_script2tag($test001);
    
function make_script2tag($str){
    $retVal = "";   
    $str001 = explode("\n",$str);
    foreach($str001 as $k => $v){        
        $vtmp = "";
        $vtmp = str_replace("\"","\\\\\\\"",$v);
        $vtmp = str_replace("</script","<\\/script",$vtmp);
        
        $vtmp = "ducument.write(\\\" " .$vtmp. "     \\\");\n";
        $retVal .= $vtmp;
    }
    return $retVal;
} 
*/
?>    
<br />    
<iframe src="about:blank" ></iframe>

    <br />    
    <br />    
    <br />    
    <br />    

<hr />     
<script type="text/javascript">
    
    /*
        var iframe = document.getElementsByTagName("iframe")[0];
        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        var iframeBody = iframeDoc.body;
    
        var scrAD =  "<script src='test01.js'></scr"+"ipt><script type='text/javascript'>console.log(document.referrer);</scr"+"ipt><div>안녕하세요...</div>";
    */
        
    
    /*
        //스크립트 돔으로 처리 하는 부분 가능
        var script = document.createElement('script');
            script.setAttribute('type', 'text/javascript');
            script.innerHTML = "console.log(123);"
      
    
        //iframeBody.innerHTML = scrAD;
        iframeBody.appendChild(script);
    */
        
    /*
        //텍스트로 처리 하는 부분
         var scrAD =  "<script src='test01.js'></scr"+"ipt><script type='text/javascript'>console.log(document.referrer);document.write('안녕하세요 이것은 자바스크립트로 찍은 라인 입니다..... <br /><script>console.log(\"오징어\")</sc'+'ript>');</scr"+"ipt><div>안녕하세요...</div>";
    
        iframeDoc.open();
        iframeDoc.write(scrAD);
        iframeDoc.close();
    */

    
        
    
function passback_exec(url){
    
    //아이프레임 변수 및 아이프레임 네이밍 
    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    var strIframe     = "<iframe id='"+strIframeId+"' frameborder='0' marginwidth='0' marginheight='0' paddingwidth='0' paddingheight='0' scrolling='no' style='width: 100%; height: 100%;' ></iframe>"; //속성추가할것
    var strScriptLink = "<script src='"+url+"'><\/script>";
    var strScriptPgm  = "";
    
    document.write(strIframe);
    var passbackIframe = document.getElementById(strIframeId);
    var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
    if(passbackIframeDoc != null){
        passbackIframeDoc.open();
        passbackIframeDoc.write(strScriptLink);
        passbackIframeDoc.close();
    }
    
    /*
    strScriptPgm += "<script type='text/javascript'> \n";
    strScriptPgm += "var passbackIframe = document.getElementById('"+strIframeId+"'); \n";
    strScriptPgm += "var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document; \n";
    strScriptPgm += "if(passbackIframeDoc != null){ \n";
    strScriptPgm += "passbackIframeDoc.open(); \n";
    strScriptPgm += "passbackIframeDoc.write(\"" + strScriptLink+ "\" ); \n";
    strScriptPgm += "passbackIframeDoc.close(); \n";
    strScriptPgm += "} \n";
    strScriptPgm += "<\/script> \n";
    */
    //console.log (strScriptPgm);    
    
    
    //document.write(strScriptPgm);
    //document.write(strIframe);
    
    
 
    
    
    
   
    
       
        
        
    
    
}    
</script>
    
    
    <br />    
    <br />    
    <br />    
    <br />    
<hr />
    <div style="width:300px;height:300px;">
    
<script>    
    passback_exec("http://www.test01.com/test02.js");
</script>
    </div>
<hr />
<div style="width:300px;height:250px;">
    <script>
        passback_exec("http://compasstest.adop.cc/RE/5849645b-be6b-4c20-84ff-02089dc0439b");
    </script>    
</div>    
    
</body>
</html>


