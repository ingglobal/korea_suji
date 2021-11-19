<?php
$sub_menu = '915125';
include_once('./_common.php');

check_demo();

auth_check_menu($auth, $sub_menu, "w");

check_admin_token();
if ($_POST['act_button'] == "일괄수정") {

    $post_bct_id_count = (isset($_POST['bct_id']) && is_array($_POST['bct_id'])) ? count($_POST['bct_id']) : 0;
    
    for ($i=0; $i<$post_bct_id_count; $i++)
    {
        $sql = " update {$g5['bom_category_table']}
        set bct_name    = '".$_POST['bct_name'][$i]."',
        bct_desc    = '".$_POST['bct_desc'][$i]."',
        bct_order   = '".sql_real_escape_string(strip_tags($_POST['bct_order'][$i]))."'
        where bct_id = '".sql_real_escape_string($_POST['bct_id'][$i])."'
        AND com_idx = '".$_SESSION['ss_com_idx']."'
        ";
        sql_query($sql,1);
    }
}
else if ($_POST['act_button'] == "분류환경변수설정반영") {
    $idarr = ['1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
    $cat1_vals = explode("\n", $g5['setting']['set_cat_1']);
    $cat1_vals = array_values(array_filter(array_map('trim',$cat1_vals)));
    $cat2_vals = explode("\n", $g5['setting']['set_cat_2']);
    $cat2_vals = array_values(array_filter(array_map('trim',$cat2_vals)));
    $cat3_vals = explode("\n", $g5['setting']['set_cat_3']);
    $cat3_vals = array_values(array_filter(array_map('trim',$cat3_vals)));

    //기존의 해당업체의 레코드를 전부 삭제한다.
    $all_del_sql = " DELETE FROM {$g5['bom_category_table']} WHERE com_idx = '".$_SESSION['ss_com_idx']."' ";
    sql_query($all_del_sql,1);

    if(count($cat1_vals)){
        $ist_sql = " INSERT INTO {$g5['bom_category_table']} (`bct_id`, `com_idx`, `bct_name`, `bct_desc`, `bct_order`, `bct_reg_dt`, `bct_update_dt`) VALUES ";
        for($i=0;$i<count($cat1_vals);$i++){
            $cd1 = $idarr[$i].'0';
            list($key,$value) = explode('=',$cat1_vals[$i]);
            $key = trim($key);
            $value = trim($value);
            //echo $cd1.'-'.$key.'-'.$value."<br>";
            $ist_sql .= ($i == 0) ? '' : ',';
            $ist_sql .= " ('{$cd1}','{$_SESSION['ss_com_idx']}','{$key}','{$value}','0','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."') ";
            if(count($cat2_vals)){
                for($j=0;$j<count($cat2_vals);$j++){
                    $cd2 = $cd1.$idarr[$j].'0';
                    list($key,$value) = explode('=',$cat2_vals[$j]);
                    $key = trim($key);
                    $value = trim($value);
                    //echo $cd2.'-'.$key.'-'.$value."<br>";
                    $ist_sql .= " ,('{$cd2}','{$_SESSION['ss_com_idx']}','{$key}','{$value}','0','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."') ";
                    if(count($cat3_vals)){
                        for($k=0;$k<count($cat3_vals);$k++){
                            $cd3 = $cd2.$idarr[$k].'0';
                            list($key,$value) = explode('=',$cat3_vals[$k]);
                            $key = trim($key);
                            $value = trim($value);
                            //echo $cd3.'-'.$key.'-'.$value."<br>";
                            $ist_sql .= " ,('{$cd3}','{$_SESSION['ss_com_idx']}','{$key}','{$value}','0','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."') ";
                        }
                    }
                }
            }
        }

        sql_query($ist_sql,1);
    }
}

goto_url("./bom_category_list.php?$qstr");