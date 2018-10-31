<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 9. 4.
 * Time: PM 3:24
 */


// Turn off output buffering
ini_set('output_buffering', 'off');
// Turn off PHP output compression
ini_set('zlib.output_compression', false);

//Flush (send) the output buffer and turn off output buffering
while (@ob_end_flush());

// Implicitly flush the buffer(s)
ini_set('implicit_flush', true);
ob_implicit_flush(true);

//prevent apache from buffering it for deflate/gzip
//header("Content-type: text/plain");
// recommended to prevent caching of event data.
//header('Cache-Control: no-cache');

ob_start();

for ($i = 0; $i < 5; $i++) {
	echo $i;
	echo "<br>";
	echo str_pad('', 4096);

	ob_flush();
	flush();
	sleep(1);
}

ob_end_flush();


phpinfo();