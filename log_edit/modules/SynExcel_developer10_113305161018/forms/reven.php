<?php
class SynExcelRevenForm extends Form
{
	function SynExcelRevenForm()
	{
		Form::Form('SynExcelRevenForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');		
	}
	
    //doanh thu
    function export_reven($from,$to)
	{
        //doanh thu tu le tan
        $sql = "select 'LT' || traveller_folio.id as id,
                    TO_CHAR(from_unixtime(folio.create_time),'DD/MM/YYYY') as ex_ngaychungtu,
                    TO_CHAR(from_unixtime(folio.create_time),'DD/MM/YYYY') as ex_ngayhachtoan,
                    --'LT' || TO_CHAR(folio.id,'000000') as ex_sochungtu,
                    'LT' || TO_CHAR(traveller_folio.reservation_id,'000000') as ex_sochungtu,
                    customer.id as ex_makhachhang,
                    customer.name as ex_tenkhachhang,
                    customer.address as ex_address,
                    customer.tax_code as ex_masothue,
                    traveller.first_name || '' || traveller.last_name as ex_guest,
                    traveller_folio.description as ex_diengiai,
                    traveller_folio.type as ex_masanpham,
                    case when traveller_folio.type = 'ROOM'
                        then 'Doanh thu tiền phòng'
                        when traveller_folio.type = 'EXTRA_SERVICE'
                        then 'Doanh thu dịch vụ mở rộng'
                        when traveller_folio.type = 'MINIBAR'
                        then 'Doanh thu minibar'
                        when traveller_folio.type = 'LAUNDRY'
                        then 'Doanh thu giặt là'
                        when traveller_folio.type = 'EQUIPMENT'
                        then 'Thu tiền đền bù'
                        when traveller_folio.type = 'BAR'
                        then 'Doanh thu nhà hàng - lễ tân thu hộ'
                        when traveller_folio.type = 'MASSAGE'
                        then 'Doanh thu spa - lễ tân thu hộ'
                        when traveller_folio.type = 'TELEPHONE'
                        then 'Doanh thu điện thoại'
                        when traveller_folio.type = 'DISCOUNT'
                        then 'Giảm giá'
                        else ''
                    end ex_tensanpham,
                    case when traveller_folio.type = 'DISCOUNT'
                        then 532
                        else 1311
                    end ex_tkno,
                    case when traveller_folio.type = 'ROOM'
                        then 5113
                        when traveller_folio.type = 'EXTRA_SERVICE'
                        then 5118
                        when traveller_folio.type = 'MINIBAR'
                        then 5111
                        when traveller_folio.type = 'LAUNDRY'
                        then 5113
                        when traveller_folio.type = 'EQUIPMENT'
                        then 5118
                        when traveller_folio.type = 'BAR'
                        then 5112
                        when traveller_folio.type = 'MASSAGE'
                        then 5113
                        when traveller_folio.type = 'TELEPHONE'
                        then 5113
                        when traveller_folio.type = 'DISCOUNT'
                        then 1311
                        else 5113
                    end ex_tkco,
                    customer.id as ex_doituong,
                    1 as ex_sl,
                    round(traveller_folio.amount * (1 + NVL(traveller_folio.service_rate,0)/100) * (1 + NVL(traveller_folio.tax_rate,0)/100)) as ex_dongia,
                    round(traveller_folio.amount * (1 + NVL(traveller_folio.service_rate,0)/100) * (1 + NVL(traveller_folio.tax_rate,0)/100)) as ex_thanhtien,
                    traveller_folio.tax_rate as ex_thue,
                    round(traveller_folio.amount * (1 + NVL(traveller_folio.service_rate,0)/100) * NVL(traveller_folio.tax_rate,0)/100) as ex_tienthue
                from traveller_folio
                    inner join folio on folio.id = traveller_folio.folio_id
                    inner join reservation on reservation.id = folio.reservation_id 
                    left outer join customer on  customer.id = reservation.customer_id
                    left outer join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                where not(foc_all = 1 or (foc is not null and type = 'ROOM'))
                    and type != 'DEPOSIT' and type != 'GROUP_DEPOSIT' 
                    and folio.create_time >= ".$from." and folio.create_time < ".($to+24*3600)." 
                order by folio.id";
        
        $result = DB::fetch_all($sql);
        
        //doanh thu nha hang
        
        $sql = "select 'NH' || bar_reservation.id as id,
                  TO_CHAR(from_unixtime(bar_reservation.time_out),'DD/MM/YYYY') as ex_ngaychungtu,
                  TO_CHAR(from_unixtime(bar_reservation.time_out),'DD/MM/YYYY') as ex_ngayhachtoan,
                  'NH' || TO_CHAR(bar_reservation.id,'000000') as ex_sochungtu,
                  customer.id as ex_makhachhang,
                  customer.name as ex_tenkhachhang,
                  customer.address as ex_address,
                  customer.tax_code as ex_masothue,
                  bar_reservation.receiver_name as ex_guest,
                  bar_reservation.note as ex_diengiai,
                  'BAR' as ex_masanpham,
                  'Doanh thu nhà hàng' as ex_tensanpham,
                  1311 as ex_tkno,
                  5112 as ex_tkco,
                  customer.id as ex_doituong,
                  1 as ex_sl,
                  bar_reservation.total as ex_dongia,
                  bar_reservation.total as ex_thanhtien,
                  bar_reservation.tax_rate as ex_thue,
                  round(bar_reservation.total/(1+NVL(bar_reservation.tax_rate,0)/100)*NVL(bar_reservation.tax_rate,0)) as ex_tienthue
                from bar_reservation
                  left outer join customer on  customer.id = bar_reservation.customer_id
                where bar_reservation.pay_with_room != 1 and bar_reservation.status = 'CHECKOUT'
                  and bar_reservation.time_out >= ".$from." and bar_reservation.time_out < ".($to+86400)." 
                order by bar_reservation.id";
        
        $result += DB::fetch_all($sql);
        
        //doanh thu spa
        
        $sql = "select 'SP' || massage_product_consumed.id as id,
                  TO_CHAR(from_unixtime(massage_product_consumed.time_out),'DD/MM/YYYY') as ex_ngaychungtu,
                  TO_CHAR(from_unixtime(massage_product_consumed.time_out),'DD/MM/YYYY') as ex_ngayhachtoan,
                  'SP' || TO_CHAR(massage_product_consumed.id,'000000') as ex_sochungtu,
                  '' as ex_makhachhang,
                  '' as ex_tenkhachhang,
                  '' as ex_address,
                  '' as ex_masothue,
                  massage_reservation_room.full_name as ex_guest,
                  massage_reservation_room.note as ex_diengiai,
                  'SPA' as ex_masanpham,
                  'Doanh thu spa' as ex_tensanpham,
                  1311 as ex_tkno,
                  5113 as ex_tkco,
                  '' as ex_doituong,
                  1 as ex_sl,
                  case when net_price = 1
                    then round(massage_product_consumed.price * massage_product_consumed.quantity 
                        * (1-NVL(massage_reservation_room.discount,0)/100))
                    else round(massage_product_consumed.price * massage_product_consumed.quantity 
                        * (1-NVL(massage_reservation_room.discount,0)/100) 
                        * (1+NVL(massage_reservation_room.service_rate,0)/100) 
                        * (1+NVL(massage_reservation_room.tax,0)/100))
                  end ex_dongia,
                  case when net_price = 1
                    then round(massage_product_consumed.price * massage_product_consumed.quantity 
                        * (1-NVL(massage_reservation_room.discount,0)/100))
                    else round(massage_product_consumed.price * massage_product_consumed.quantity 
                        * (1-NVL(massage_reservation_room.discount,0)/100) 
                        * (1+NVL(massage_reservation_room.service_rate,0)/100) 
                        * (1+NVL(massage_reservation_room.tax,0)/100))
                  end ex_thanhtien,
                  massage_reservation_room.tax as ex_thue,
                  case when net_price = 1
                    then round(massage_product_consumed.price * massage_product_consumed.quantity 
                        * (1-NVL(massage_reservation_room.discount,0)/100)
                        / (1+NVL(massage_reservation_room.tax,0)/100)
                        * (NVL(massage_reservation_room.tax,0)/100))
                    else round(massage_product_consumed.price * massage_product_consumed.quantity 
                        * (1-NVL(massage_reservation_room.discount,0)/100) 
                        * (1+NVL(massage_reservation_room.service_rate,0)/100) 
                        * (NVL(massage_reservation_room.tax,0)/100))
                  end ex_tienthue
                from massage_product_consumed
                  inner join massage_reservation_room on  massage_reservation_room.id = massage_product_consumed.reservation_room_id
                where massage_product_consumed.HOTEL_RESERVATION_ROOM_ID is null 
                  and massage_product_consumed.status = 'CHECKOUT'
                  and massage_product_consumed.time_out >= ".$from." and massage_product_consumed.time_out < ".($to+86400)." 
                order by massage_product_consumed.id";
                
        $result += DB::fetch_all($sql);
        //System::debug($result);
        //exit();
        $objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Ngày chứng từ (*)');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Ngày hạch toán (*)');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Số CT bán hàng (*)');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Mã khách hàng (*)');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Tên khách hàng');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Địa chỉ');
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Mã số thuế');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Khách hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Diễn giải');
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Mã hàng (*)');
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Tên hàng');
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Kho');
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'TK Nợ');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'TK Có');
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'Đối tượng');
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'Đơn vị tính');
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'Số lượng');
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'Đơn giá');
        $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Thành tiền');
        $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Tỷ lệ CK (%)');
        $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, '% thuế GTGT');
        $objPHPExcel->getActiveSheet()->setCellValue('V'.$i, 'TK thuế GTGT');
        $objPHPExcel->getActiveSheet()->setCellValue('W'.$i, 'TK kho');
        $objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'TK giá vốn');
        
        $i++;
        foreach($result as $key=>$value)
		{
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['ex_ngaychungtu']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['ex_ngayhachtoan']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['ex_sochungtu']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['ex_makhachhang']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['ex_tenkhachhang']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['ex_address']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['ex_masothue']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['ex_guest']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['ex_diengiai']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $value['ex_masanpham']);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $value['ex_tensanpham']);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['ex_tkno']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $value['ex_tkco']);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $value['ex_doituong']);
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $value['ex_sl']);
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $value['ex_dongia']);
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $value['ex_thanhtien']);
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 0);
            $objPHPExcel->getActiveSheet()->setCellValue('U'.$i, 0);
			$i++;				
		}
        $fileName = "reven".date('d-m-y',$from)."-to-".date('d-m-y',$to).".xls";
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
		$_REQUEST['date_from'] = (isset($_REQUEST['date_from']) && $_REQUEST['date_from'])?$_REQUEST['date_from']:date('01/m/Y');
		$_REQUEST['date_to'] = (isset($_REQUEST['date_to']) && $_REQUEST['date_to'])?$_REQUEST['date_to']:date('d/m/Y');
		$this->parse_layout('reven');
        
        if(Url::get('act'))
        {
            require_once 'packages/core/includes/utils/vn_code.php';
    		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
    		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
    		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
    		//set_time_limit(0);
    		$from = Date_Time::to_time($_REQUEST['date_from']);
    		$to = Date_Time::to_time($_REQUEST['date_to']);
    		if(!is_dir('export/'.date('d-m-Y')))
    		{
    			mkdir('export/'.date('d-m-Y'));
    		}
            $this->export_reven($from,$to);
        }
	}

}
?>