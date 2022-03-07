<?php
header('Content-Type: application/json; charset=UTF-8');
include_once('./_common.php');

/*
if(!isset($config['cf_line1_bom_idx'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_line1_bom_idx` VARCHAR(255) NOT NULL AFTER `cf_recaptcha_secret_key`,
                    ADD `cf_line2_bom_idx` VARCHAR(255) NOT NULL AFTER `cf_line1_bom_idx`,
                    ADD `cf_line3_bom_idx` VARCHAR(255) NOT NULL AFTER `cf_line2_bom_idx`,
                    ADD `cf_line4_bom_idx` VARCHAR(255) NOT NULL AFTER `cf_line3_bom_idx` ", true);
}
if(!isset($config['cf_current_oop_idx'])) {
    sql_query(" ALTER TABLE `{$g5['config_table']}`
                    ADD `cf_current_oop_idx` VARCHAR(255) NOT NULL AFTER `cf_recaptcha_secret_key`, true);
}
$g5['setting']['set_api_token']
*/

$rawBody = file_get_contents("php://input"); // 본문을 불러옴
$getData = array(json_decode($rawBody,true)); // 데이터를 변수에 넣고

if($test){
    $weight = trim($weight);
    $getData = array();
    $getData[0] = array();
    $getData[0] = $_POST;
    $getData[0]['weight'] = $weight;
}

// 토큰 비교
if(!check_token1($getData[0]['token'])) {
	$result_arr = array("code"=>499,"message"=>"token error");
}
else if($getData[0]['bom_part_no']) {
    $arr = $getData[0];
    $mtr_lot = substr($arr['mtr_barcode'],0,6);
    $sql = " INSERT INTO {$g5['material_table']} SET
                com_idx = '{$_SESSION['ss_com_idx']}'
                , bom_idx = '{$arr['bom_idx']}'
                , bom_idx_parent = '{$arr['bom_idx_parent']}'
                , oop_idx = '{$arr['oop_idx']}'
                , bom_part_no = '{$arr['bom_part_no']}'
                , mtr_name = '{$arr['mtr_name']}'
                , mtr_barcode = '{$arr['mtr_barcode']}'
                , mtr_type = 'half'
                , mtr_weight = '{$arr['weight']}'
                , mtr_lot = '{$mtr_lot}'
                , mtr_price = '{$arr['mtr_price']}'
                , trm_idx_location = '{$arr['trm_idx_location']}'
                , mtr_status = 'finish'
                , mtr_input_date = '".G5_TIME_YMD."'
                , mtr_reg_dt = '".G5_TIME_YMDHIS."'
                , mtr_update_dt = '".G5_TIME_YMDHIS."'
    ";
    sql_query($sql,1);
    $mtr_idx = sql_insert_id();
    $result_arr['code'] = 200;
    $result_arr['message'] = 'Inserted OK!';
    $result_arr['mtr_idx'] = $mtr_idx;
    $result_arr['mtr_status'] = 'finish';
}
else{
    $result_arr = array("code"=>599,"message"=>"error");
}

//테스트페이지로부터 호출되었으면 테스트 폼페이지로 이동
if($test){
    goto_url('./form.php?oop_idx='.$oop_idx.'&mtr_idx='.$mtr_idx);
}
else{
    echo json_encode( array('meta'=>$result_arr) );
}