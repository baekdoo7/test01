<?php
ini_set('memory_limit','4096M');
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
/* Data 로그에서 - 레퍼러 */
$device = 'mobile';
//$sdate = date('Ymd', strtotime("-1 days"));
$sdate = date('Ymd');
$year = substr($sdate, 0, 4);
$month = substr($sdate, 4, 2);
$day = substr($sdate, 6, 2);
$sel = $client->StartQueryExecution(
    array(
        "QueryExecutionContext"=> array( "Database"=> "basic" ),
        "QueryString"=> "
                SELECT DISTINCT acid
                FROM hourly
                WHERE year = '".$year."'
                AND month = '".$month."'
                AND day = '".$day."'
                AND hour between '14' AND '23'
                AND dt is NOT null
                AND (ref LIKE '%m.dcinside.com/view.php?id=loan%'
                OR ref LIKE '%www.ajunews.com%'
                OR ref LIKE '%biz.chosun.com%'
                OR ref LIKE '%www.sporbiz.co.kr%'
                OR ref LIKE '%www.g-enews.com%'
                OR ref LIKE '%www.newspim.com%'
                OR ref LIKE '%www.tf.co.kr/news2/economy/section.ss%'
                OR ref LIKE '%m.dcinside.com%'
                OR ref LIKE '%www.mimint.co.kr%'
                OR ref LIKE '%www.sentv.co.kr%'
                OR ref LIKE '%www.consumernews.co.kr%'
                OR ref LIKE '%www.asiae.co.kr%'
                OR ref LIKE '%ekn.kr%'
                OR ref LIKE '%www.ezday.co.kr%'
                OR ref LIKE '%www.sisain.co.kr%'
                OR ref LIKE '%moneys.mt.co.kr%'
                OR ref LIKE '%www.dailycnc.com%'
                OR ref LIKE '%leaders.asiae.co.kr%'
                OR ref LIKE '%biz.heraldcorp.com%'
                OR ref LIKE '%www.womaneconomy.kr%')
                AND dev = '".$device."'
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
for($i=0; $i<=2000; $i++){
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

    $did = 776;
    $au_arr = array();
    $info = array('freq'=>5, 'dt'=>$now);
    $info_json = json_encode($info);

    for($i=0; $i<count($get_rst); $i++){
        foreach ($get_rst[$i]['ResultSet']['Rows'] as $key=>$row){
            if($i == 0 && $key == 0){
                $column_tmp[0] = 'acid';
            }else{
                foreach ($row as $row2){
                    $redis->set($row2[0]['VarCharValue']."|".$did, $info_json, 60*60*24*7);
                }
            }
        }
    }

    $cnt = (count($get_rst)*1000);
}catch(Exception $e) {
    die($e->getMessage());
}


$_LOGDB = mysqli_connect("compass-log-all-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "adop_dmp");
if ($_LOGDB->connect_errno) {
    die('Log-all DB Connect Error: ' . $_LOGDB->connect_error);
}

$job_name = 'hourly '.$device.'_'.$sdate.'PM';
$job_end_date = date('Y-m-d H:i:s', strtotime("+9 hours"));
$logSQL = " INSERT INTO audience_cron_log (deal_id, job_name, job_exe_id, cnt, job_start_date, job_end_date) VALUE ('".$did."', '".$job_name."', '".$job_exe_id."', '".$cnt."', '".$job_tar_date."','".$job_end_date."')";
$_LOGDB->query($logSQL);
$_LOGDB->close();
