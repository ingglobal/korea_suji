<?php
include_once('./_common.php');

$bom_part_no = trim($_POST['bom_part_no']);
$msg = '';
$sql = "select COUNT(*) AS cnt 
        from {$g5['bom_table']}
        where bct_id = '{$bct_id}' AND bom_status NOT IN ('delete','del','trash','cancel') AND com_idx ='".$_SESSION['ss_com_idx']."'  AND bom_part_no = '".$bom_part_no."' 
";
$row = sql_fetch($sql);
//
if($row['cnt']){
    $msg = 'overlap';
}
else{
   $msg = 'ok'; 
}

echo $msg;