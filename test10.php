<?php

function checkPII($str) {
    $vq = '/[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[^&$]+(\.)[^&$]+([^&$]+)*[^&$]+/';
    $str = preg_replace($vq,"",urldecode($str));
    return urlencode($str);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>
 테스트 <br />
 <script type="text/javascript">
 var a = "testing......(script)";
 //document.write(a);
// document.write("<br />");
 //document.write("<script src='http://www.test02.com/test10.js'></scr"+"ipt>");
 
 </script>
 
  
  <script type="application/javascript">
    var  aaa = "<h5>고등어\n \
      </h5>\"자갈치\" ";  
    document.write(aaa);  
  </script>
  <div id="testtest" kk=123 ></div>
 <br />
 <br />
<?php echo checkPII("http%3A%2F%2Fmbox12.korea.com%2Fmail%2FmailView.crd%3Fmsg_id%3D1504255977419107.0.mta02%7CINBOX%7Credacted%40example.com&rx=0&eae=2&brdim=0%2C0%2C0%2C0%2C1920%2C0%2C1920%2C1040%2C120%2C600&vis=1&rsz=%7C%7CcE%7C&abl=NS&ppjl=f&pfx=0&fu=16&bc=1&ifi=1&dtd=124 HTTP/1.1")?>    
<br />
<hr />
<script src="http://compasstest.adop.cc/ST/b687496e-4c81-4ba0-a83b-eaac150bee16"></script>    
</body>
</html>