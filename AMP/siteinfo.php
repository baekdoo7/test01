<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 10. 10.
 * Time: PM 1:33
 */

$sql = " select aa.*, ia.site_idx, ia.area_platform from advertise_ad2 aa 
inner join inventory_area_ad iaa ON aa.adv_idx= iaa.adv_idx 
left join inventory_area ia ON iaa.area_idx = ia.area_idx 
where iaa.ad_state='1' and aa.com_idx='a252ab7a-3306-4038-9475-3fb5001e4855' and aa.adv_idx = '22dbdc50-4f4f-45f5-80ae-b2441e5297bc' and aa.network_adv_idx = '259737b6-b23d-11e7-8214-02c31b446301' and aa.del_yn='N'  ";

$adv_tmp = mysqli_query($com_conn, $sql);
$adv_rst = $adv_tmp->fetch_all(MYSQLI_ASSOC);

$site_sql = " SELECT * FROM i_site_info where site_del_yn='N' ";
$rst_tmp = mysqli_query($in_conn, $site_sql);
$site_rst = $rst_tmp->fetch_all(MYSQLI_ASSOC);

foreach ($site_rst as $site_row){
    $site_info[$site_row['site_idx_cps']]['pc'] =$site_row['site_web_url'];
    if($site_row['site_mobile_url']!="") {
        $site_info[$site_row['site_idx_cps']]['mo'] = $site_row['site_mobile_url'];
    }
}


foreach ($adv_rst as $key=>$row){
    var_dump($site_info[$row['site_idx']]);
    if($row['area_platform']=="M"){
        $loc = ($site_info[$row['site_idx']]['mo'] == "") ? 'compass.adop.cc' : $site_info[$row['site_idx']]['mo'];
    }else{
        echo $row['site_idx'];
        $loc = ($site_info[$row['site_idx']]['pc'] == "") ? 'compass.adop.cc' : $site_info[$row['site_idx']]['pc'];
    }
//        $loc = ($row['site_url'] == "") ? 'compass.adop.cc' : $row['site_url'];
    $html_tmp  = $row['html_code'];
    $adv_rst[$key]['html_code'] = str_replace('&cntsr', '%3Floc%3D'.urlencode($loc).'&cntsr', $html_tmp);
    $html_tmp = str_replace('&cntsr', '%3Floc%3D'.urlencode($loc).'&cntsr', $html_tmp);
    $adv_rst[$key]['net_adv_passback'] = $row['net_adv_passback'].'?loc='.$loc;
    $pb_url = $row['net_adv_passback'].'?loc='.$loc;

    //$up_sql = " UPDATE advertise_ad2 SET html_code = '$html_tmp', net_adv_passback='$pb_url' where adv_idx='".$row['adv_idx']."' ";
    //echo "<textarea cols=100 rows=30>".$up_sql."</textarea>";
    //mysqli_query($com_conn, $up_sql);
}