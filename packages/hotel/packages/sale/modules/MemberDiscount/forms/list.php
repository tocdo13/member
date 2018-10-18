<?php
class MemberDiscountForm extends Form
{
	function MemberDiscountForm()
	{
		Form::Form('MemberDiscountForm');
	}
	function draw()
	{
        $this->map = array();
        $cond = '1=1';
        if(Url::get('keyword'))
        {
            $cond .= ' AND (member_discount.code LIKE  \'%'.Url::get('keyword').'%\' 
                    OR member_discount.title like \'%'.Url::get('keyword').'%\')
                    OR member_discount.description like \'%'.Url::get('keyword').'%\')';
        }
        $this->map['items'] = DB::fetch_all("
                                            SELECT 
                                                member_discount.*
                                                ,to_char(member_discount.start_date,'DD/MM/YYYY') as start_date
                                                ,to_char(member_discount.end_date,'DD/MM/YYYY') as end_date
                                                ,nvl(member_discount.operator,'=') || member_discount.num_people as number_people
                                            FROM 
                                                member_discount  
                                            WHERE 
                                                ".$cond." 
                                            ORDER BY
                                                member_discount.code DESC");
        $this->map['items_js'] = String::array2js($this->map['items']);
        $this->parse_layout('list',$this->map);
        /** Kimtan them **/
        if(Url::get('export_file_excel'))
        {
            $this->export_file_excel($cond);
        }
        /** Kimtan them **/
	}
    function export_file_excel($cond)
    {
        require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
  		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
  		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
        
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'STT');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Mã');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Loại thẻ');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Điểm dịch vụ');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Tiêu đề');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Ngày bắt đầu');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Ngày kết thúc');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Số người');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Diễn giải');
        $export = DB::fetch_all("SELECT member_discount.*,member_discount.operator || member_discount.num_people as number_people FROM member_discount WHERE ".$cond." ORDER BY member_discount.access_pin_service_code");
        $i++;
		foreach($export as $key=>$value)
		{
		    if($value['is_parent']=='PARENT'){ $is_parent = Portal::language('parent_card');}elseif($value['is_parent']=='SON'){ $is_parent = Portal::language('son_card');}else{ $is_parent =Portal::language('all');}
            $start_date = Date_time::convert_orc_date_to_date($value['start_date'],'/');
            $end_date = Date_time::convert_orc_date_to_date($value['end_date'],'/');
            //gÃ¡n cÃ¡c truong
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $i-1);
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['code']);
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $is_parent);
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['access_pin_service_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['title']);
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $start_date);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $end_date);
    		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['number_people']);
    		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['description']);
            $i++;
        }
        //System::debug($export);exit();
        $fileName = "Chinh_sach_giam_gia".".xls";
        //System::debug($objPHPExcel); exit();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileName);
		if(file_exists($fileName))
        {
			echo '<script>';
			echo 'window.location.href = \''.$fileName.'\';';
			echo ' </script>';
		}else{
			echo '<script>';
			echo 'alert(" Export dá»¯ liá»‡u khÃ´ng thÃ nh cÃ´ng !");';
			echo '</script>';
		}
        //System::debug($export);exit();
    }
}
?>