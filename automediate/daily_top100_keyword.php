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
//$now = '2018-07-09';
$now =  date('Y-m-d', strtotime($real."-1 days"));
$tmr = date('Y-m-d', strtotime($now."+1 days"));
echo "<br>".$now."/".$tmr;


$total = "SELECT rowNo as rank
                , searchEngine
                , keyword
                , cnt
                , pc_cnt
                , m_cnt
            FROM ( SELECT searchEngine
                        , keyword
                        , cnt
                        , pc_cnt
                        , m_cnt
                        , (@rn:=if(@prev = searchEngine, @rn +1, 1)) as rowNo
                        , @prev:= searchEngine
                    FROM (
                        select search_engine as searchEngine
                            , keyword
                            , sum(cnt) as cnt
                            , ifnull(sum( case when device='desktop' then cnt end ), 0) as pc_cnt
            		    , ifnull(sum( case when device='mobile' then cnt end ), 0) as m_cnt
                        from adop_dmp.basic_refer_keyword
                        where search_date  >= '".$now."'
                          and search_date < '".$tmr."'
                        group by searchEngine, keyword
                        order by searchEngine, cnt desc
                        ) as a
                    JOIN (select @prev:=NULL, @rn :=0)  as b
                ) as c
            WHERE rowNo  <= 100
            ORDER BY searchEngine, cnt DESC";
$word_list_tmp2 = mysqli_query($_LOGDB, $total);
$word_list2 = $word_list_tmp2->fetch_all(MYSQLI_ASSOC);

$vals_tmp = "";
foreach ($word_list2 as $rows2){
    $vals_tmp[] = sprintf("('%s','%s','%s','%s','%s','%s','%s') ",$rows2['searchEngine'],$rows2['keyword'],$rows2['cnt'],'',$rows2['pc_cnt'],$rows2['m_cnt'],$now);
}
$vals="";
$vals = join(",", $vals_tmp);
$strSQL = " INSERT INTO basic_refer_top_keyword (search_engine, keyword, cnt, site_idx, pc_cnt, m_cnt, search_date) VALUES ".$vals ."ON DUPLICATE KEY UPDATE cnt = VALUES(cnt), pc_cnt = VALUES(pc_cnt), m_cnt = VALUES(m_cnt)";
$_LOGDB->query($strSQL);

$siteSql = "select distinct site_idx
              from adop_dmp.basic_refer_keyword
             where search_date  >= '".$now."'
               and search_date < '".$tmr."'
           ";
$site_list_tmp = mysqli_query($_LOGDB, $siteSql);
$site_list = $site_list_tmp->fetch_all(MYSQLI_ASSOC);

$vals_tmp = "";
foreach ($site_list as $row){
    $sql = "SELECT rowNo as rank
                , searchEngine
                , keyword
                , site_idx 
                , cnt
                , pc_cnt
                , m_cnt
            FROM ( SELECT searchEngine
                        , keyword
                        , cnt
                        , site_idx
                        , pc_cnt
                        , m_cnt
                        , (@rn:=if(@prev = searchEngine, @rn +1, 1)) as rowNo
                        , @prev:= searchEngine
                    FROM (
                        select search_engine as searchEngine
                            , site_idx
                            , keyword
                            , sum(cnt) as cnt
                        	, ifnull(sum( case when device='desktop' then cnt end ), 0) as pc_cnt
            				, ifnull(sum( case when device='mobile' then cnt end ), 0) as m_cnt
                        from adop_dmp.basic_refer_keyword
                        where search_date  >= '".$now."'
                          and search_date < '".$tmr."'
                          and site_idx = '".$row['site_idx']."'
                        group by searchEngine, keyword
                        order by searchEngine, cnt desc
                        ) as a
                    JOIN (select @prev:=NULL, @rn :=0)  as b
                ) as c
            WHERE rowNo  <= 100
            ORDER BY searchEngine, cnt DESC
            ";
    $word_list_tmp = mysqli_query($_LOGDB, $sql);
    $word_list = $word_list_tmp->fetch_all(MYSQLI_ASSOC);

    foreach ($word_list as $rows){
//        $vals_tmp[] = sprintf("('%s','%s','%s','%s','%s') ",$rows['searchEngine'],$rows['keyword'],$rows['cnt'],$rows['site_idx'],$now);
        $vals_tmp[] = sprintf("('%s','%s','%s','%s','%s','%s','%s') ",$rows['searchEngine'],$rows['keyword'],$rows['cnt'],$rows['site_idx'],$rows['pc_cnt'],$rows['m_cnt'],$now);
    }
}

$arr = array_chunk($vals_tmp, 10000);

foreach ($arr as $row){
    $vals="";
    $vals = join(",", $row);

    $strSQL = " INSERT INTO basic_refer_top_keyword (search_engine, keyword, cnt, site_idx, pc_cnt, m_cnt, search_date) VALUES ".$vals ."ON DUPLICATE KEY UPDATE cnt = VALUES(cnt), pc_cnt = VALUES(pc_cnt), m_cnt = VALUES(m_cnt)";
    $_LOGDB->query($strSQL);
}

$_LOGDB->close();

$end = date('Y-m-d H:i:s', strtotime("+9 hours"));
echo "<br>".$end;

