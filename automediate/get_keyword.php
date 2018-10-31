<?php
/**
 * Created by IntelliJ IDEA.
 * User: hailey
 * Date: 2018. 6. 4.
 * Time: 오후 12:30
 */
ini_set('memory_limit','2048M');
ini_set('max_execution_time', 60 * 60 * 2);
date_default_timezone_set('UTC');

$now = date('Y-m-d H:i:s', strtotime("+9 hours"));
$job_name = 'basic_refer_keyword';

$search_date = date('Y-m-d H:00:00', strtotime("+8 hours"));
//$search_date = '2018-06-26 15:00:00';
$year = substr($search_date, 0, 4);
$month = substr($search_date, 5, 2);
$day = substr($search_date, 8, 2);
$hour = substr($search_date, 11, 2);

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

$sel = $client->StartQueryExecution(
    array(
        "QueryExecutionContext"=> array( "Database"=> "basic" ),
        "QueryString"=> "
               SELECT (CASE WHEN ref LIKE '%search.naver%' THEN 'NAVER'
                             WHEN ref LIKE '%search.daum.net/search?%' THEN 'DAUM'
                             WHEN ref LIKE '%search.daum.net/nate?%' THEN 'NATE'
                             WHEN ref LIKE '%search.zum.com/search.zum?%' THEN 'ZUM'
                             ELSE 'ETC' END) AS search_engine
                     , (CASE WHEN ref LIKE '%search.naver%' THEN url_extract_parameter(replace(ref, ' ', ''), 'query')
                             WHEN ref LIKE '%search.zum%' THEN url_extract_parameter(replace(ref, ' ', ''), 'query')
                             WHEN ref LIKE '%search.dreamwiz.com%' THEN url_extract_parameter(replace(ref, ' ', ''), 'sword')
                             WHEN ref LIKE '%search.yahoo.com/search?%' THEN url_extract_parameter(replace(ref, ' ', ''), 'p')
                             ELSE url_extract_parameter(replace(ref, ' ', ''), 'q') END) AS keyword
                     , count(*) AS cnt
                     , z.site_idx AS site_idx
                     , if(dev='tablet', 'mobile', dev) AS device
                FROM hourly h
                INNER JOIN zid_site_info z
                        ON h.zid = z.area_idx
                WHERE year = '".$year."'
                  AND month = '".$month."'
                  AND day = '".$day."'
                  AND hour = '".$hour."'
                  AND url!=ref
                  AND ref NOT IN ('undefined', '')
                  AND ord LIKE '1/%'
                  AND ref NOT LIKE '%dreamsearch%'
                  AND ref NOT LIKE '%cad.chosun.com%'
                  AND ref NOT LIKE '%fm1.jssearch.net%'
                  AND ref NOT LIKE '%ad.about.co.kr%'
                  AND ref NOT LIKE '%ad.ad4989.co.kr%'
                  AND ref NOT LIKE '%ad.adnmore.co.kr%'
                  AND ref NOT LIKE '%tracker.adbinead.com%'
                  AND ref NOT LIKE '%tpc.googlesyndication.com%'
                  AND ref NOT LIKE '%adtg.widerplanet.com%'
                  AND ref NOT LIKE '%ads.priel.co.kr%'
                  AND ref NOT LIKE '%nw.realssp.co.kr%'
                  AND ref NOT LIKE '%ads.mobitree.co.kr%'
                  AND ref NOT LIKE '%cas.criteo.com%'
                  AND ref NOT LIKE '%dgate.joins.com%'
                  AND ref NOT LIKE '%c6.daisy6.com%'
                  AND ref NOT LIKE '%livere.me%'
                  AND ref NOT LIKE '%ads.acrosspf.com%'
                  AND ref NOT LIKE '%adex.ednplus.com%'
                  AND ref NOT LIKE '%a.wyzmob.com%'
                  AND ref NOT LIKE '%linktender.net%'
                  AND ref NOT LIKE '%adkpf.newscloud.or.kr%'
                  AND ref NOT LIKE '%displayad.zum.com%'
                  AND ref NOT LIKE '%named.com/adx%'
                  AND ref NOT LIKE '%adx.turl.co.kr%'
                  AND ref NOT LIKE '%securepubads.g.doubleclick.net%'
                  AND ref NOT LIKE '%adn.inven.co.kr%'
                  AND ref NOT LIKE '%api.dable.io/widgets/%'
                  AND ref NOT LIKE '%compass.adop.cc%'
                  AND ref NOT LIKE '%ads-optima.com%'
                  AND (ref LIKE '%search.naver%'
                  OR ref LIKE '%search.daum.net/nate?%'
                  OR ref LIKE '%search.daum.net/search?%'
                  OR ref LIKE '%google.co.kr/search%'
                  OR ref LIKE '%google.com/search?%'
                  OR ref LIKE '%search.yahoo.com/search?%'
                  OR ref LIKE '%bing.com/search%'
                  OR ref LIKE '%search.zum.com/search.zum?%'
                  OR ref LIKE '%search.dreamwiz.com%')
                GROUP BY (CASE WHEN ref LIKE '%search.naver%' THEN 'NAVER'
                               WHEN ref LIKE '%search.daum.net/search?%' THEN 'DAUM'
                               WHEN ref LIKE '%search.daum.net/nate?%' THEN 'NATE'
                               WHEN ref LIKE '%search.zum.com/search.zum?%' THEN 'ZUM'
                               ELSE 'ETC' END)
                       , (CASE WHEN ref LIKE '%search.naver%' THEN url_extract_parameter(replace(ref, ' ', ''), 'query')
                               WHEN ref LIKE '%search.zum%' THEN url_extract_parameter(replace(ref, ' ', ''), 'query')
                               WHEN ref LIKE '%search.dreamwiz.com%' THEN url_extract_parameter(replace(ref, ' ', ''), 'sword')
                               WHEN ref LIKE '%search.yahoo.com/search?%' THEN url_extract_parameter(replace(ref, ' ', ''), 'p')
                               ELSE url_extract_parameter(replace(ref, ' ', ''), 'q') END)
                       , z.site_idx , if(dev='tablet', 'mobile', dev)
                ORDER BY cnt desc ",
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
for($i=0; $i<100; $i++){
    if(isset($get_rst[$i]['NextToken'])){
        $get_rst[$i+1] = $client->GetQueryResults(array(
            'QueryExecutionId' => $job_exe_id,
            'NextToken' => $get_rst[$i]['NextToken']
        ));
    }else{
        break;
    }
}

$_LOGDB = mysqli_connect("compass-log-all-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "adop_dmp");
//$_LOGDB = mysqli_connect("127.0.0.1", "root", "root", "test");

if ($_LOGDB->connect_errno) {
    die('Log-all DB Connect Error: ' . $_LOGDB->connect_error);
}
mysqli_query($_LOGDB,"set names utf8");
//echo "<br>cnt:".count($get_rst);
for ($i = 0; $i < count($get_rst); $i++) {
    foreach ($get_rst[$i]['ResultSet']['Rows'] as $key => $row) {
        if ($i == 0 && $key == 0) {
            $column_tmp[0] = $row['Data'][0]['VarCharValue'];//search_engine
            $column_tmp[1] = $row['Data'][1]['VarCharValue'];//keyword
            $column_tmp[2] = $row['Data'][2]['VarCharValue'];//cnt
            $column_tmp[3] = $row['Data'][3]['VarCharValue'];//site_idx
            $column_tmp[4] = $row['Data'][4]['VarCharValue'];//device
            $column_tmp[5] = 'search_date';//search_date
        } else {
            foreach ($row as $row2) {
                if($row2[1]['VarCharValue']!=''){
                    $vals_tmp[] = sprintf("('%s','%s','%s','%s','%s','%s') ",$row2[0]['VarCharValue'],str_replace("'", "", $row2[1]['VarCharValue']),$row2[2]['VarCharValue'],$row2[3]['VarCharValue'],$row2[4]['VarCharValue'], $search_date);
                }
            }
        }
    }
    if (($i % 10 == 0) && ($i < count($get_rst) - 1)) {
        $column = join(",", $column_tmp);
        $vals = join(",", $vals_tmp);

        $strSQL = " INSERT INTO basic_refer_keyword ( " . $column . " ) VALUES " . $vals . " ON DUPLICATE KEY UPDATE cnt = VALUES(cnt) ";
        $rt = $_LOGDB->query($strSQL);
        $vals = array();
        $vals_tmp = array();
        sleep(3);
    } elseif (($i == count($get_rst) - 1)) {
        $column = join(",", $column_tmp);
        $vals = join(",", $vals_tmp);

        $strSQL = " INSERT INTO basic_refer_keyword ( " . $column . " ) VALUES " . $vals . " ON DUPLICATE KEY UPDATE cnt = VALUES(cnt) ";
        $rt = $_LOGDB->query($strSQL);
        $vals = array();
        $vals_tmp = array();
        sleep(3);
    }
}

//$job_end_date = date('Y-m-d H:i:s', strtotime("+9 hours"));
//$sdate = $year.$month.$day.$hour;
//$logSQL = " INSERT INTO dmp_cron_log (job_name, job_tar_date, job_exe_id, job_start_date, job_end_date) VALUE ('".$job_name."', '".$sdate."', '".$job_exe_id."', '".$now."','".$job_end_date."')";
//$_LOGDB->query($logSQL);
//$_LOGDB->close();
