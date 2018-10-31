<meta charset="utf-8">
<?php
ini_set('memory_limit','2048M');
ini_set('max_execution_time', 60 * 60 * 2);
date_default_timezone_set('UTC');

$cur = date('Y-m-d H:i:s', strtotime("+9 hours"));
echo $cur;
$_LOGDB = mysqli_connect("compass-log-all-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "adop_dmp");
if ($_LOGDB->connect_errno) {
    die('Log-all DB Connect Error: ' . $_LOGDB->connect_error);
}
mysqli_query($_LOGDB,"set names utf8");

$real = date('Y-m-d', strtotime("+9 hours"));
//$now = '2018-07-04';
$now =  date('Y-m-d', strtotime($real."-1 days"));
$tmr = date('Y-m-d', strtotime($now."+1 days"));
echo "<br>".$now."/".$tmr;

$sql = "  select search_engine 
                , site_idx
                , keyword
                , sum(cnt) as cnt
                , ifnull(sum( case when device='desktop' then cnt end ), 0) as pc_cnt
                , ifnull(sum( case when device='mobile' then cnt end ), 0) as m_cnt
            from adop_dmp.basic_refer_keyword
            where search_date  >= '".$now."'
              and search_date < '".$tmr."'
            group by search_engine, site_idx, keyword
            order by search_engine, keyword, cnt desc ";

$word_list_tmp = mysqli_query($_LOGDB, $sql);
$word_list = $word_list_tmp->fetch_all(MYSQLI_ASSOC);

$vals_tmp = "";
foreach ($word_list as $rows){
    $vals_tmp[] = sprintf("('%s','%s','%s','%s','%s','%s','%s') ",$rows['search_engine'],$rows['keyword'],$rows['cnt'],$rows['pc_cnt'],$rows['m_cnt'],$rows['site_idx'],$now);
}

$arr = array_chunk($vals_tmp, 10000);

foreach ($arr as $row){
    $vals="";
    $vals = join(",", $row);

    $strSQL = " INSERT INTO basic_refer_keyword_daily (search_engine, keyword, cnt, pc_cnt, m_cnt, site_idx, search_date) VALUES ".$vals ."ON DUPLICATE KEY UPDATE cnt = VALUES(cnt), pc_cnt = VALUES(pc_cnt), m_cnt = VALUES(m_cnt)";
    $_LOGDB->query($strSQL);
}

$_LOGDB->close();

$end = date('Y-m-d H:i:s', strtotime("+9 hours"));
echo "<br>".$end;

