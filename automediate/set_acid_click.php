<?php
ini_set('memory_limit','2048M');
ini_set('max_execution_time', 60 * 60 * 2);
date_default_timezone_set('UTC');

$redis_host = "redis-test.jr3ryi.0001.apn2.cache.amazonaws.com";
$redis_port = 6379;

$now = date('YmdHis');
$job_tar_date = date('Y-m-d H:i:s', strtotime("+9 hours"));

//require_once '../aws/aws-autoloader.php';
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
/* 클릭 로그에서 */
$tar_idx = '737'; // 대출카페대부중개

//$sdate = date('Ymd', strtotime("-1 days"));
$sdate = date('Ymd');
$year = substr($sdate, 0, 4);
$month = substr($sdate, 4, 2);
$day = substr($sdate, 6, 2);

$sel = $client->StartQueryExecution(
    array(
        "QueryExecutionContext"=> array( "Database"=> "log" ),
        "QueryString"=> "
                SELECT distinct a
                FROM atom_log2
                WHERE year = '".$year."'
                AND month = '".$month."'
                AND day = '".$day."'
		AND a != ''
                AND a is NOT null
                AND ta in ('737','776','834')
                ",
        "ResultConfiguration"=> array(
            "EncryptionConfiguration"=> array("EncryptionOption"=> "SSE_S3"),
            "OutputLocation"=> "s3://aws-athena-query-results-441075904564-ap-northeast-2"
        )
    )
);
$i = 0;
while(++$i<=10)
{
    sleep(10);
    $result = $client->getQueryExecution(array('QueryExecutionId' => $sel->get('QueryExecutionId')));
    $res = $result->toArray();

    if($res['QueryExecution']['Status']['State']=='FAILED')
    {
        echo 'Query Failed';
        die;
    }
    else if($res['QueryExecution']['Status']['State']=='CANCELED')
    {
        echo 'Query was cancelled';
        die;
    }
    else if($res['QueryExecution']['Status']['State']=='SUCCEEDED')
    {
        break; // break while loop
    }else{
        if($i==10){
            var_dump($result);
        }
    }

}
$job_exe_id = $sel->get('QueryExecutionId');
$result1 = $client->GetQueryResults(array(
    'QueryExecutionId' => $sel->get('QueryExecutionId')
));

$get_rst[0] =  $result1->toArray();
for($i=0; $i<=50; $i++){
    if(isset($get_rst[$i]['NextToken'])){
        $get_rst[$i+1] = $client->GetQueryResults(array(
            'QueryExecutionId' => $job_exe_id,
            'NextToken' => $get_rst[$i]['NextToken']
        ));
    }else{
        break;
    }
}
$vals_tmp = "";
$cnt = 0;
try {
    $redis = new Redis();
    $redis->connect($redis_host, $redis_port, 1000);

    $m = 776;
    $m_arr = array();
    $info = array('freq'=>5, 'dt'=>$now);
    $info_json = json_encode($info);

    for($i=0; $i<count($get_rst); $i++){
        foreach ($get_rst[$i]['ResultSet']['Rows'] as $key=>$row){
            if($i == 0 && $key == 0){
                $column_tmp[0] = 'a';//$row['Data'][0]['VarCharValue']
            }else{
                foreach ($row as $row2){
                    $m_arr[$row2[0]['VarCharValue']."|".$m] = $info_json;
                }
            }
        }
    }
    $redis->mset($m_arr);

    $cnt = count($m_arr);
}catch(Exception $e) {
    die($e->getMessage());
}

$_LOGDB = mysqli_connect("compass-log-all-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "adop_dmp");
if ($_LOGDB->connect_errno) {
    die('Log-all DB Connect Error: ' . $_LOGDB->connect_error);
}

$did = $m;
$job_name = 'atom_log';
$job_end_date = date('Y-m-d H:i:s', strtotime("+9 hours"));
$logSQL = " INSERT INTO audience_cron_log (deal_id, job_name, job_exe_id, cnt, job_start_date, job_end_date) VALUE ('".$did."', '".$job_name."', '".$job_exe_id."', '".$cnt."', '".$job_tar_date."','".$job_end_date."')";
$_LOGDB->query($logSQL);
$_LOGDB->close();
