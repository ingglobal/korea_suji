<?php
$sub_menu = "915165";
include_once('./_common.php');

auth_check($auth[$sub_menu],'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'offwork';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form/","",$g5['file_name']); // _form을 제외한 파일명
$qstr .= '&ser_mms_idx='.$ser_mms_idx; // 추가로 확장해서 넘겨야 할 변수들

// print_r3($member);
// print_r3($_SESSION);

if ($w == '') {
    $sound_only = '<strong class="sound_only">필수</strong>';
    $w_display_none = ';display:none';  // 쓰기에서 숨김
    
    ${$pre}['com_idx'] = $_SESSION['ss_com_idx'];
    ${$pre}['mms_idx'] = 0;
    ${$pre}['off_period_type'] = 1;
    // ${$pre}['mms_idx'] = rand(1,4);
    ${$pre}[$pre.'_start_time'] = G5_SERVER_TIME;
    ${$pre}[$pre.'_end_time'] = G5_SERVER_TIME+3600*3;
    ${$pre}[$pre.'_status'] = 'ok';
}
else if ($w == 'u' || $w == 'c') {
    $u_display_none = ';display:none;';  // 수정에서 숨김

	${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');
	$com = get_table_meta('company','com_idx',${$pre}['com_idx']);
    $mms = get_table_meta('mms','mms_idx',${$pre}['mms_idx']);

}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 날짜 표현
${$pre}[$pre.'_start_date'] = date("Y-m-d",${$pre}[$pre.'_start_time']);
${$pre}[$pre.'_end_date'] = date("Y-m-d",${$pre}[$pre.'_end_time']);
${$pre}[$pre.'_start_his'] = date("H:i:s",${$pre}[$pre.'_start_time']);
${$pre}[$pre.'_end_his'] = date("H:i:s",${$pre}[$pre.'_end_time']);

// 라디오&체크박스 선택상태 자동 설정 (필드명 배열 선언!)
$check_array=array('mb_gender');
for ($i=0;$i<sizeof($check_array);$i++) {
	${$check_array[$i].'_'.${$pre}[$check_array[$i]]} = ' checked';
}

$html_title = ($w=='')?'추가':'수정'; 
$html_title = ($w=='c')?'복제':$html_title; 
$g5['title'] = '공제시간 '.$html_title;
//include_once('./_top_menu_data.php');
include_once ('./_head.php');
//echo $g5['container_sub_title'];

// 각 항목명 및 항목 설정값 정의, 형식: 항목명, required, 폭, 단위(개, 개월, 시, 분..), 설명, tr숨김, 한줄두항목여부
$items1 = array(
    "com_idx"=>array("업체번호","readonly",60,0,'','',2)
    ,"mms_idx"=>array("설비번호","required",60,0,'','',0)
    ,"off_name"=>array("공제시간명칭","",250,'','','',0)
    ,"off_period_type"=>array("적용기간","required",75,0,'전체기간을 선택하면 해당 설비에 대하여 전체 기간 동안 적용됩니다. 기간을 선택하고 입력하면 전체 기간 상관없이 우선 적용됩니다.','',0)
    ,"off_start_time"=>array("시작시간","required",70,0,'시작시간은 17:00:00와 같이 입력하세요.','',2)
    ,"off_end_time"=>array("종료시간","",70,0,'종료시간은 23:59:59와 같이 끝단위까지 모두 입력하세요.','',0)
    ,"off_memo"=>array("메모","",70,0,'','',0)
);
?>
<style>
.frm_date {width:75px;}
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="<?=$pre?>_idx" value="<?php echo ${$pre."_idx"} ?>">
<input type="hidden" name="com_idx" value="<?php echo $_SESSION['ss_com_idx'] ?>">
<input type="hidden" name="ser_mms_idx" value="<?php echo $ser_mms_idx ?>">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>각종 고유번호(설비번호, IMP번호..)들은 내부적으로 다른 데이타베이스 연동을 통해서 정보를 가지고 오게 됩니다.</p>
</div>

<div class="tbl_frm01 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?></caption>
	<colgroup>
		<col class="grid_4" style="width:15%;">
		<col style="width:35%;">
		<col class="grid_4" style="width:15%;">
		<col style="width:35%;">
	</colgroup>
	<tbody>

	</tbody>
	</table>
</div>

<div class="btn_fixed_top">
    <a href="./<?=$fname?>_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
$(function() {
    // 기간선택, 전체기간
    $(document).on('click','input[name=off_period_type]',function(e){
        // 기간선택
        if( $(this).val() == '0' ) {
            // $('input[name=off_start_date]').attr('type','text').select().focus();
            $('input[name=off_start_date]').attr('type','text');
            $('input[name=off_end_date]').attr('type','text');
            $('.span_wave').show();
        }
        // 전체기간
        else {
            $('input[name=off_start_date]').attr('type','hidden');
            $('input[name=off_end_date]').attr('type','hidden');
            $('.span_wave').hide();
        }
    });

    // 설비선택, 전체설비
    $(document).on('click','input[name=mms_idx_radio]',function(e){
        if( $(this).val() == '0' ) {
            $('input[name=mms_idx]').attr('old_value',$('input[name=mms_idx]').val()).val('0').attr('type','hidden');
        }
        else {
            $('input[name=mms_idx]').val($('input[name=mms_idx]').attr('old_value')).attr('type','text').select().focus();
        }
    });

    $("input[name$=_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });

    // 가격 입력 쉼표 처리
	$(document).on( 'keyup','input[name$=_price]',function(e) {
//        console.log( $(this).val() )
//		console.log( $(this).val().replace(/,/g,'') );
        if(!isNaN($(this).val().replace(/,/g,'')))
            $(this).val( thousand_comma( $(this).val().replace(/,/g,'') ) );
	});

});

function form01_submit(f) {
    // 교대시간 체크

    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
