<?php
$sub_menu = "920100";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

/*
Array
(
    [w] => 
    [sfl] => 
    [stx] => 
    [sst] => 
    [sod] => 
    [page] => 
    [token] => ab7b7f9a4497338722f5b5f3a196e026
    [com_idx] => 8
    [ord_idx] => ord_idx
    [sca] => 
    [com_idx_customer] => 9
    [com_name] => 거래처2
    [ord_price] => 61,374
    [ord_ship_date] => 2021-07-31
    [ord_type] => ok
    [serialized] => [{\"id\":1,\"depth\":0,\"bom_name\":\"FRT 고정 그레이 쌍침\",\"bom_idx_child\":\"1\",\"bit_count\":\"1\"},{\"id\":2,\"depth\":0,\"bom_name\":\"FRT 고정 블랙 쌍침\",\"bom_idx_child\":\"7\",\"bit_count\":\"2\"},{\"id\":3,\"depth\":0,\"bom_name\":\"FRT 고정 블랙 쌍침(PE)\",\"bom_idx_child\":\"9\",\"bit_count\":\"3\"}]
)
Array
(
    [0] => Array
        (
            [id] => 1
            [depth] => 0
            [bom_name] => FRT 고정 그레이 쌍침
            [bom_idx_child] => 1
            [bit_count] => 1
        )

    [1] => Array
        (
            [id] => 2
            [depth] => 0
            [bom_name] => FRT 고정 블랙 쌍침
            [bom_idx_child] => 7
            [bit_count] => 2
        )

    [2] => Array
        (
            [id] => 3
            [depth] => 0
            [bom_name] => FRT 고정 블랙 쌍침(PE)
            [bom_idx_child] => 9
            [bit_count] => 3
        )

)

Array
(
    [ori_idx] => 2
    [com_idx] => 8
    [com_idx_customer] => 0
    [ord_idx] => 1
    [bom_idx] => 7
    [ori_count] => 2
    [ori_price] => 10204
    [ori_status] => ok
    [ori_reg_dt] => 2021-07-22 16:26:42
    [ori_update_dt] => 2021-07-22 16:26:42
)
*/

$data = json_decode(stripslashes($_POST['serialized']),true);
$data_k = array();
foreach($data as $dta){
    $data_k[$dta['bom_idx_child']] = $dta;
    //print_r2($data_k[$dta['bom_idx_child']]);
    //echo $data_k[$dta['bom_idx_child']]['bom_idx_child']."<br>";
    $bsql = " SELECT com_idx_customer FROM {$g5['bom_table']} WHERE bom_idx = '{$data_k[$dta['bom_idx_child']]['bom_idx_child']}' ";
    $cust = sql_fetch($bsql);
    //print_r2($cust);
    $data_k[$dta['bom_idx_child']]['com_idx_customer'] = "{$cust['com_idx_customer']}";
}
// print_r2($data_k);
// exit;
if(count($data) == 0) alert('적어도 상품 한 개 이상은 등록해 주세요.');

$ord_price = str_replace(',','',trim($ord_price));

$sql_common = " com_idx = '{$com_idx}',
                ord_price = '{$ord_price}',
                ord_ship_date = '{$ord_ship_date}',
                ord_status = '{$ord_status}',
                ord_date = '{$ord_date}',
";

if($w == ''){
    $sql_common .= " ord_reg_dt = '".G5_TIME_YMDHIS."',ord_update_dt = '".G5_TIME_YMDHIS."' ";

    $sql = " INSERT into {$g5['order_table']} SET
                {$sql_common}
    ";
    sql_query($sql,1);
	$ord_idx = sql_insert_id();
}
else if($w == 'u'){
    $sql_common .= " ord_update_dt = '".G5_TIME_YMDHIS."' ";

    $sql = " UPDATE {$g5['order_table']} SET
                {$sql_common}
            WHERE ord_idx = '{$ord_idx}'
    ";
    sql_query($sql,1);
}
else if($w == 'd'){
    $sql = " UPDATE {$g5['order_table']} SET
                ord_status = 'trash'
            WHERE ord_idx = '{$ord_idx}'
    ";
    sql_query($sql,1);
    $sql_ori = " UPDATE {$g5['order_item_table']} SET
                    ori_status = 'trash'
                WHERE ord_idx = '{$ord_idx}'  
    ";
    sql_query($sql_ori,1);
}

if($w != 'd'){
    $old_boms = array();
    $tmp_boms = array();
    $old_r = sql_query(" SELECT bom_idx FROM {$g5['order_item_table']} WHERE ord_idx = '{$ord_idx}' ");
    for($i=0;$old=sql_fetch_array($old_r);$i++) array_push($old_boms,$old['bom_idx']);
    foreach($data as $dv) array_push($tmp_boms,$dv['bom_idx_child']);
    //print_r2($old_boms);
    //print_r2($tmp_boms);

    $del_boms = array_diff($old_boms,$tmp_boms);//삭제할 데이터
    //print_r2($del_boms); 
    
    $add_boms = array_diff($tmp_boms,$old_boms);//추가될 데이터
    //print_r2($add_boms); 
    
    $mod_boms = array_diff($old_boms,$del_boms);//수정할 데이터
    //print_r2($mod_boms); 
    //exit;
    if(count($del_boms)){ //삭제해야 할 데이터가 있다면
        foreach($del_boms as $delb){
            //1. 삭제하고자 하는 ori_idx를 추출한다
            $ori_sql3 = " SELECT ori_idx FROM {$g5['order_item_table']} WHERE ord_idx = '{$ord_idx}' AND bom_idx = '{$delb}' AND ori_status NOT IN('del','delete','cancel','trash') ";
            $ori3 = sql_fetch($ori_sql3);
            //2. 출하계획 레코드 중에 해당ord_idx에다 해당ori_idx가 있는지 확인해라
            $oro_sql3 = " SELECT COUNT(*) AS cnt FROM {$g5['order_out_table']} WHERE ord_idx = '{$ord_idx}' AND ori_idx = '{$ori3['ori_idx']}' AND oro_status NOT IN('del','delete','cancel','trash') ";
            $oro3 = sql_fetch($oro_sql3);
            $oro_flag3 = ($oro3['cnt']) ? true : false;
            //3. 출하계획에서 해당 ord_idx에다 해당ori_idx가 있으면 해당 출하계획 레코드를 삭제처리해라
            if($oro_flag3){
                $oop_sql4 = " SELECT COUNT(*) AS cnt FROM {$g5['order_out_practice_table']} WHERE ord_idx = '{$ord_idx}' AND ori_idx = '{$ori3['ori_idx']}' AND oop_status NOT IN('del','delete','cancel','trash') ";
                $oop4 = sql_fetch($oop_sql4);
                $oop_flag4 = ($oop4['cnt']) ? true : false;
                //단 해당 ord_idx의 ori_idx의 생산계획 레코드가 존재하면 출하계획을 삭제할 수 없다.
                if($oop_flag4){
                    alert('출하계획과 생산계획 모두에 등록된 제품은 삭제처리를 할 수 없습니다.');
                    exit;
                }
                //출하계획 해당 레코드 삭제
                $oro_sql4 = " UPDATE {$g5['order_out_table']} SET
                                oro_status = 'trash'
                            WHERE ord_idx = '{$ord_idx}' AND ori_idx = '{$ori3['ori_idx']}'
                ";
                sql_query($oro_sql4,1);
            }

            $sql = " UPDATE {$g5['order_item_table']} SET
                        ori_status = 'trash'
                    WHERE ord_idx = '{$ord_idx}' AND bom_idx = '{$delb}'
            ";
            sql_query($sql,1);
        }
    }

    if(count($add_boms)){ //추가해야할 데이터가 있다면
        foreach($add_boms as $addb){
            //print_r2($data_k[$addb]);
            $sql = " INSERT into {$g5['order_item_table']} SET
                        com_idx = '{$com_idx}',
                        com_idx_customer = '{$data_k[$addb]['com_idx_customer']}',
                        ord_idx = '{$ord_idx}',
                        bom_idx = '{$data_k[$addb]['bom_idx_child']}',
                        ori_count = '{$data_k[$addb]['bit_count']}',
                        ori_price = '{$data_k[$addb]['ori_price']}',
                        ori_status = 'ok',
                        ori_reg_dt = '".G5_TIME_YMDHIS."',
                        ori_update_dt = '".G5_TIME_YMDHIS."'
            ";
            //echo $sql."<br>";
            sql_query($sql,1);
			$ori_idx = sql_insert_id();

            //1. 출하계획 레코드 중에 해당ord_idx가 있는지 확인해라
            $oro_sql = " SELECT COUNT(*) AS cnt FROM {$g5['order_out_table']} WHERE ord_idx = '{$ord_idx}' AND oro_status NOT IN('del','delete','cancel','trash') ";
            $oro = sql_fetch($oro_sql);
            $oro_flag = ($oro['cnt']) ? true : false;
            //2. 출하계획 레코드 중에 해당ord_idx에다 해당ori_idx가 있는지 확인해라
            $oro_sql2 = " SELECT COUNT(*) AS cnt FROM {$g5['order_out_table']} WHERE ord_idx = '{$ord_idx}' AND ori_idx = '{$ori_idx}' AND oro_status NOT IN('del','delete','cancel','trash') ";
            $oro2 = sql_fetch($oro_sql2);
            $oro_flag2 = ($oro2['cnt']) ? true : false;
            
            //oro_flag=true AND oro_flag2=false 이면 출하계획에 해당 ord_idx로 ori_idx를 새로 등록하자
            if($oro_flag && !$oro_flag2){
                $osql = " INSERT into {$g5['order_out_table']} SET
                            com_idx = '{$com_idx}',
                            com_idx_customer = '{$data_k[$addb]['com_idx_customer']}',
                            ord_idx = '{$ord_idx}',
                            ori_idx = '{$ori_idx}',
                            oro_count = '{$data_k[$addb]['bit_count']}',
                            com_idx_shipto = '{$data_k[$addb]['com_idx_customer']}',
                            oro_status = 'pending',
                            oro_reg_dt = '".G5_TIME_YMDHIS."',
                            oro_update_dt = '".G5_TIME_YMDHIS."',
                            oro_1 = '{$data_k[$addb]['bit_count']}'
                ";
                sql_query($osql,1);
            }
        }
        //exit;
    }

    if(count($mod_boms)){ //수정해야할 데이터가 있다면
        foreach($mod_boms as $modb){
            print_r2($data_k[$modb]);
            $sql = " UPDATE {$g5['order_item_table']} SET
                        com_idx = '{$com_idx}',
                        com_idx_customer = '{$data_k[$modb]['com_idx_customer']}',
                        ord_idx = '{$ord_idx}',
                        bom_idx = '{$data_k[$modb]['bom_idx_child']}',
                        ori_count = '{$data_k[$modb]['bit_count']}',
                        ori_price = '{$data_k[$modb]['ori_price']}',
                        ori_status = 'ok',
                        ori_update_dt = '".G5_TIME_YMDHIS."'
                    WHERE ord_idx = '{$ord_idx}' AND bom_idx = '{$modb}'
            ";
            //echo $sql."<br>";
            sql_query($sql,1);
        }
        //exit;
    }
}

$qstr .= '&sca='.$sca; //.'&file_name='.$file_name 추가로 확장해서 넘겨야 할 변수들
//goto_url('./order_list.php?'.$qstr, false);
goto_url('./order_form.php?'.$qstr.'&w=u&ord_idx='.$ord_idx, false);