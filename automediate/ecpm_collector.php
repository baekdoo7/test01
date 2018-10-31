<?php
/**
 * Created by IntelliJ IDEA.
 * User: justin
 * Date: 2018. 7. 23.
 * Time: PM 3:34
 */
//eCPM = (rev/imp)*1000
//i_rev_adunit, i_network_info, i_daily_rev 조인 media_rev_no가 매체수익이고 매체 기준으로 eCPM구하려면 저걸 써야함.

//공통사용 변수 선언
$_COMDB = mysqli_connect("compass.cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "platon");
$_INDB = mysqli_connect("insight-cluster-1.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "insight");
$target_date = date('Y-m-d', strtotime("-39 hours"));
$insert_data = array();
$insert_data_tmp = "('%s',%s,'%s')";

//Insight에서 ecpm데이터 가져오기
function get_ecpm_data(){
    global $_INDB, $target_date;

    $ecpm_sql = "SELECT rev_id, com_idx, net_com_idx, site_idx, sdate, IMP, media_rev_no, unit_nm, net_idx, c_area_idx, compass_net_idx
FROM (SELECT idr.rev_id, idr.com_idx, idr.net_com_idx, idr.site_idx, idr.sdate, idr.IMP, idr.media_rev_no, idr.unit_nm,
ira.net_idx, ira.c_area_idx, ini.compass_net_idx
FROM i_daily_rev AS idr
INNER JOIN i_rev_adunit AS ira 
ON  md5(concat(idr.com_idx, idr.site_idx, idr.net_com_idx,idr.unit_nm)) = ira.unit_nm_md5
INNER JOIN i_network_info AS ini ON ira.net_idx = ini.net_idx
WHERE idr.sdate='".$target_date."' AND ira.c_area_idx!='' AND ira.del_yn='N' ORDER BY ira.ra_idx DESC) AS asd
GROUP BY c_area_idx, net_com_idx; ";
    $ecpm_info_tmp = mysqli_query($_INDB, $ecpm_sql);
    $ecpm_info['data'] = $ecpm_info_tmp->fetch_all(MYSQLI_ASSOC);
    $_INDB->close();
    if(count($ecpm_info['data'])>0){
        return get_area_data($ecpm_info);
    }else{
        $ecpm_info['code'] = 200;
        $ecpm_info['log'] = return_msg('get_ecpm_data', 'get_ecpm_data_fail' );
        return $ecpm_info;
    }
}

//Compass에서 영역, 광고단위 정보 가져오기
function get_area_data($ecpm_info){
    global $_COMDB;
    $area_sql = "SELECT ia.area_idx, ia.origin_areacd, aa.adv_idx, aa.network_adv_idx
FROM inventory_area AS ia
INNER JOIN inventory_area_ad AS iaa ON ia.area_idx=iaa.area_idx
INNER JOIN advertise_ad AS aa ON iaa.adv_idx = aa.adv_idx
WHERE ia.del_yn='N' AND ia.weight_direction='v' AND aa.del_yn='N' ";
    $area_info_tmp = mysqli_query($_COMDB, $area_sql);
    $area_info_arr = $area_info_tmp->fetch_all(MYSQLI_ASSOC);
    foreach ($area_info_arr as $arr){
        $area_info['data'][$arr['origin_areacd']][$arr['network_adv_idx']]=$arr['adv_idx'];
    }
    if(count($area_info['data'])>0){
        return make_insert_data($ecpm_info['data'], $area_info['data']);
    }else{
        $area_info['code'] = 200;
        $area_info['log'] = return_msg('get_area_data', 'get_area_data_fail' );
        return $area_info;
    }
}

//두 데이터를 맵핑하고 insert하기 위한 배열로 변경
function make_insert_data($ecpm_info_arr, $area_info){
    global $insert_data_tmp;
    foreach ($ecpm_info_arr as $row){
        $adv_idx = $area_info[$row['c_area_idx']][$row['compass_net_idx']];
        if($adv_idx!="") {
            if ($row['media_rev_no'] > 0 && $row['IMP'] > 0) {
                $ecpm = ($row['media_rev_no'] / $row['IMP']) * 1000;
                $insert_data['data'][] = sprintf($insert_data_tmp, $adv_idx, number_format($ecpm, 2, '.', ''), date('Y-m-d H:i:s', strtotime("+9 hours")));
            } else {
                $insert_data['data'][] = sprintf($insert_data_tmp, $adv_idx, 0, date('Y-m-d H:i:s', strtotime("+9 hours")));
            }
        }
    }
    $insert_data['code'] = 100;
    return $insert_data;
//    insert_ecpm_data($insert_data);
}

//테이블 만들고 배열을 쪼개서 DB인서트
function insert_ecpm_data($insert_data){

    global $_COMDB;

    $tb_check_query = " check table advertise_ad_ecpm_tmp; ";
    $check_rst_tmp = mysqli_query($_COMDB, $tb_check_query);
    $check_rst = $check_rst_tmp->fetch_all(MYSQLI_ASSOC);
    if($check_rst[0]['Msg_type']!="Error"){ //테이블이 있는경우
        return return_msg('200', 'insert_ecpm_data', 'exist_ecpm_tmp_table');
    }else {
        $create_query = " CREATE TABLE IF NOT EXISTS `advertise_ad_ecpm_tmp` ( `adv_idx` VARCHAR(75) NOT NULL, `ecpm` FLOAT NOT NULL, `reg_date` DATETIME NOT NULL, PRIMARY KEY (`adv_idx`)) ";
        $create_query .= " ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $create_rst = mysqli_query($_COMDB, $create_query);
        if ($create_rst) {
	    $insert_data_cnt = count($insert_data);
            $insert_data_query = implode(",", $insert_data);
            $insert_query = " INSERT INTO advertise_ad_ecpm_tmp (adv_idx, ecpm, reg_date) VALUES " . $insert_data_query;
            $insert_rst = mysqli_query($_COMDB, $insert_query);
            if ($insert_rst) {
		return return_msg('100', 'insert_ecpm_data', ' ecpm'.$insert_data_cnt.' ');
            } else { //insert에서 에러가 난 경우
                return return_msg('200', 'insert_ecpm_data', 'ecpm_insert_fail');
            }
        } else { //ecpm테이블 생성을 실패했을 경우
            return return_msg('200', 'insert_ecpm_data', 'create_ecpm_table_fail');
        }
    }
}

function return_msg($code=null, $func, $msg){
    $return_msg['date'] = date('Y-m-d H:i:s', strtotime("+9 hours"));
    $return_msg['code'] = $code;
    $return_msg['func'] = $func;
    $return_msg['msg'] = $msg;
    return json_encode($return_msg);

}

function start_ecpm_collector(){
    $insert_data = get_ecpm_data();
    if($insert_data['code'] == 100){
        $result = insert_ecpm_data($insert_data['data']);
        return $result;
    }else{
        return $insert_data['log'];
    }
}

//start_ecpm_collector(); 시작 함수

