<?php
include_once('./_head.sub.php');
$g5['title'] = "g5_1_company 테이블 리셋하고 새로 등록하기";
$xls = G5_USER_ADMIN_SQL_PATH.'/xls/g5_1_company.xls';
$xls_exist = @is_file($xls);
?>
<h2><?=$g5['title']?></h2>
<div><a id="btn_start" href="<?=G5_USER_ADMIN_SQL_URL?>/company_insert.php?start=1">시작</a></div>
<?php
if($start){
if($xls_exist){
?>
<?php
$demo = 0;  // 데모모드 = 1

// ref: https://github.com/PHPOffice/PHPExcel
require_once G5_LIB_PATH."/PHPExcel-1.8/Classes/PHPExcel.php"; // PHPExcel.php을 불러옴.
$objPHPExcel = new PHPExcel();
require_once G5_LIB_PATH."/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php"; // IOFactory.php을 불러옴.
$filename = $xls;//$_FILES['file_excel']['tmp_name'];
PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);

// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
$filename = iconv("UTF-8", "EUC-KR", $filename);

//전체 엑셀 데이터를 담을 배열을 선언한다.
$allData = array();
$headArr = array();
$contArr = array();
$colStr = '';
try {
    // 업로드한 PHP 파일을 읽어온다.
	$objPHPExcel = PHPExcel_IOFactory::load($filename);
	$sheetsCount = $objPHPExcel -> getSheetCount();

	// 시트Sheet별로 읽기
	for($i = 0; $i < $sheetsCount; $i++) {       
        $objPHPExcel -> setActiveSheetIndex($i);
        $sheet = $objPHPExcel -> getActiveSheet();
        $highestRow = $sheet -> getHighestRow();   			           // 마지막 행
        $highestColumn = $sheet -> getHighestColumn();	// 마지막 컬럼
        // 한줄읽기
        for($row = 1; $row <= $highestRow; $row++) {
            //if($row > 41) break;
            // $rowData가 한줄의 데이터를 셀별로 배열처리 된다.
            $rowData = $sheet -> rangeToArray("A" . $row . ":" . $highestColumn . $row, NULL, TRUE, FALSE);
            
            $allData[$row] = $rowData[0];
            if($row == 1){
                $headArr = $rowData[0];
                for($i=0;$i<count($headArr);$i++){
                    $colStr .= ($i == 0) ? "`".$headArr[$i]."`" : ",`".$headArr[$i]."`";
                }
            }
            else array_push($contArr,$rowData[0]);
        }
	}

} catch(exception $e) {
	echo $e;
    exit;
}

//print_r2($headArr);
//print_r2($contArr);
?>

<?php
$cnt = 0;
if(count($contArr)){
    //기존 g5_1_company 테이블 리셋하자
    $truncate_sql = " TRUNCATE {$g5['company_table']} ";
    sql_query($truncate_sql,1);
    $sql = " INSERT {$g5['company_table']} ( {$colStr} ) VALUES ";
    for($i=0;$i<count($contArr);$i++){
        $cnt++;
        $sql .= ($i == 0) ? '(' : ',(';
        for($j=0;$j<count($contArr[$i]);$j++){
            //com_code (3번째 열에 있는 값이니 index값은 2이다)의 경우는 대문자로 등록해라
            $contArr[$i][$j] = ($j == 2) ? strtoupper($contArr[$i][$j]) : $contArr[$i][$j];
            
            //첫번째 항목앞에는 (,)쉼표를 붙이면 안된다.
            $sql .= ($j == 0) ? "'".$contArr[$i][$j]."'" : ",'".$contArr[$i][$j]."'";
        }
        $sql .= ')';
    }
    sql_query($sql,1);
    echo $sql;
}
?>

<?php
} //if($xls_exist)
else echo '해당파일일 존재하지 않습니다.';
} //if($start)
include_once('./_tail.sub.php');