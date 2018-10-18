<?php
class SynExcelWarehouseForm extends Form
{
	function SynExcelWarehouseForm()
	{
		Form::Form('SynExcelWarehouseForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');		
	}
	function on_submit()
	{
		
	}
    
    //export xuat kho trong khoang thoi gian
    function export_wh_export($from,$to)
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Mã khách hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Người nhận hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Quyển sổ');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Số chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Ngày chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Số lượng');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Giá');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Tiền');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Tk nợ');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Tk có');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Mã kho');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Mã vật tư');
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Diễn giải');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Mã giao dịch');
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'Mã dự án');
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'Mã ĐVCS');
        
        //du lieu
		$wh_export = DB::fetch_all('
			select wh_invoice_detail.id,
              wh_invoice.receiver_name,
              wh_invoice.bill_number,
              wh_invoice.create_date,
              wh_invoice_detail.num,
              wh_invoice_detail.price,
              wh_invoice_detail.price * wh_invoice_detail.num as amount,
              warehouse.code,
              wh_invoice_detail.product_id,
              wh_invoice.note
            from wh_invoice
              inner join wh_invoice_detail on wh_invoice_detail.invoice_id = wh_invoice.id
              inner join warehouse on warehouse.id = wh_invoice.warehouse_id
            where date_to_unix(wh_invoice.create_date) >= '.$from.' 
              and date_to_unix(wh_invoice.create_date) <= '.$to.'
		');
		$i++;
		foreach($wh_export as $key=>$value)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, '');
    		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['receiver_name']);
    		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, '');
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['bill_number']);
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, Date_Time::convert_orc_date_to_date($value['create_date'],'/'));
    		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['num']);
    		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['amount']);
    		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '');
    		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '');
    		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $value['code']);
    		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value['product_id']);
    		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['note']);
    		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '');
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '');
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '');
			$i++;				
		}
		$fileName = "warehouse_export_".date('d-m-y',$from)."-to-".date('d-m-y',$to).".xls";
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
			echo 'alert(" Export dữ liệu không thành công !");';
			echo '</script>';
		}		
	}
    
	function draw()
	{
		$_REQUEST['date_from'] = Url::get('date_from','01/'.date('m/Y'));
		$_REQUEST['date_to'] = Url::get('date_to',date('d/m/Y'));
		$this->parse_layout('warehouse');
        if(Url::get('act'))
        {
            require_once 'packages/core/includes/utils/vn_code.php';
    		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
    		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
    		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
    		//set_time_limit(0);
    		$from = Date_Time::to_time($_REQUEST['date_from']);
    		$to = Date_Time::to_time($_REQUEST['date_to'])+24*3600;
    		if(!is_dir('export/'.date('d-m-Y')))
    		{
    			mkdir('export/'.date('d-m-Y'));
    		}
            switch(Url::get('cmd'))
            {
                case "wh_export":
                {
                    $this->export_wh_export($from,$to);break;
                }
                default :
                {
                        
                }
            }
        }
	}

}
?>