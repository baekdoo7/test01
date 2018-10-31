<?php

ini_set('memory_limit','2048M');
ini_set('max_execution_time', 60 * 60 * 2);
date_default_timezone_set('UTC');

$year = date('Y', strtotime('+9 hours'));
$month = date('m', strtotime('+9 hours'));
$day = date('d', strtotime('+9 hours'));
$hour = date('H', strtotime('+9 hours'));


$sql2 = "ALTER TABLE atom_log2 ADD 
PARTITION (year = '".$year."', month='".$month."', day='".$day."',hour='".$hour."',type='clk') LOCATION 's3://adop-dmp/Data/log/atom/year=".$year."/month=".$month."/day=".$day."/hour=".$hour."/type=clk'
PARTITION (year = '".$year."', month='".$month."', day='".$day."',hour='".$hour."',type='req') LOCATION 's3://adop-dmp/Data/log/atom/year=".$year."/month=".$month."/day=".$day."/hour=".$hour."/type=req'
PARTITION (year = '".$year."', month='".$month."', day='".$day."',hour='".$hour."',type='non') LOCATION 's3://adop-dmp/Data/log/atom/year=".$year."/month=".$month."/day=".$day."/hour=".$hour."/type=non'
PARTITION (year = '".$year."', month='".$month."', day='".$day."',hour='".$hour."',type='imp') LOCATION 's3://adop-dmp/Data/log/atom/year=".$year."/month=".$month."/day=".$day."/hour=".$hour."/type=imp'";


require_once '/Data/aws/aws-autoloader.php';
use Aws\Athena\AthenaClient;
$client = AthenaClient::factory(array(
    'version' => 'latest',
    'region' => 'ap-northeast-2',
    'credentials' => array(
        'key' => 'AKIAIXJZL4X2FQWIQJUQ',
        'secret' => '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'
    )
));


$sel = $client->StartQueryExecution(
    array(
        "QueryExecutionContext" => array("Database" => "log"),
        "QueryString" => $sql2,
        "ResultConfiguration" => array(
            "EncryptionConfiguration" => array("EncryptionOption" => "SSE_S3"),
            "OutputLocation" => "s3://aws-athena-query-results-441075904564-ap-northeast-2"
        )
    )
);

