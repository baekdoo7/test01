#!/usr/bin/php -q

<?php
	$dir_path = '로그파일의 패스';
	$file_list = getFileList($dir_path);

	foreach ($file_list as $file) {
		if(isMatchFile($file)) {
            $file_date = str_replace("catalina.out.", "", $file);

            if (isPastDate($file_date)) {
                $full_path = $dir_path."/".$file;
                exec("gzip ".$full_path." ");
           }
		}
	}

	function getFileList($path) {
		$files = array();
		if($dir = opendir($path)) {
			while (($file = readdir($dir)) == true) {
				if ($file != '.' && $file != '..') {
					array_push($files, $file);
				}
			}
			closedir($dir);
		}

		return $files;
	}
	
	function isMatchFile($fileName) {
		if (preg_match("/^catalina\.out\.[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fileName)) {
			return true;
		} else {
			return false;
		}
	}
	
	function isPastDate($fileDate) {
        date_default_timezone_set("Asia/Tokyo");
        $today = date("Y-m-d");
        
        if (strtotime($fileDate) < strtotime($today)) {
            return true;
        } else {
            return false;
        }
	}
?>