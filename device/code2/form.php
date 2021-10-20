<?php
include_once('./_common.php');
// 인스타 로그인후 인스타쪽 피드 배열을 받아서 올스타에 업데이트하는 폼을 임시로 구현한 페이지입니다.
// 실제로는 member_log.php가 크롤링 서버에서 정보를 받아서 저장합니다.
// 파일명이 실제로는 member_feed_log.php가 더 적합할 수도..

include(G5_PATH.'/head.sub.php');
?>

<form id="form01" action="./index.php">

Token(암호코드)
<table>
	<tr><td>토큰값</td><td><input type="text" name="token" value="1099de5drf09"></td></tr>
</table>

<hr>
com_idx
<table>
	<tr><td>업체 idx1</td><td><input type="text" name="com_idx" value="<?=rand(1,100)?>"></td></tr>
</table>

<hr>
imp_idx
<table>
	<tr><td>IMP idx1</td><td><input type="text" name="imp_idx" value="<?=rand(1,100)?>"></td></tr>
</table>

<hr>
mms_idx (프레스, 트랜스퍼, 인덕션히트...)
<table>
	<tr><td>MMS idx1</td><td><input type="text" name="mms_idx" value="<?=rand(1,100)?>"></td></tr>
</table>

<hr>
cod_code
<table>
	<tr><td>코드값1</td><td><input type="text" name="cod_code" value="M0100"></td></tr>
</table>

<hr>
cod_group '구분(err,run,product,pre,mea..)'
<table>
	<tr><td>구분1</td><td><input type="text" name="cod_group" value="err"></td></tr>
</table>

<hr>
cod_type 'r=저장, a=알람, p=주기'
<table>
	<tr><td>타입1</td><td><input type="text" name="cod_type" value="r"></td></tr>
</table>

<hr>
cod_interval '단위시간, p=주기인 경우에만 의미있음 (초단위로 입력됨)'
<table>
	<tr><td>단위시간1</td><td><input type="text" name="cod_interval" value="1800">(30분)</td></tr>
</table>

<hr>
cod_count '발생빈도'
<table>
	<tr><td>발생빈도1</td><td><input type="text" name="cod_count" value="<?=rand(1,10)?>"></td></tr>
</table>

<hr>
cod_condition '예지조건'
<table>
	<tr><td>예지조건1</td><td><input type="text" name="cod_condition" value="M40AA"></td></tr>
</table>

<hr>
cod_name
<table>
	<tr><td>코드이름1</td><td><input type="text" name="cod_name" value="비상정지"></td></tr>
</table>

<hr>
cod_memo
<table>
	<tr><td>메모1</td><td><input type="text" name="cod_memo" value="비상정지가 발생했습니다."></td></tr>
</table>


<hr>
<button type="submit" id="btn_submit">확인</button>
</form>


<script>
jQuery.fn.serializeObject = function() {
    var obj = null; 
    try { 
      if(this[0].tagName && this[0].tagName.toUpperCase() == "FORM" ) { 
          var arr = this.serializeArray(); 
          if(arr){ obj = {}; 
          jQuery.each(arr, function() { 
              obj[this.name] = this.value; }); 
          } 
      } 
    }catch(e) { 
      alert(e.message); 
    }finally {} 
    return obj; 
}


$(document).on('click','#btn_submit',function(e) {
    e.preventDefault();
    const serializedValues2 = $('#form01').serializeObject()
    $.ajax({
        url:'./index.php',
        type:'post',
        data : JSON.stringify(serializedValues2),
        dataType:'json',
        timeout:10000, 
        beforeSend:function(){},
        success:function(res){
//            var items;
//            for(items in res) { alert(items +': '+ res[items]); }
            alert('데이터 입력 성공, 입력값은 디비에서 확인해 주세요.');
            console.log(res);
        },
        error:function(xmlRequest) {
            alert('Status: ' + xmlRequest.status + ' \n\rstatusText: ' + xmlRequest.statusText 
            + ' \n\rresponseText: ' + xmlRequest.responseText);
        }
    });
});
</script>


<?php
include(G5_PATH.'/tail.sub.php');
?>