<?php
include_once('./_common.php');

$bct_id = trim($_POST['bct_id']);

$strlen = strlen($bct_id);
$cnt = $strlen / 2;
$namestr = '';
for($i=1;$i<=$cnt;$i++){
    $id = substr($bct_id,0,$i*2);
    $sql = "select bct_name
            from {$g5['bom_category_table']}
            where bct_id = '{$id}' AND com_idx ='".$_SESSION['ss_com_idx']."' 
    ";
    $bct_name_arr = sql_fetch($sql);
    $bct_name = $bct_name_arr['bct_name'];
    $namestr .= $bct_name.'-';
}
echo $namestr;