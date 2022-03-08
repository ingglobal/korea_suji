<?php
header('Content-Type: application/json; charset=UTF-8');
include_once('./_common.php');


$rawBody = file_get_contents("php://input"); // 본문을 불러옴
$getData = array(json_decode($rawBody,true)); // 데이터를 변수에 넣고

if($test){
    $weight = trim($weight);
    $getData = array();
    $getData[0] = array();
    $getData[0] = $_POST;
}

// 토큰 비교
if(!check_token1($getData[0]['token'])) {
	$result_arr = array("code"=>499,"message"=>"token error");
}
else if($getData[0]['oop_idx']) {
    $sql = " UPDATE {$g5['order_out_practice_table']} AS oop SET
                oop_mtr_weight = ( SELECT SUM(mtr_weight) FROM {$g5['material_table']} WHERE oop_idx = '{$getData[0]['oop_idx']}' ) 
            WHERE oop.oop_idx = '{$getData[0]['oop_idx']}'
    ";
    sql_query($sql,1);
    $result_arr['code'] = 200;
    $result_arr['message'] = 'Updated OK!';
    $result_arr['oop_idx'] = $getData[0]['oop_idx'];
} 
else {
    $result_arr = array("code"=>599,"message"=>"error");
}


//테스트페이지로부터 호출되었으면 테스트 폼페이지로 이동
if($test){
    goto_url('./form.php?oop_idx='.$oop_idx);
}
else{
    echo json_encode( array('meta'=>$result_arr) );
}