<?php
/**
 * coppy right 07-80-2015
 * 
 * function name - export chart to php from excel
 * 
 * description func : export($name,$array);
 * 
 * $name: ten cua bieu do
 * 
 * $array: mang 2 chieu chua du lieu cua bieu do, co dang:
 * --- $array =  array( 0=>array(0=>XXXX,1=>title1,2=>title2,3=>title3 ...),1=>array(0=>value_row,1=>data_title,2=>data_title,3=>data_titl ...)...);
 * ++ mang con dau tien chua tieu de cac cot, voi key 0 bo trong.
 * ++ cac mang con lai chua du lieu tuong ung cua cac tieu de, voi key 0 la tieu de hang.
 * 
 * Array(
    [0] => Array
        (
            [0] => 
            [1] => title col 1
            [2] => title col 2
            [3] => title col 3
            [4] => title col 4
            [5] => title col 5
            [6] => title col 6
            [7] => title col 7
            [8] => title col 8
        )

    [1] => Array
        (
            [0] => title row 1
            [1] => data title 1
            [2] => data title 2
            [3] => data title 3
            [4] => data title 4
            [5] => data title 5
            [6] => data title 6
            [7] => data title 7
            [8] => data title 8
        )
    [2] => Array
        (
            [0] => title row 2
            [1] => data title 1
            [2] => data title 2
            [3] => data title 3
            [4] => data title 4
            [5] => data title 5
            [6] => data title 6
            [7] => data title 7
            [8] => data title 8
        )
    )
 * 
*/


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');


set_include_path(get_include_path() . PATH_SEPARATOR . '../Classes/');

require_once 'exportexcel/Classes/PHPExcel.php';

function export($name_chart,$data)
{
    $objPHPExcel = new PHPExcel();
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $objWorksheet->fromArray($data);
    /** lay ra tieu de cac cot **/
    $arr_title_col = $data[0];
    
    /** khai bao tieu de cac cot **/
    $dataseriesLabels = array(); $key_arr = 0;
    foreach($arr_title_col as $key_title=>$value_title)
    {
        if($key_title!=0)
        {
            $col_title = convert_number_to_col_excel($key_title+1);
            $dataseriesLabels += array($key_arr=>new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$'.$col_title.'$1', null, 1));
            $key_arr++;
        }
    }
    //$dataseriesLabels = array(
//    	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$1', null, 1),
//    	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', null, 1),
//    	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', null, 1),
//        new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$E$1', null, 1),
//        new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$F$1', null, 1),
//        new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$G$1', null, 1),
//        new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$H$1', null, 1),
//        new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$I$1', null, 1),
//    );
    
    /** khai bao so luong hang **/
    $num_row = sizeof($data);
    $count = $num_row--;
    $xAxisTickValues = array(
    	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$'.$num_row, null, $count),
    );
    
    /** khai bao du lieu cho tung hang **/
    $dataSeriesValues = array();$key_arr = 0;
    foreach($arr_title_col as $key_title=>$value_title)
    {
        if($key_title!=0)
        {
            $col_title = convert_number_to_col_excel($key_title+1);
            $dataSeriesValues += array($key_arr=>new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$'.$col_title.'$2:$'.$col_title.'$'.$num_row, null, $count));
            $key_arr++;
        }
    }
    
    //$dataSeriesValues = array(
//    	new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$2:$B$32', null, 31),
//    	new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$32', null, 31),
//    	new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$D$2:$D$32', null, 31),
//        new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$E$2:$E$32', null, 31),
//        new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$F$2:$F$32', null, 31),
//        new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$G$2:$G$32', null, 31),
//        new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$H$2:$H$32', null, 31),
//        new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$I$2:$I$32', null, 31),
//    );
    
    
    $series = new PHPExcel_Chart_DataSeries(
    	PHPExcel_Chart_DataSeries::TYPE_PIECHART,
    	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
    	range(0, count($dataSeriesValues)-1),
    	$dataseriesLabels,
    	$xAxisTickValues,
    	$dataSeriesValues
    );
    
    $layout = new PHPExcel_Chart_Layout();
    $layout->setShowVal(TRUE);
    $layout->setShowPercent(TRUE);
    
    $plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
    
    $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, null, false);
    
    $title = new PHPExcel_Chart_Title($name_chart);
    $xAxisLabel = new PHPExcel_Chart_Title('xAxisLabel');
    $yAxisLabel = new PHPExcel_Chart_Title('yAxisLabel');
    
    $chart = new PHPExcel_Chart(
    	'chart2',
    	$title,
    	$legend,
    	$plotarea,
    	true,
    	0,
    	$xAxisLabel,
    	$yAxisLabel	
    );
    
    /** xet vi tri cua bieu do **/
    $num_col = sizeof($arr_title_col)+2;
    $chart->setTopLeftPosition(convert_number_to_col_excel($num_col).'1');
    $chart->setBottomRightPosition(convert_number_to_col_excel(sizeof($data)+$num_col).sizeof($data));
    $objWorksheet->addChart($chart);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->setIncludeCharts(TRUE);
    $objWriter->save(str_replace('.php','.xlsx', __FILE__));
}

function convert_number_to_col_excel($number)
{
    $string_col = convert_number_sub_arr($number,"");
    if(strpos($string_col,","))
    {
        $name_col = '';
        $arr = explode(",",$string_col);
        for($i=0;$i<sizeof($arr);$i++)
        {
            $name_col .= convert_number_to_char_excel($arr[$i]);
        }
        return $name_col;
    }
    else
    {
        return convert_number_to_char_excel($string_col);
    }
}
function convert_number_sub_arr($number,$string)
{
    if($number<=26)
    {
        if($string=='')
            $string = $number;
        else
        $string = $number.",".$string;
        
        return $string;
    }
    else
    {
        $thuong = intval($number/26);
        if($string=='')
            $string = ($number%26);
        else
        $string .= ",".($number%26);
        
        return convert_number_sub_arr($thuong,$string);
    }
}
function convert_number_to_char_excel($number)
{
    if($number==1)
        return "A";
    if($number==2)
        return "B";
    if($number==3)
        return "C";
    if($number==4)
        return "D";
    if($number==5)
        return "E";
    if($number==6)
        return "F";
    if($number==7)
        return "G";
    if($number==8)
        return "H";
    if($number==9)
        return "I";
    if($number==10)
        return "J";
    if($number==11)
        return "K";
    if($number==12)
        return "L";
    if($number==13)
        return "M";
    if($number==14)
        return "N";
    if($number==15)
        return "O";
    if($number==16)
        return "P";
    if($number==17)
        return "Q";
    if($number==18)
        return "R";
    if($number==19)
        return "S";
    if($number==20)
        return "T";
    if($number==21)
        return "U";
    if($number==22)
        return "V";
    if($number==23)
        return "W";
    if($number==24)
        return "X";
    if($number==25)
        return "Y";
    if($number==26)
        return "Z";
}

?>