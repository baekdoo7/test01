<?php
require 'class.JavaScriptPacker2.php';

	$src            = trim(stripslashes($_REQUEST[src]));
	$ascii_encoding = trim($_REQUEST[ascii_encoding]);
	$fast_decode    = trim($_REQUEST[fast_decode]);
	$special_char   = trim($_REQUEST[special_char]);
	
	$t1             = microtime(true);	
	$packer = new JavaScriptPacker($src,$ascii_encoding, $fast_decode=='on'?true:false,$special_char=='on'?true:false);
	$packed = $packer->pack();
	$t2 = microtime(true);
	$time = sprintf('%.4f', ($t2 - $t1) );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<script type="text/javascript">
function decode() {
  var packed = document.getElementById('packed');
  alert(packed.value.slice(4));
  eval("packed.value=String" + packed.value.slice(4));
}
  </script>
<style type="text/css">
#menu {
  padding: 0;
  margin: 0.4em;
}
#menu li {
  display: inline;
  margin: 0 0.5em;
  text-indent: 0;
}
/*h2 {
	font-size: 1.333em;
}*/
h2 span{
	font-size: 1em;
}
/*.h1bis {
  font-size: 0.625em;
}*/
.section div {
  clear: both;
}
.clear {
  clear: both;
}
.download {
  min-height: 22px;
  padding-right: 26px;
  padding: 0.4em 22px 0.4em 0.4em;
  background: url("images/fleche-download.png") no-repeat right center;
  border: 1px #F9FAFC solid;
  color: blue;
  text-decoration: underline;
}
.download:hover {
  border: 1px #001477 solid;
  background: #DAE6FC url("images/fleche-download-over.png") no-repeat right center;
  text-decoration: none;
  font-weight: bold;
}

#demo textarea {
  width: 100%;
}
.result {
  border: 1px blue dashed;
  color: black;
  background-color: #e5e5e5;
  padding: 0.2em;
}
#related ul {
 margin-top: 1em;
}
#related li {
 margin: 0.5em 0 0.7em 4em ;
}
.note {
  margin: 0.5em;
}
</style>
</head>
<body>

  <div class="section" id="demo">
    <h3><span>Demo</span></h3>
    <form action="packing2.php" method="post">
      <div>
        <h4>script to pack:</h4>
        <textarea name="src" id="src" rows="10" cols="80"><?=$src?></textarea>
      </div>
      <div>
        <label for="ascii-encoding">Encoding:</label>
        <select name="ascii_encoding" id="ascii-encoding">
          <option value="0"  <?=$ascii_encoding=='0'?'selected':''?>>None</option>
          <option value="10" <?=$ascii_encoding=='10'?'selected':''?>>Numeric</option>
          <option value="62" <?=$ascii_encoding=='62'?'selected':''?>>Normal</option>
          <option value="95" <?=$ascii_encoding=='95'?'selected':''?>>High ASCII</option>
        </select>
        <label>
          Fast Decode:
          <input type="checkbox" name="fast_decode" id="fast-decode" <?=$fast_decode=='on'?'checked':''?>>
        </label>
        <label>
          Special Characters:
          <input type="checkbox" name="special_char" id="special-char" <?=$special_char=='on'?'checked':''?>>
        </label>
        <input type="submit" value="Pack">
      </div>
      <!-- </fieldset> -->
    </form>
    
        <div id="result">
      <h4>packed result:</h4>
      <textarea id="packed" class="result" rows="10" cols="80" readonly01="readonly"><?=$packed?></textarea>
      <p>
        compression ratio:
        <?=strlen($packed)?>/<?=strlen($src)?> = <?=round(strlen($packed)/strlen($src),3)?>        <br>performed in <?=$time?> s.
      </p>
      <p><button type="button" onclick="decode()">decode</button></p>
    </div>
        
    <p class="note"><em>note : Absolutely no data is stored on the server.</em></p>
  </div>
</body>  
</html>