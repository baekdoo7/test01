<?php
ini_set('memory_limit','2048M');
ini_set('max_execution_time', 60 * 60 * 2);
date_default_timezone_set('UTC');
define('DB_HOST', 'compass-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com');

$com_conn = mysqli_connect(DB_HOST, 'adopadmin', 'Adop*^14', 'platon');
if(mysqli_connect_errno()){
    die("콤파스 디비연결 실패!!".mysqli_connect_error());
}
mysqli_query($com_conn,"set names utf8");

$comSQL = " SELECT ia.area_idx, ia.site_idx, s.site_name
              FROM inventory_area ia
             INNER JOIN inventory_site s
                ON ia.site_idx = s.site_idx 
               AND ia.area_idx = ia.origin_areacd
               AND ia.del_yn = 'N'
               AND s.del_yn = 'N'";
$area_list_tmp = mysqli_query($com_conn, $comSQL);
$area_list = $area_list_tmp->fetch_all(MYSQLI_ASSOC);

$str_json="";
$j_row = "";
foreach ($area_list as $row){
    $j_row = $j_row.json_encode($row)."\n";
}

//require_once 'aws/aws-autoloader.php';
require_once '/Data/aws/aws-autoloader.php';
$client = new Aws\S3\S3Client([
    'version' => 'latest',
    'region' => 'ap-northeast-2',
    'credentials' => [
        'key' => 'AKIAIXJZL4X2FQWIQJUQ',
        'secret' => '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',
    ]
]);
$client->putObject(array(
    'Bucket' => 'adop-dmp',
    'Key' => sprintf("Data/common/join/area_site_info/area_site_info.json"),
    'Body' => $j_row,
    'ACL' => 'public-read'
));
//echo "<pre>";
//var_dump($area_site_list);
//exit;
