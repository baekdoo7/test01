<?php
ini_set('memory_limit','2048M');
ini_set('max_execution_time', 60 * 60 * 2);
define("__DB_HOST","compass.cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com");
define("__DB_USER", "adopadmin");
define("__DB_PWD", "Adop*^14");
define("__DB_NAME", "platon");
define("__ADOP_COM_IDX", "a252ab7a-3306-4038-9475-3fb5001e4855");
//define("__S3_FILE_PATH", "/Users/Administrator/Documents/workspace/compass_v2/application/libraries/aws/aws-autoloader.php");
define("__S3_FILE_PATH", "/Data/aws/aws-autoloader.php");
define("__S3_BUKET", "compass.adop.cc");
define("__INSIGHT_DB_HOST", "insight-cluster-1.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com");
define("__INSIGHT_DB_NAME", "insight");


function __INSIGHT_DB_CONNECT() {
    $_DBCON = mysqli_connect(__INSIGHT_DB_HOST, __DB_USER, __DB_PWD, __INSIGHT_DB_NAME);
    if ($_DBCON->connect_errno) {
        die('Connect Error: '.$_DBCON->connect_error);
    }
    mysqli_query($_DBCON,"set names utf8");
    return $_DBCON;
}

function __DB_CONNECT() {
    $_DBCON = mysqli_connect(__DB_HOST, __DB_USER, __DB_PWD, __DB_NAME);
    if ($_DBCON->connect_errno) {
        die('Connect Error: '.$_DBCON->connect_error);
    }
    mysqli_query($_DBCON,"set names utf8");
    return $_DBCON;
}

function __DB_DISCONNECT($_DBCON) {
    mysqli_close($_DBCON);
}


/*
 * 1. op_type 이 A (오토)로 설정된 origin_areacd 로드
 */
function getAutoMediationList() {
    $_DBCON = __DB_CONNECT();

    $sql = sprintf(" SELECT ia.origin_areacd, ia.com_idx, COUNT(iaa.ad_idx) AS cnt
                            FROM   inventory_area AS ia INNER JOIN inventory_area_ad AS iaa ON ia.area_idx = iaa.area_idx
                            WHERE  ia.op_type = 'A'
                            AND    ia.del_yn = 'N'
                            AND    ia.com_idx = '%s'
                            GROUP  BY ia.origin_areacd
                            HAVING cnt > 2
                            ORDER  BY NULL;", __ADOP_COM_IDX);

    $query = $_DBCON->query($sql);
    $_RETURN = array();
    while($row = $query->fetch_assoc()) {
        $_RETURN[$row['origin_areacd']]['origin_areacd'] = $row['origin_areacd'];
        $_RETURN[$row['origin_areacd']]['com_idx'] = $row['com_idx'];
    }
    __DB_DISCONNECT($_DBCON);
    return $_RETURN;
}

/*
 * 2. origin_areacd 로 상세 데이터(인벤토리, 광고) 로드
 * network_idx 별로 인벤토리 데이터와 광고 데이터 분류
 */
function getInventoryAndAdvertiseData($param) {
    $_DBCON = __DB_CONNECT();

    $sql = sprintf(" SELECT ia.origin_areacd, ia.area_name, nt.net_site_nm, aa.adv_idx, ia.area_idx, aa.cmp_idx, aa.gp_idx, iaa.ad_type, iaa.ad_weight, iaa.ad_state
                                    , aa.net_adv_param01, aa.net_adv_param02, aa.net_adv_param03, aa.net_adv_param04, aa.net_adv_param05, aa.net_adv_param06
                                    , aa.net_adv_passback, ia.com_idx, aa.chk_yn, aa.fprice, ia.op_type, ia.area_no, aa.imp, ae.ecpm, ia.area_type, aa.network_adv_idx, '' AS html_code
                            FROM    inventory_area AS ia INNER JOIN inventory_area_ad AS iaa ON ia.area_idx = iaa.area_idx
                                                         INNER JOIN advertise_ad AS aa ON aa.adv_idx = iaa.adv_idx
                                                         LEFT OUTER JOIN advertise_ad_ecpm AS ae ON aa.adv_idx = ae.adv_idx
                                                         LEFT OUTER JOIN network_template AS nt ON nt.net_idx = aa.network_adv_idx AND nt.com_idx = '%s'
                            WHERE   ia.origin_areacd = '%s'
                            AND     iaa.ad_state = 1
                            ORDER   BY ia.area_no ASC ", $param['com_idx'], $param['origin_areacd']);

    $query = $_DBCON->query($sql);
    $_RETURN['area'] = array();
    $_RETURN['adv'] = array();
    $areaColNameArr = array('origin_areacd', 'area_name', 'area_idx', 'com_idx', 'op_type', 'area_no', 'area_type');
    $advColNameArr = array('net_site_nm', 'adv_idx', 'cmp_idx', 'gp_idx', 'ad_type', 'ad_weight', 'ad_state', 'net_adv_param01', 'net_adv_param02', 'net_adv_param03'
                            , 'net_adv_param04', 'net_adv_param05', 'net_adv_param06', 'net_adv_passback', 'chk_yn', 'fprice', 'imp', 'ecpm', 'network_adv_idx', 'html_code');

    if($query->num_rows > 0) {
        $maxImp = $query->num_rows;
        while ($row = $query->fetch_assoc()) {
            foreach ($areaColNameArr AS $colName) {
                $_RETURN['area'][$row['area_type']][$colName] = $row[$colName];
            }
            foreach ($advColNameArr AS $colName) {
                if (isset($row['network_adv_idx']) && $row['network_adv_idx'] != '') {
                    if($row['imp'] == $maxImp) { //마지막 광고만 별도로 관리 > 마지막 광고는 순위 변동 시키면 안됨
                        $_RETURN['adv']['last'][$row['network_adv_idx']][$colName] = $row[$colName];
                        $_RETURN['adv']['last'][$row['network_adv_idx']]['ecpm'] = isset($row['ecpm']) && $row['ecpm'] != '' ? $row['ecpm'] : 0;
                    } else {
                        $_RETURN['adv']['list'][$row['network_adv_idx']][$colName] = $row[$colName];
                        $_RETURN['adv']['list'][$row['network_adv_idx']]['ecpm'] = isset($row['ecpm']) && $row['ecpm'] != '' ? $row['ecpm'] : 0;
                    }
                }
            }
        }
    }

    __DB_DISCONNECT($_DBCON);
    return $_RETURN;
}

/*
 * Ecpm이 높은 순서대로 광고 배열을 재정의하고, 광고배열의 순서대로 인벤토리 배열과 합침
 * 조건 1 : 마지막 광고의 순서는 바뀌지 않음
 * 조건 2 : Ecpm이 동률일 경우 기존 순서대로
 * 조건 3 : Ecpm이 없을 경우 기존 순서대로
 */
function changeMediationByEcpm($param) {
    array_multisort(array_column($param['adv']['list'], 'ecpm'), SORT_DESC, array_column($param['adv']['list'], 'imp'), SORT_ASC, $param['adv']['list']);
    foreach($param['adv']['last'] AS $key => $val) {
        $param['adv']['list'][$key] = $val;
    }
    $areaNo = 0;
    $preNetIdx = 0;
    $date = date('Y-m-d H:i:s');
    $areaSqlArr['set']['areaNo'] = ' area_no = CASE '; //area_no 변경
    $areaSqlArr['set']['date'] = sprintf(" modi_date = '%s' ", $date);
    $advSqlArr['set']['imp'] = ' imp = CASE '; //imp 변경
    $advSqlArr['and']['adv_idx'] = array();
    $areaAdSqlArr['set']['areaIdx'] = ' area_idx = CASE '; //area_idx 변경
    $areaAdSqlArr['set']['date'] = sprintf(" modi_date = '%s' ", $date);
    $areaAdSqlArr['and']['adv_idx'] = array();
    $historySqlArr['insert'] = array();
    $cacheSqlArr['and']['areaIdx'] = array();
    $cacheSqlArr['and']['origin_areacd'] = '';
    foreach($param['adv']['list'] AS $key => $val) {
        $param['adv']['list'][$key]['imp'] = $areaNo + 1;
        $param['adv']['list'][$key]['origin_areacd'] = $param['area'][$preNetIdx]['origin_areacd'];
        $param['adv']['list'][$key]['area_name'] = $param['area'][$preNetIdx]['area_name'];
        $param['adv']['list'][$key]['area_idx'] = $param['area'][$preNetIdx]['area_idx'];
        $param['adv']['list'][$key]['com_idx'] = $param['area'][$preNetIdx]['com_idx'];
        $param['adv']['list'][$key]['op_type'] = $param['area'][$preNetIdx]['op_type'];
        $param['adv']['list'][$key]['area_no'] = $areaNo;
        $param['adv']['list'][$key]['area_type'] = $param['area'][$preNetIdx]['area_type'];

        //inventory_area update 쿼리문
        $areaSqlArr['and']['origin_areacd'] = $param['area'][$preNetIdx]['origin_areacd'];
        $areaSqlArr['set']['areaNo'].= sprintf(" WHEN area_idx = '%s' THEN %s ", $param['area'][$preNetIdx]['area_idx'], $areaNo);

        //advertise_ad update 쿼리문
        $advSqlArr['set']['imp'].= sprintf(" WHEN adv_idx = '%s' THEN %s ", $val['adv_idx'], $areaNo + 1);
        array_push($advSqlArr['and']['adv_idx'], $val['adv_idx']);

        //inventory_area_ad update 쿼리문
        $areaAdSqlArr['set']['areaIdx'].= sprintf(" WHEN adv_idx = '%s' THEN '%s' ", $val['adv_idx'], $param['area'][$preNetIdx]['area_idx']);
        array_push($areaAdSqlArr['and']['adv_idx'], $val['adv_idx']);

        //inventory_area_ad_history 쿼리문
        array_push($historySqlArr['insert'], sprintf(" ('%s', '%s', '%s', '%s', '%s'
                                                                        , '%s', '%s', '%s', '%s', '%s'
                                                                        , '%s', '%s', '%s', '%s', '%s'
                                                                        , '%s', '%s', '%s', '%s', '%s'
                                                                        , '%s', '%s', '%s', '%s', '%s'
                                                                        , '%s', '%s', '%s') "
                                                            , $areaNo, $param['area'][$preNetIdx]['origin_areacd'], $param['area'][$preNetIdx]['area_name'], $val['net_site_nm'], $val['network_adv_idx']
                                                            , $val['adv_idx'], $param['area'][$preNetIdx]['area_idx'], $val['cmp_idx'], $val['gp_idx'], $val['ad_type']
                                                            , $val['ad_weight'], $val['ad_state'], $val['html_code'], $val['net_adv_param01'], $val['net_adv_param02']
                                                            , $val['net_adv_param03'], $val['net_adv_param04'], $val['net_adv_param05'], $val['net_adv_param06'], $val['net_adv_passback']
                                                            , $date, 'Compass', 'Y', $val['chk_yn'], 'adv_modi'
                                                            , $param['area'][$preNetIdx]['com_idx'], $val['fprice'], $param['area'][$preNetIdx]['op_type']));

        array_push($cacheSqlArr['and']['areaIdx'], $param['area'][$preNetIdx]['area_idx']);
        $cacheSqlArr['and']['origin_areacd'] = $param['area'][$preNetIdx]['origin_areacd'];

        $preNetIdx = $key;
        $areaNo++;
    }
    $areaSqlArr['set']['areaNo'].= ' ELSE area_no END ';
    $advSqlArr['set']['imp'].= ' END ';
    $areaAdSqlArr['set']['areaIdx'].= ' END ';

    if(join(",", array_keys($param['area'])) != "0,".join(",", array_keys($param['adv']['list']))) { //기존 순서와 다를 때만
        try {
            $_DBCON = __DB_CONNECT();
            $_DBCON->autocommit(FALSE);
            $_INSIGHT_DBCON = __INSIGHT_DB_CONNECT();

            updateInventoryAreaAd($_DBCON, $areaAdSqlArr); //inventory_area_ad 업데이트
            updateAdvertiseImp($_DBCON, $advSqlArr); //advertise_ad 업데이트
            updateInventoryAreaNo($_DBCON, $areaSqlArr); //inventory_area 업데이트
            insertHistory($_DBCON, $historySqlArr); //inventory_area_ad_history 업데이트
            $_DBCON->commit();
            $_DBCON->autocommit(TRUE);
            makeS3Cache($_DBCON, $cacheSqlArr['and']['areaIdx']);//광고 캐시파일 갱신
            make_area_info($_DBCON, $_INSIGHT_DBCON, $cacheSqlArr['and']['origin_areacd']);//라벨 캐시파일 갱신
            $_RETURN['msg'] = 'Success. origin_areacd : ' . $cacheSqlArr['and']['origin_areacd'];
            $_RETURN['code'] = 100;
        } catch(Exception $e) {
            $_DBCON->rollback();
            $_DBCON->autocommit(TRUE);
            $_RETURN['msg'] = $e->getMessage();
            $_RETURN['code'] = $e->getCode();
        } finally {
            __DB_DISCONNECT($_DBCON);
            __DB_DISCONNECT($_INSIGHT_DBCON);
        }
    } else {
        $_RETURN['msg'] = 'No changed.  origin_areacd : ' . $cacheSqlArr['and']['origin_areacd'];
        $_RETURN['code'] = 300;
    }
    return $_RETURN;
}

/*
 * 인벤토리 area_no (순서) 변경
 */
function updateInventoryAreaNo($_DBCON, $param) {
    $sql = sprintf(" UPDATE inventory_area
                            SET     %s
                            WHERE   origin_areacd = '%s'
                            AND     del_yn = 'N' ", join(",", $param['set']), $param['and']['origin_areacd']);
    $result = $_DBCON->query($sql);
    if(!$result) {
        throw new Exception('inventory_area area_no update error. origin_areacd : ' . $param['and']['origin_areacd'], 200);
    }
}

/*
 * 광고게재 imp (순서) 변경
 */
function updateAdvertiseImp($_DBCON, $param) {
    $sql = sprintf(" UPDATE advertise_ad
                            SET     %s
                            WHERE   adv_idx IN ('%s') ", join(",", $param['set']), join("','", $param['and']['adv_idx']));
    $result = $_DBCON->query($sql);
    if(!$result) {
        throw new Exception('advertise_ad imp update error. adv_idx : ' . join(" , ", $param['and']['adv_idx']), 200);
    }
}

/*
 * inventory_area_ad 변경
 * adv_idx 기준으로 area_idx 변경
 */
function updateInventoryAreaAd($_DBCON, $param) {
    $sql = sprintf(" UPDATE inventory_area_ad
                            SET     %s
                            WHERE   adv_idx IN ('%s') ", join(",", $param['set']), join("','", $param['and']['adv_idx']));
    $result = $_DBCON->query($sql);
    if(!$result) {
        throw new Exception('inventory_area update error. adv_idx : ' . join(" , ", $param['and']['adv_idx']), 200);
    }
}

/*
 * inventory_area_ad_history 에 기록 남김
 */
function insertHistory($_DBCON, $param) {
    $sql = sprintf(" INSERT INTO inventory_area_ad_history (list_idx, origin_areacd, area_name, net_name, net_idx, adv_idx, area_idx, cmp_idx, gp_idx
                                                                        , ad_type, ad_weight, ad_state, html_code, net_adv_param01, net_adv_param02, net_adv_param03
                                                                        , net_adv_param04, net_adv_param05, net_adv_param06, net_adv_passback, update_date, manager
                                                                        , mediation_yn, chk_yn, log_type, com_idx, fprice, op_type)
                             VALUES %s ", join(",", $param['insert']));
    $result = $_DBCON->query($sql);
    if(!$result) {
        throw new Exception('inventory_area_ad_history insert error.', 200);
    }
}

function makeS3Cache($_DBCON, $cacheSqlArr) {
    require_once __S3_FILE_PATH;
    $client = new Aws\S3\S3Client([
        'version' => 'latest',
        'region' => 'ap-northeast-2',
        'credentials' => [
            'key' => 'AKIAIXJZL4X2FQWIQJUQ',
            'secret' => '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',
        ]
    ]);

    $totalCnt = count($cacheSqlArr);

    foreach($cacheSqlArr AS $key => $val) {
        $sql = sprintf("SELECT aa.area_idx, aa.adv_idx, aa.ad_type, aa.ad_weight,
                                ad.com_idx, ad.network_adv_idx, ad.adv_supply, ad.adv_file_path, ad.adv_md5_filename,
                                ad.html_code, ad.adv_clk_url, ad.adv_clk_url_hp, ad.add_type, ad.net_adv_passback,ad.imp,ad.clk AS pann_idx,
                                size.size_width, size.size_height, si.site_name, ad.adv_url, ad.adv_url_hp, ad.adv_clk_tar,
                                area.weight_direction, area.area_type, area.origin_areacd, ad.adv_price,
                                ad.net_adv_param01, ad.net_adv_param02, CONCAT(%s,'/',%s) AS ord, TRIM(nt.net_site_nm) AS net_nm
                                FROM inventory_area_ad AS aa
                                LEFT JOIN inventory_area AS area ON aa.area_idx = area.area_idx
                                LEFT JOIN advertise_ad AS ad ON aa.adv_idx = ad.adv_idx
                                LEFT JOIN advertise_ad_size size ON ad.adv_size_idx = size.adv_size_idx
                                LEFT JOIN inventory_site AS si ON area.site_idx = si.site_idx
                                LEFT JOIN network_template nt ON ad.network_adv_idx = nt.net_idx AND ad.com_idx = nt.com_idx
                                WHERE  aa.del_yn = 'N'
                                AND aa.area_idx = '%s'
                                AND aa.ad_state = 1 ;", $key + 1, $totalCnt, $val);

        $query = $_DBCON->query($sql);
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $client->putObject(array(
                    'Bucket' => __S3_BUKET,
                    'Key' => sprintf("adinfo_2018/RD/%s/ad_info.txt", $val),
                    'Body' => json_encode($row),
                    'ACL' => 'public-read'
                ));
            }
        }
    }
}

function make_area_info($_DBCON, $_INSIGHT_DBCON, $origin_areacd){
    require_once __S3_FILE_PATH;
    $client = new Aws\S3\S3Client([
        'version' => 'latest',
        'region' => 'ap-northeast-2',
        'credentials' => [
            'key' => 'AKIAIXJZL4X2FQWIQJUQ',
            'secret' => '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',
        ]
    ]);

    $area_basic_sql = " select area_idx, site_idx from inventory_area where area_idx = '".$origin_areacd."'; ";
    $area_basic_info = mysqli_query($_DBCON, $area_basic_sql);
    $area_basic_tmp = $area_basic_info->fetch_array(MYSQLI_ASSOC);

    $com_idx_sql = " select com_idx from i_site_info where site_idx_cps = '".$area_basic_tmp['site_idx']."'";
    $com_idx_info = mysqli_query($_INSIGHT_DBCON, $com_idx_sql);
    $com_idx_tmp = $com_idx_info->fetch_array(MYSQLI_ASSOC);

    $com_idx = $com_idx_tmp['com_idx'];

    $cfs_sql = "select * from i_url_pattern where del_yn='N' and com_idx =".$com_idx." and cfs_type != 0;";
    $cfs_sql_info_tmp = mysqli_query($_INSIGHT_DBCON, $cfs_sql);
    if($cfs_sql_info_tmp->num_rows > 0){
        $cfs_yn = "Y";
    }else{
        $cfs_yn = "N";
    }

    $area_info_sql = "select ia.area_idx, aa.network_adv_idx, ia.weight_direction, ia.area_name from inventory_area ia
left join inventory_area_ad  iaa ON ia.area_idx = iaa.area_idx
left join advertise_ad aa ON iaa.adv_idx = aa.adv_idx
where ia.origin_areacd = '".$origin_areacd."' and ia.del_yn='N' and iaa.ad_state='1'
and iaa.del_yn='N' order by aa.imp";
    $area_info = mysqli_query($_DBCON, $area_info_sql);
    $area = "";

    if($area_info->num_rows > 0) {
        $area_info_tmp = $area_info->fetch_all(MYSQLI_ASSOC);
        $area['area_idx'] = $origin_areacd;
        $area['area_nm'] = $area_info_tmp[0]['area_name'];
        $area['cfs_site'] = $cfs_yn;
        $area['type'] = $area_info_tmp[0]['weight_direction'] == "v" ? "W" : "R";

        foreach($area_info_tmp as $key => $row){
            $area['ad_order'][] = $row['network_adv_idx'];
        }
        if($area != "") {
            $client->putObject(array(
                'Bucket' => __S3_BUKET,
                'Key' => sprintf("areainfo/%s/area_info.txt", $origin_areacd),
                'Body' => json_encode($area),
                'ACL' => 'public-read'
            ));
        }
    }
}

function runAutoMediation() {
    $workCnt = $noChangeCnt = 0;
    $autoMeidationList = getAutoMediationList();
    foreach($autoMeidationList AS $row) {
        $data = getInventoryAndAdvertiseData($row);
        if(count($data['adv']['list']) >= 2) { //마지막 광고를 제외하고 광고가 최소 2개 이상일 때(총 광고의 갯수가 3개)부터 자동화, 1개 이하는 패스
            $_RETURN = changeMediationByEcpm($data);
            if($_RETURN['code'] == 200) {
                return json_encode($_RETURN);
            } else if($_RETURN['code'] == 300) {
                $workCnt++;
                $noChangeCnt++;
            } else {
                $workCnt++;
            }
        }
    }

    $_RETURN['code'] = 100;
    $_RETURN['msg'] = "Checked : " . count($autoMeidationList) .  " Success : " . $workCnt . " No Changed : " . $noChangeCnt;
    return json_encode($_RETURN);
}