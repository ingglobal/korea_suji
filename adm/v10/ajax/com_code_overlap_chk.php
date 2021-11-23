<?php
include_once('./_common.php');

$com_idx = $_POST['com_idx'];
$com_code = trim($_POST['com_code']);
$msg = '';
$sql = "select COUNT(*) AS cnt, com_idx
        from {$g5['company_table']}
        where com_status NOT IN ('delete','del','trash','cancel') AND com_idx_par ='".$_SESSION['ss_com_idx']."'  AND com_code = '".$com_code."' 
";
$row = sql_fetch($sql);
/*
echo $com_idx;
echo gettype($com_idx);
echo $row['com_idx'];
echo gettype($row['com_idx']);exit;
*/
//
if($row['cnt'] == '1'){
    if($com_idx == $row['com_idx']){
        $msg = 'same';
    }
    else{
        $msg = 'overlap';
    }
}
else{
   $msg = 'ok'; 
}

echo $msg;