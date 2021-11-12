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
            [bom_name] => 
            [com_idx_customer] => 37
            [bom_idx_child] => 3
            [bit_count] => 1000
            [ori_price] => 1000
        )

    [1] => Array
        (
            [id] => 2
            [depth] => 0
            [bom_name] => 
            [com_idx_customer] => 37
            [bom_idx_child] => 2
            [bit_count] => 2000
            [ori_price] => 1000
        )

    [2] => Array
        (
            [id] => 3
            [depth] => 0
            [bom_name] => 
            [com_idx_customer] => 36
            [bom_idx_child] => 1
            [bit_count] => 3000
            [ori_price] => 780
        )

    [3] => Array
        (
            [id] => 4
            [depth] => 0
            [bom_name] => 
            [com_idx_customer] => 34
            [bom_idx_child] => 3
            [bit_count] => 4000
            [ori_price] => 1000
        )

)
*/

$data = json_decode(stripslashes($_POST['serialized']),true);
$data_k = array();
foreach($data as $dta){
    $data_k[$dta['bom_idx_child']] = $dta;
}
// print_r2($data);
// exit;
if(count($data) == 0) alert('적어도 상품 한 개 이상은 등록해 주세요.');

$ord_price = str_replace(',','',trim($ord_price));

$sql_common = " com_idx = '{$com_idx}',
                com_idx_customer = '{$com_idx_customer}',
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
    $old_oris = array();
    $mod_oris = array();
    $add_boms = array();
    $old_r = sql_fetch(" SELECT GROUP_CONCAT(ori_idx) AS ori_idxs FROM {$g5['order_item_table']} WHERE ord_idx = '{$ord_idx}' AND ori_status NOT IN ('del','delete','trash','cancel') ");
    $old_oris = ($old_r['ori_idxs']) ? explode(',',$old_r['ori_idxs']) : array();
    //기존의 수주품목이 전혀 없을때
    if(!count($old_oris)){
        $add_boms = $data;
    }
    //기존의 수주품목이 1개이상 존재할때
    else{
        foreach($data as $d){
            $msql = sql_fetch(" SELECT ori_idx FROM {$g5['order_item_table']} WHERE ord_idx = '{$ord_idx}' AND com_idx_customer = '{$d['com_idx_customer']}' AND bom_idx = '{$d['bom_idx_child']}' AND ori_status NOT IN ('del','delete','trash','cancel') ");
            $d_ori_idx = $msql['ori_idx'];
            //해당 ori_idx가 존재하면 $d정보로 수정해라
            if($d_ori_idx){
                array_push($mod_oris,$d_ori_idx); //수정한 ori_idx들을 제외한 나머지는 삭제하기 위해 $mod_oris에 담아둔다
                $d_mod_sql = " UPDATE {$g5['order_item_table']} SET
                                com_idx = '{$com_idx}',
                                com_idx_customer = '{$d['com_idx_customer']}',
                                ord_idx = '{$ord_idx}',
                                bom_idx = '{$d['bom_idx_child']}',
                                ori_count = '{$d['bit_count']}',
                                ori_price = '{$d['ori_price']}',
                                ori_status = 'ok',
                                ori_update_dt = '".G5_TIME_YMDHIS."'
                            WHERE ori_idx = '{$d_ori_idx}'
                ";
                sql_query($d_mod_sql,1);
            }
            //해당 ori_idx가 존재하지 않으면 새로 생성해야 하니 $add_boms에 담아라
            else{
                array_push($add_boms,$d);
            }
        }
    }
    //수정했던 데이터들이 있으면 그 외 데이터는 전부 삭제해라
    if(count($mod_oris)){
        $ex_oris = implode(',',$mod_oris);
        $del_sql = " UPDATE {$g5['order_item_table']} SET
                        ori_status = 'trash'
                    WHERE com_idx = '{$com_idx}' AND ord_idx = '{$ord_idx}' AND ori_idx NOT IN({$ex_oris}) AND ori_status NOT IN('del','delete','trash','cancel')
        ";
        sql_query($del_sql,1);
    }

    //새로운 데이터 레코드를 추가해라
    if(count($add_boms)){
        $add_sql = " INSERT into {$g5['order_item_table']} (com_idx,com_idx_customer,ord_idx,bom_idx,ori_count,ori_price,ori_status,ori_reg_dt,ori_update_dt) VALUES ";
        $k = 0;
        foreach($add_boms as $add){
            $add_sql .= ($k == 0) ? '' : ',';
            $add_sql .= " ( '{$com_idx}',
                        '{$add['com_idx_customer']}',
                        '{$ord_idx}',
                        '{$add['bom_idx_child']}',
                        '{$add['bit_count']}',
                        '{$add['ori_price']}',
                        'ok',
                        '".G5_TIME_YMDHIS."',
                        '".G5_TIME_YMDHIS."' )
            ";
            $k++;
        }
        sql_query($add_sql,1);
    }
}

$qstr .= '&sca='.$sca; //.'&file_name='.$file_name 추가로 확장해서 넘겨야 할 변수들
//goto_url('./order_list2.php?'.$qstr, false);
goto_url('./order_form2.php?'.$qstr.'&w=u&ord_idx='.$ord_idx, false);