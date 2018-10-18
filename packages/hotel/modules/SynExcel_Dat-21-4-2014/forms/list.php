<?php
class SynExcelForm extends Form
{
	function SynExcelForm()
	{
		Form::Form('SynExcelForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');		
	}
	function on_submit()
	{
		require_once 'packages/core/includes/utils/vn_code.php';
		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
		//set_time_limit(0);
		$time_in = Date_Time::to_time(Url::get('date_from')?Url::get('date_from'):'01/06/2010');
		$time_out = Date_Time::to_time(Url::get('date_to')?Url::get('date_to'):date('d/m/Y',time()))+24*3600;
		if(!is_dir('export/'.date('d-m-Y')))
		{
			mkdir('export/'.date('d-m-Y'));
		}
		/*----------------- Danh muc mat hang -------------------------*/
		// Create new PHPExcel object
		$this->save_product();//Mau_vat_tu_hang_hoa_cong_cu_dung_cu -OK
		$this->save_product_category();//Mau_loai_vat_tu_hang_hoa -OK
		/*---------------- /Danh muc mat hang--------------------------*/
		/*----------------- Danh muc khach hang -----------------------*/
		$this->get_customer();//Mau_khach_hang_nha_cung_cap -OK
		$this->save_customer_group();//Mau_nhom_khach_hang_nha_cung_cap
		$this->get_wh_import($time_in,$time_out);//Mau_chung_tu_nhap_kho
		$this->get_payment_cash();//Mau_chung_tu_thu_tien
		$this->get_payment_credit();//Mau_chung_tu_nop_tien_vao_tai_khoan
        $this->get_invoice();//Mau_hoa_don_ban_hang_chua_thu_tien
		/*----------------- /Danh muc khach hang ----------------------*/
		/*----------------- Don vi do luong ---------------------------*/
		//$this->get_order($time_in,$time_out);
		/*----------------- /Bang Ke Hang Hoa Va Dich Vu --------------*/
		/*$path='export/'.date('d-m-Y');
		$zip = new ZipArchive;
		$zip->open('export/'.date('d-m-Y').'.zip', ZipArchive::CREATE);
		if (false !== ($dir = opendir($path)))
			 {
				 while (false !== ($file = readdir($dir)))
				 {
					 if ($file != '.' && $file != '..')
					 {
							   $zip->addFile($path.DIRECTORY_SEPARATOR.$file);
							   //delete if need
					 }
				 }
			 }
			 else
			 {
				 die('Can\'t read dir');
			 }
		$zip->close();
		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename="export/'.date('d-m-Y').'.zip"');
		header("Content-Transfer-Encoding: binary"); 
	    header("Content-Length: ".filesize(date('d-m-Y').'.zip')); 		
		readfile(''.date('d-m-Y').'.zip');		
		//Url::redirect_current(array('cmd'=>'success'));
		*/
	}
	function save_product()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$products = DB::fetch_all('
			SELECT
				product.id
				,product.name_1 as name
				,product_category.code as category_code
				,unit.name_1 as unit
				,product.type
			FROM
				product
				INNER JOIN product_category on product_category.id = product.category_id
				INNER JOIN unit on unit.id = product.unit_id
			WHERE
				1=1
		');
		$i = 1;
		foreach($products as $key=>$value)
		{
			$tool = 0;
			$acc_code = '';
			switch($value['type'])
			{
				case 'PRODUCT':
					$type = 3;
					$acc_code = 155;
					break;
				case 'DRINK':
					$type = 3;
					$acc_code = 155;
					break;
				case 'GOODS':
					$type = 0;
					$acc_code = 156;
					break;
				case 'TOOL':
					$type = 0;
					$tool = 1;
					$acc_code = 153;					
					break;
				case 'EQUIMENT':
					$type = 0;
					$tool = 1;
					$acc_code = 152;
					break;
				case 'SERVICE':
					$type = 2;
					break;
				case 'MATERIAL':
					$type = 0;
					$acc_code = 152;
					break;
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $key);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $type);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['category_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['unit']);
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $acc_code);
			$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $tool);
			$i++;				
		}
		$fileName = "export/".date('d-m-Y')."/Mau_vat_tu_hang_hoa_cong_cu_dung_cu.xls";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);		
	}
	function save_product_category()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$products = DB::fetch_all('
			SELECT
				product_category.id
				,product_category.name as name
				,product_category.code
				,product_category.structure_id
			FROM
				product_category
			WHERE
				structure_id<>'.ID_ROOT.'
			ORDER BY
				structure_id
		');
		$i = 1;
		foreach($products as $key=>$value)
		{
			$parent = DB::fetch('SELECT code FROM product_category WHERE structure_id='.IDStructure::parent($value['structure_id']),'code');
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, ($parent!='ROOT')?$parent:'');
			$i++;				
		}
		$fileName = "export/".date('d-m-Y')."/Mau_loai_vat_tu_hang_hoa.xls";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);				
	}
	function get_customer()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$customers = DB::fetch_all('
			SELECT
				customer.id
				,customer.name
				,customer.code
				,customer.contact_person_name
				,customer.contact_person_phone
				,customer.tax_code
				,customer.phone
				,customer.fax
				,customer.address
				,customer.email
				,customer_group.id as group_name
				,customer_contact.contact_name
			FROM
				customer
				LEFT OUTER JOIN customer_group on customer_group.id = customer.group_id
				LEFT OUTER JOIN customer_contact on  customer_contact.customer_id = customer.id
			WHERE
				1=1
		');
		$i=1;
		foreach($customers as $key=>$value)
		{
			$is_company = 0;
			$is_customer = 0;
			$is_supplier = 1;
			if($value['group_name']=='KL')
			{
				$is_company = 1;
			}
			if($value['group_name']!='KL' and $value['group_name']!='SUPPLIER')
			{
				$is_customer = 1;
			}
			if($value['group_name']=='SUPPLIER')
			{
				$is_supplier = 1;	
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $is_company);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $is_customer);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $is_supplier);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['group_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['address']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['tax_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $value['phone']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $value['fax']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value['email']);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $value['tax_code']);
			$i++;
		}
		$fileName = "export/".date('d-m-Y')."/Mau_khach_hang_nha_cung_cap.xls";
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);
	}
	function save_customer_group()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$customer_groups = DB::fetch_all('
			SELECT
				customer_group.id
				,customer_group.name as name
				,customer_group.id as code
				,customer_group.structure_id
			FROM
				customer_group
			WHERE
				structure_id<>'.ID_ROOT.'
			ORDER BY
				structure_id
		');
		$i = 1;
		foreach($customer_groups as $key=>$value)
		{
			$parent = DB::fetch('SELECT id as code FROM customer_group WHERE structure_id='.IDStructure::parent($value['structure_id']),'code');
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, ($parent!='ROOT')?$parent:'');
			$i++;				
		}
		$fileName = "export/".date('d-m-Y')."/Mau_nhom_khach_hang_nha_cung_cap.xls";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);				
	}
	function get_wh_import($time_in,$time_out)
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        $i = 1;
        //tieu de
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Ngày chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Ngày hạch toán');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Số chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Mã đối tượng');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Tên đối tượng');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Địa chỉ');
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Người giao');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Diễn giải');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Loại tiền');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Nhân viên mua hàng');
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Mã hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Tên hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'Kho');
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'TK Nợ');
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'TK Có');
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'Đơn vị tính');
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Số lượng');
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Đơn giá');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, 'Thành tiền');
		$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'Đối tượng hạch toán');
        
        //data
		$wh_invoice = DB::fetch_all('
			SELECT
				wh_invoice.id,
				wh_invoice.bill_number,
				TO_CHAR(wh_invoice.create_date,\'DD/MM/YYYY\') as create_date,
				wh_invoice.deliver_name,
				wh_invoice.receiver_name,				
				wh_invoice.note,
				customer.code as supplier_code,
				customer.name as supplier_name,
				customer.address as supplier_address
			FROM
				wh_invoice
				LEFT OUTER JOIN customer on customer.id = wh_invoice.supplier_id
			WHERE
				wh_invoice.type=\'IMPORT\'
				
		');
		$i++;
		foreach($wh_invoice as $id=>$value)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['create_date']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['create_date']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['bill_number']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['supplier_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['supplier_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['supplier_address']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['deliver_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['note']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'VND');
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value['receiver_name']);			
			$wh_invoice_detail = DB::fetch_all('
				SELECT
					wh_invoice_detail.id,
					wh_invoice_detail.product_id,
					wh_invoice_detail.num,
					wh_invoice_detail.price,					
					product.name_1 as product_name,
					product.type,
					unit.name_1 as unit_name,
					warehouse.code as warehouse_code
				FROM
					wh_invoice_detail
					INNER JOIN wh_invoice ON wh_invoice.id = wh_invoice_detail.invoice_id
					INNER JOIN warehouse ON wh_invoice.warehouse_id = warehouse.id
					INNER JOIN product ON product.id = wh_invoice_detail.product_id
					INNER JOIN unit ON product.unit_id = unit.id
				WHERE
					wh_invoice_detail.invoice_id = '.$id.'
			');
			if($wh_invoice_detail)
			{
				foreach($wh_invoice_detail as $key=>$invoice_detail)
				{
					switch($invoice_detail['type'])
					{
						case 'PRODUCT':
							$acc_code = 155;
							break;
						case 'DRINK':
							$acc_code = 155;
							break;
						case 'GOODS':
							$acc_code = 156;
							break;
						case 'TOOL':
							$acc_code = 153;					
							break;
						case 'EQUIMENT':
							$acc_code = 153;
							break;
						case 'MATERIAL':
							$acc_code = 152;
							break;
					}					
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $invoice_detail['product_id']);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $invoice_detail['product_name']);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $invoice_detail['warehouse_code']);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $acc_code);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '331');
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $invoice_detail['unit_name']);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $invoice_detail['num']);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $invoice_detail['price']);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $invoice_detail['price']*$invoice_detail['num']);
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $value['supplier_code']);
					$i++;
				}
                $i--;
			}
			$i++;
		}
		$fileName = "export/".date('d-m-Y')."/Mau_chung_tu_nhap_kho.xls";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);
	}
	function get_order($time_in,$time_out)
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		/*------------------- Reservation - Le tan -------------------*/
		/*------------------- Bar Reservation - Nha hang -------------*/
		$restaurants = $this->get_bar($time_in,$time_out);
		$i=1;
		foreach($restaurants as $l_id=>$restaurant)
		{
			$amount = $restaurant['quantity']*$restaurant['price'];
			$discount = $restaurant['quantity']*$restaurant['price']*$restaurant['discount_rate']/100;
			$service_charge = $restaurant['quantity']*$restaurant['price']*$restaurant['discount_rate']/100;
			$tax = $restaurant['quantity']*$restaurant['price']*$restaurant['discount_rate']/100;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, PHPExcel_Shared_Date::PHPToExcel($restaurant['time']));
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,'');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $restaurant['invoice_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $restaurant['full_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $restaurant['customer_code']);
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$i, $restaurant['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $restaurant['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $restaurant['unit_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $restaurant['quantity']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $restaurant['price']);
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$i,hexdec($amount), PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $discount);			
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $service_charge);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $tax);
			$i++;
		}
		/*------------------- /Bar Reservation - Nha hang ------------*/	
		$fileName = "export/".date('d-m-Y')."/BangKeHangHoaVaDichVu_".gmdate("Y_m_d", time() + 7*3600).".xls";
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);
	}
	function get_invoice()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		/*------------------- Reservation - Le tan -------------------*/
		$rooms = $this->get_room_invoice();
		$i = 1;
		foreach($rooms as $r_id=>$room)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 1);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('d/m/Y',$room['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, date('d/m/Y',$room['time']));			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'BH_REC_'.$room['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $room['customer_code']?$room['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $room['full_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $room['customer_address']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $room['tax_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $room['contact_name']);			
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Thu tiền phòng');
			$room_details = $this->get_room_invoice_detail($r_id);
			foreach($room_details as $r_d_id=>$room_detail)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $room_detail['product_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $room_detail['product_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, $room_detail['warehouse_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $room_detail['acc_revenue_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $room_detail['acc_deposit_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $room_detail['customer_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $room_detail['unit']);
				$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, $room_detail['quantity']);
				$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, $room_detail['price']);
				$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, $room_detail['amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, $room_detail['discount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, $room_detail['tax_total']);
				$objPHPExcel->getActiveSheet()->setCellValue('AR'.$i, $room_detail['tax_acc_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AS'.$i, $room_detail['warehouse_acc_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AT'.$i, $room_detail['cogs_acc']);
				$i++;
			}
			$i++;
		}
	
		/*------------------- Restaurant - Nhà hàng -------------------*/
		$restaurants = $this->get_bar_invoice();
		foreach($restaurants as $b_id=>$restaurant)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 1);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('d/m/Y',$restaurant['time_out']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, date('d/m/Y',$restaurant['time_out']));			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'BH_RES_'.$restaurant['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $restaurant['customer_code']?$restaurant['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $restaurant['full_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $restaurant['customer_address']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $restaurant['tax_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $restaurant['contact_name']);			
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Thu tiền nhà hàng');
			$restaurant_details = $this->get_bar($b_id);
			foreach($restaurant_details as $r_p_id=>$restaurant_detail)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $restaurant_detail['product_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $restaurant_detail['product_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, $restaurant_detail['warehouse_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $restaurant_detail['acc_revenue_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $restaurant_detail['acc_deposit_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $restaurant_detail['customer_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $restaurant_detail['unit']);
				$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, $restaurant_detail['quantity']);
				$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, $restaurant_detail['price']);
				$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, $restaurant_detail['amount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, $restaurant_detail['discount']);
				$objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, $restaurant_detail['tax_total']);
				$objPHPExcel->getActiveSheet()->setCellValue('AR'.$i, $restaurant_detail['tax_acc_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AS'.$i, $restaurant_detail['warehouse_acc_code']);
				$objPHPExcel->getActiveSheet()->setCellValue('AT'.$i, $restaurant_detail['cogs_acc']);
				$i++;
			}
			$i++;
		}
		$fileName = "export/".date('d-m-Y')."/Mau_hoa_don_ban_hang_chua_thu_tien.xls";		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);
	}
	//
	function get_bar($bar_reservation_id)
	{
		$product_list = 
			DB::fetch_all('
			SELECT
				bar_reservation_product.id
				,bar_reservation_product.product_id
				,bar_reservation_product.quantity
				,bar_reservation_product.price
				,bar_reservation_product.discount_rate
				,bar_reservation.tax_rate
				,bar_reservation.time
				,product.name_1 as product_name
				,product.type as product_type
				,unit.name_1 as unit_name
				,customer.name as customer_name
				,customer.code as customer_code
				,bar_reservation.tax_rate
				,bar_reservation.bar_fee_rate
				,bar_reservation.discount_percent 
				,bar_reservation.discount
				,bar.code as bar_code
				,bar.full_rate
				,bar.full_charge
				,bar_reservation.foc
			FROM
				bar_reservation_product
				INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
				INNER JOIN bar ON bar.id = bar_reservation.bar_id
				INNER JOIN product ON product.id = bar_reservation_product.product_id
				INNER JOIN unit on unit.id = product.unit_id
				LEFT OUTER JOIN reservation_room on reservation_room.id = bar_reservation.reservation_room_id
				LEFT OUTER JOIN RESERVATION on reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
			WHERE
				1=1 AND bar_reservation.id='.$bar_reservation_id.'
			ORDER BY
				bar_reservation_product.id desc
			');
		foreach($product_list as $k => $product){
			switch($product['product_type'])
			{
				case 'PRODUCT':
					$acc_code = 155;
					break;
				case 'DRINK':
					$acc_code = 155;
					break;
				case 'GOODS':
					$acc_code = 156;
					break;
				case 'TOOL':
					$acc_code = 153;					
					break;
				case 'EQUIMENT':
					$acc_code = 153;
					break;
				case 'MATERIAL':
					$acc_code = 152;
					break;
				default:
					$acc_code = 156;
					break;
			}
			if($product['full_rate'])
			{	
				$price = $product['price']-($product['price']/(1+$product['tax_rate']/100));
			}
			else
			{
				if($product['full_charge'])
				{
					$price = $product['price'];
				}
				else
				{
					$price = $product['price']+$product['price']*$product['bar_fee_rate']/100;
				}
			}
			$product_list[$k]['product_code'] = $product['product_id'];
			$product_list[$k]['product_name'] = $product['product_name'];
			$product_list[$k]['warehouse_code'] = $this->get_warehouse_code($product['bar_code']);
			$product_list[$k]['acc_revenue_code'] = $product['foc']?6418:131;
			$product_list[$k]['acc_deposit_code'] = $acc_code;
			$product_list[$k]['customer_code'] = $product['customer_code']?$product['customer_code']:'KL';
			$product_list[$k]['unit'] = $product['unit_name'];
			$product_list[$k]['quantity'] = $product['quantity'];
			$product_list[$k]['price'] = $price;
			$product_list[$k]['amount'] = $price*$product['quantity'];
			$product_list[$k]['discount'] = $product['discount'];
			$product_list[$k]['tax_rate'] = $product['tax_rate'];
			$product_list[$k]['tax_total'] = $price*$product['tax_rate']/100;
			$product_list[$k]['tax_acc_code'] = 3331;
			$product_list[$k]['warehouse_acc_code'] = $acc_code;
			$product_list[$k]['cogs_acc'] = 632;
		}
		return $product_list;
	}
	function get_massage()
	{
		return DB::fetch_all('
			SELECT
				massage_product_consumed.id
				,massage_product_consumed.reservation_room_id
				,massage_product_consumed.quantity
				,massage_product_consumed.price
				,massage_product_consumed.time
				,massage_reservation_room.tax
				,massage_product.name
				,massage_product.code as product_id
				,\'Lần\' as unit_name
				,customer.code as customer_code
				,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as guest_name
			FROM
				massage_product_consumed
				INNER JOIN massage_product ON massage_product.id = massage_product_consumed.product_id
				INNER JOIN massage_reservation_room on massage_reservation_room.id = massage_product_consumed.reservation_room_id
				LEFT OUTER JOIN reservation_room on reservation_room.id = massage_reservation_room.HOTEL_RESERVATION_ROOM_ID
				LEFT OUTER JOIN RESERVATION on reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
				LEFT OUTER JOIN traveller on traveller.id = reservation_room.traveller_id
			WHERE
				1=1 and (massage_product_consumed.status=\'CHECKIN\' and massage_product_consumed.status=\'CHECKOUT\')
		');	
	}
	/*--------------------Invoice -----------------------------*/
	function get_room_invoice()
	{
		return DB::fetch_all('
			SELECT
				reservation_room.id
				,reservation_room.time_out as time
				,reservation_room.total_amount
				,customer.code as customer_code
				,customer.name as full_name
				,customer.address as customer_address
				,customer.tax_code
				,customer_contact.contact_name
				,customer_contact.contact_phone
				,customer_contact.contact_email			
				,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as guest_name
			FROM
				reservation_room
				INNER JOIN payment_type on payment_type.id = reservation_room.payment_type_id
				LEFT OUTER JOIN RESERVATION on reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
				LEFT OUTER JOIN customer_contact ON customer_contact.customer_id = customer.id
				LEFT OUTER JOIN traveller on traveller.id = reservation_room.traveller_id				
			WHERE
				reservation_room.status=\'CHECKOUT\' and payment_type.def_code=\'CASH\'
			ORDER BY
				reservation_room.id
		');
	}
	function get_room_invoice_detail($reservation_room_id)
	{
		$room_detail = array();
		$room_statuses = DB::fetch_all('
			SELECT
				room_status.id,
				TO_CHAR(room_status.in_date,\'DD/MM/YYYY\') as in_date,
				room_status.change_price,
				reservation_room.net_price,
				reservation_room.tax_rate,
				reservation_room.service_rate,
				reservation_room.foc,
				reservation_room.reduce_amount as discount,
				room_level.brief_name as room_level_code,
				customer.code as customer_code
			FROM
				room_status
				INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
				INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				INNER JOIN room_level ON room_level.id = reservation_room.room_level_id
				LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
			WHERE
				reservation_room.id = '.$reservation_room_id.' AND room_status.status=\'OCCUPIED\'
		');
		foreach($room_statuses as $r_s_id=>$room_status)
		{
			$price = $room_status['change_price'];
			if($room_status['net_price'])
			{
				$tax_rate = $room_status['tax_rate']/100;
				$price = $room_status['change_price']/(1+$tax_rate);				
			}
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['product_code'] = 'ROOM_NIGHT_'.$room_status['room_level_code'];
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['product_name'] = 'Đêm phòng';
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['warehouse_code'] = 'REC';
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['acc_revenue_code'] = $room_status['foc']?6418:131;
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['acc_deposit_code'] = 156;
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['customer_code'] = $room_status['customer_code']?$room_status['customer_code']:'KL';
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['unit'] = 'Đêm';
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['quantity'] = 1;
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['price'] = $price;			
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['amount'] = $price;
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['discount'] = $room_status['discount'];
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['tax_rate'] = $room_status['tax_rate'];
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['tax_total'] = $price*$room_status['tax_rate'];
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['tax_acc_code'] = 3331;
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['warehouse_acc_code'] = 156;
			$room_detail['ROOM_NIGHT_'.$room_status['room_level_code']]['cogs_acc'] = 632;
		}
		// GET MINIBAR PRODUCT
		$minibar_products = DB::fetch_all('
			SELECT
				housekeeping_invoice_detail.id,
				housekeeping_invoice_detail.product_id,
				housekeeping_invoice_detail.quantity,
				housekeeping_invoice_detail.price,
				housekeeping_invoice_detail.promotion,
				housekeeping_invoice.tax_rate,
				housekeeping_invoice.fee_rate,
				housekeeping_invoice.discount,
				product.name_1 as product_name,
				product.type as product_type,
				unit.name_1 as unit_name,
				reservation_room.foc_all,
				customer.code as customer_code
			FROM
				housekeeping_invoice_detail
				INNER JOIN housekeeping_invoice ON housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
				INNER JOIN product on product.id = housekeeping_invoice_detail.product_id
				INNER JOIN unit on unit.id = product.unit_id
				INNER JOIN reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
				INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
			WHERE
				housekeeping_invoice.reservation_room_id = '.$reservation_room_id.' and housekeeping_invoice.type=\'MINIBAR\'
		');
		foreach($minibar_products as $m_p_id=>$minibar_product)
		{
			switch($minibar_product['product_type'])
			{
				case 'PRODUCT':
					$acc_code = 155;
					break;
				case 'DRINK':
					$acc_code = 155;
					break;
				case 'GOODS':
					$acc_code = 156;
					break;
				case 'TOOL':
					$acc_code = 153;					
					break;
				case 'EQUIMENT':
					$acc_code = 153;
					break;
				case 'MATERIAL':
					$acc_code = 152;
					break;
			}
			$price = $minibar_product['price']+($minibar_product['price']*$minibar_product['fee_rate']/100);
			$room_detail[$minibar_product['product_id']]['product_code'] = $minibar_product['product_id'];
			$room_detail[$minibar_product['product_id']]['warehouse_code'] = $this->get_warehouse_code('MINIBAR');
			$room_detail[$minibar_product['product_id']]['product_name'] = $minibar_product['product_name'];
			$room_detail[$minibar_product['product_id']]['acc_revenue_code'] = $minibar_product['foc_all']?6418:131;
			$room_detail[$minibar_product['product_id']]['acc_deposit_code'] = $acc_code;
			$room_detail[$minibar_product['product_id']]['customer_code'] = $minibar_product['customer_code']?$minibar_product['customer_code']:'KL';
			$room_detail[$minibar_product['product_id']]['unit'] = $minibar_product['unit_name'];
			$room_detail[$minibar_product['product_id']]['quantity'] = $minibar_product['quantity'];
			$room_detail[$minibar_product['product_id']]['price'] = $price;
			$room_detail[$minibar_product['product_id']]['amount'] = $price*$minibar_product['quantity'];
			$room_detail[$minibar_product['product_id']]['discount'] = $minibar_product['discount'];
			$room_detail[$minibar_product['product_id']]['tax_rate'] = $minibar_product['tax_rate'];
			$room_detail[$minibar_product['product_id']]['tax_total'] = $price*$minibar_product['tax_rate']/100;
			$room_detail[$minibar_product['product_id']]['tax_acc_code'] = 3331;
			$room_detail[$minibar_product['product_id']]['warehouse_acc_code'] = $acc_code;
			$room_detail[$minibar_product['product_id']]['cogs_acc'] = 632;
		}
		//Laundrys
		$laundry_products = DB::fetch_all('
			SELECT
				housekeeping_invoice_detail.id,
				housekeeping_invoice_detail.product_id,
				housekeeping_invoice_detail.quantity,
				housekeeping_invoice_detail.price,
				housekeeping_invoice_detail.promotion,
				housekeeping_invoice.tax_rate,
				housekeeping_invoice.fee_rate,
				housekeeping_invoice.discount,				
				product.name_1 as product_name,
				product.type as product_type,
				unit.name_1 as unit_name,
				reservation_room.foc_all,
				customer.code as customer_code
			FROM
				housekeeping_invoice_detail
				INNER JOIN housekeeping_invoice ON housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
				INNER JOIN product on product.id = housekeeping_invoice_detail.product_id
				INNER JOIN unit on unit.id = product.unit_id
				INNER JOIN reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
				INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
				LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
			WHERE
				housekeeping_invoice.reservation_room_id = '.$reservation_room_id.' and housekeeping_invoice.type=\'LAUNDRY\'
		');
		
		foreach($laundry_products as $l_p_id=>$laundry_product)
		{
			switch($laundry_product['product_type'])
			{
				case 'PRODUCT':
					$acc_code = 155;
					break;
				case 'DRINK':
					$acc_code = 155;
					break;
				case 'GOODS':
					$acc_code = 156;
					break;
				case 'TOOL':
					$acc_code = 153;					
					break;
				case 'EQUIMENT':
					$acc_code = 153;
					break;
				case 'MATERIAL':
					$acc_code = 152;
					break;
				default:
					$acc_code = 156;
					break;
			}
			$price = $laundry_product['price']+($laundry_product['price']*$laundry_product['fee_rate']/100);
			$room_detail[$laundry_product['product_id']]['product_code'] = $laundry_product['product_id'];
			$room_detail[$laundry_product['product_id']]['product_name'] = $laundry_product['product_name'];
			$room_detail[$laundry_product['product_id']]['warehouse_code'] = $this->get_warehouse_code('LAUNDRY');
			$room_detail[$laundry_product['product_id']]['acc_revenue_code'] = $laundry_product['foc_all']?6418:131;
			$room_detail[$laundry_product['product_id']]['acc_deposit_code'] = $acc_code;
			$room_detail[$laundry_product['product_id']]['customer_code'] = $laundry_product['customer_code']?$laundry_product['customer_code']:'KL';
			$room_detail[$laundry_product['product_id']]['unit'] = $laundry_product['unit_name'];
			$room_detail[$laundry_product['product_id']]['quantity'] = $laundry_product['quantity'];
			$room_detail[$laundry_product['product_id']]['price'] = $price;
			$room_detail[$laundry_product['product_id']]['amount'] = $price*$laundry_product['quantity'];
			$room_detail[$laundry_product['product_id']]['discount'] = $laundry_product['discount'];
			$room_detail[$laundry_product['product_id']]['tax_rate'] = $laundry_product['tax_rate'];
			$room_detail[$laundry_product['product_id']]['tax_total'] = $price*$laundry_product['tax_rate']/100;
			$room_detail[$laundry_product['product_id']]['tax_acc_code'] = 3331;
			$room_detail[$laundry_product['product_id']]['warehouse_acc_code'] = $acc_code;
			$room_detail[$laundry_product['product_id']]['cogs_acc'] = 632;
		}
		$restaurant_products = DB::fetch_all('
			SELECT
				bar_reservation_product.id
				,bar_reservation_product.product_id
				,bar_reservation_product.quantity
				,bar_reservation_product.price
				,bar_reservation_product.discount_rate
				,bar_reservation.tax_rate
				,bar_reservation.time
				,product.name_1 as product_name
				,product.type as product_type
				,unit.name_1 as unit_name
				,customer.name as customer_name
				,customer.code as customer_code
				,bar_reservation.tax_rate
				,bar_reservation.bar_fee_rate
				,bar_reservation.discount_percent 
				,bar_reservation.discount
				,bar.code as bar_code
				,bar.full_rate
				,bar.full_charge
				,reservation_room.foc_all
			FROM
				bar_reservation_product
				INNER JOIN bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
				INNER JOIN bar ON bar.id = bar_reservation.bar_id
				INNER JOIN product ON product.id = bar_reservation_product.product_id
				INNER JOIN unit on unit.id = product.unit_id
				INNER JOIN reservation_room on reservation_room.id = bar_reservation.reservation_room_id
				INNER JOIN RESERVATION on reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
			WHERE
				1=1 AND reservation_room.id='.$reservation_room_id.'
			ORDER BY
				bar_reservation_product.id desc
		');
		foreach($restaurant_products as $b_p_id=>$restaurant_product)
		{
			switch($restaurant_product['product_type'])
			{
				case 'PRODUCT':
					$acc_code = 155;
					break;
				case 'DRINK':
					$acc_code = 155;
					break;
				case 'GOODS':
					$acc_code = 156;
					break;
				case 'TOOL':
					$acc_code = 153;					
					break;
				case 'EQUIMENT':
					$acc_code = 153;
					break;
				case 'MATERIAL':
					$acc_code = 152;
					break;
			}
			if($restaurant_product['full_rate'])
			{	
				$price = $restaurant_product['price']-($restaurant_product['price']/(1+$restaurant_product['tax_rate']/100));
			}
			else
			{
				if($restaurant_product['full_charge'])
				{
					$price = $restaurant_product['price'];
				}
				else
				{
					$price = $restaurant_product['price']+$restaurant_product['price']*$restaurant_product['bar_fee_rate']/100;
				}
			}
			$price = $restaurant_product['price']+($restaurant_product['price']*$restaurant_product['bar_fee_rate']/100);
			$room_detail[$restaurant_product['product_id']]['product_code'] = $restaurant_product['product_id'];
			$room_detail[$restaurant_product['product_id']]['product_name'] = $restaurant_product['product_name'];
			$room_detail[$restaurant_product['product_id']]['warehouse_code'] = $this->get_warehouse_code($restaurant_product['bar_code']);
			$room_detail[$restaurant_product['product_id']]['acc_revenue_code'] = $restaurant_product['foc_all']?6418:131;
			$room_detail[$restaurant_product['product_id']]['acc_deposit_code'] = $acc_code;
			$room_detail[$restaurant_product['product_id']]['customer_code'] = $restaurant_product['customer_code']?$restaurant_product['customer_code']:'KL';
			$room_detail[$restaurant_product['product_id']]['unit'] = $restaurant_product['unit_name'];
			$room_detail[$restaurant_product['product_id']]['quantity'] = $restaurant_product['quantity'];
			$room_detail[$restaurant_product['product_id']]['price'] = $price;
			$room_detail[$restaurant_product['product_id']]['amount'] = $price*$restaurant_product['quantity'];
			$room_detail[$restaurant_product['product_id']]['discount'] = $restaurant_product['discount'];
			$room_detail[$restaurant_product['product_id']]['tax_rate'] = $restaurant_product['tax_rate'];
			$room_detail[$restaurant_product['product_id']]['tax_total'] = $price*$restaurant_product['tax_rate']/100;
			$room_detail[$restaurant_product['product_id']]['tax_acc_code'] = 3331;
			$room_detail[$restaurant_product['product_id']]['warehouse_acc_code'] = $acc_code;
			$room_detail[$restaurant_product['product_id']]['cogs_acc'] = 632;
		}
		$extra_services = DB::fetch_all('
			SELECT
				extra_service_invoice_detail.id,
				extra_service.code as product_code,
				extra_service_invoice_detail.name as product_name,
				extra_service_invoice_detail.price,
				extra_service_invoice_detail.quantity,
				extra_service_invoice_detail.service_id,
				extra_service_invoice.time,
				extra_service_invoice.tax_rate,
				extra_service_invoice.service_rate,
				extra_service.unit as unit_name,
				reservation_room.foc_all,
				customer.code as customer_code
			FROM
				extra_service_invoice_detail
				INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
				INNER JOIN extra_service ON extra_service.id = extra_service_invoice_detail.service_id
				INNER JOIN reservation_room ON reservation_room.id = extra_service_invoice.reservation_room_id
				INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer ON customer.id = reservation.customer_id 
			WHERE
				extra_service_invoice.reservation_room_id = '.$reservation_room_id.'
			
		');
		foreach($extra_services as $e_s_id=>$extra_service)
		{
			$acc_code = 156;
			$price = $extra_service['price']+($extra_service['price']*$extra_service['service_rate']/100);
			$room_detail['ES'.$extra_service['service_id']]['product_code'] = $extra_service['product_code'];
			$room_detail['ES'.$extra_service['service_id']]['product_name'] = $extra_service['product_name'];
			$room_detail['ES'.$extra_service['service_id']]['warehouse_code'] = '';
			$room_detail['ES'.$extra_service['service_id']]['acc_revenue_code'] = $extra_service['foc_all']?6418:131;
			$room_detail['ES'.$extra_service['service_id']]['acc_deposit_code'] = $acc_code;
			$room_detail['ES'.$extra_service['service_id']]['customer_code'] = $extra_service['customer_code']?$extra_service['customer_code']:'KL';
			$room_detail['ES'.$extra_service['service_id']]['unit'] = $extra_service['unit_name'];
			$room_detail['ES'.$extra_service['service_id']]['quantity'] = $extra_service['quantity'];
			$room_detail['ES'.$extra_service['service_id']]['price'] = $price;
			$room_detail['ES'.$extra_service['service_id']]['amount'] = $price*$extra_service['quantity'];
			$room_detail['ES'.$extra_service['service_id']]['discount'] = 0;
			$room_detail['ES'.$extra_service['service_id']]['tax_rate'] = $extra_service['tax_rate'];
			$room_detail['ES'.$extra_service['service_id']]['tax_total'] = $price*$extra_service['tax_rate']/100;
			$room_detail['ES'.$extra_service['service_id']]['tax_acc_code'] = 3331;
			$room_detail['ES'.$extra_service['service_id']]['warehouse_acc_code'] = $acc_code;
			$room_detail['ES'.$extra_service['service_id']]['cogs_acc'] = 632;
		}
		return $room_detail;		
	}
	function get_housekeeping_invoice()
	{
		return DB::fetch_all('
			SELECT
				housekeeping_invoice.id
				,housekeeping_invoice.time
				,housekeeping_invoice.total
				,customer.name as full_name
				,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as guest_name
			FROM
				housekeeping_invoice
				LEFT OUTER JOIN reservation_room on reservation_room.id = housekeeping_invoice.reservation_id
				LEFT OUTER JOIN RESERVATION on reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
				LEFT OUTER JOIN traveller on traveller.id = reservation_room.traveller_id
			WHERE
				reservation_room.status=\'CHECKOUT\'
			ORDER BY
				housekeeping_invoice.id
		');
	}
	function get_bar_invoice()
	{
		return DB::fetch_all('
			SELECT
				bar_reservation.id
				,bar_reservation.time_out
				,bar_reservation.total
				,customer.code as customer_code
				,customer.name as full_name
				,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as guest_name
				,customer.address as customer_address
				,customer.tax_code
				,customer_contact.contact_name
				,customer_contact.contact_phone
				,customer_contact.contact_email
			FROM
				bar_reservation
				LEFT OUTER JOIN reservation_room on reservation_room.id = bar_reservation.reservation_room_id
				LEFT OUTER JOIN RESERVATION on reservation.id = reservation_room.reservation_id
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
				LEFT OUTER JOIN customer_contact ON customer_contact.customer_id = customer.id				
				LEFT OUTER JOIN traveller on traveller.id = reservation_room.traveller_id				
			WHERE
				bar_reservation.status=\'CHECKOUT\' and bar_reservation.reservation_room_id=0
			ORDER BY
				bar_reservation.id
		');
	}
	function get_payment_cash()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);		
		$payments = DB::fetch_all('
			SELECT
				payment.id,
				payment.time,
				payment.amount,
				payment.description,
				customer.code as customer_code,
				customer.name as customer_name,
				customer.address as customer_address
			FROM
				payment
				LEFT OUTER JOIN customer ON customer.id = payment.customer_id
			WHERE
				payment.payment_type_id = \'CASH\'
		');
		$i=1;
		foreach($payments as $key=>$value)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['customer_code']?$value['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['customer_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['customer_address']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'VND');
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['description']);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 1111);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 131);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $value['amount']);
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $value['customer_code']?$value['customer_code']:'KL');			
			$i++;
		}
		$fileName = "export/".date('d-m-Y')."/Mau_chung_tu_thu_tien.xls";		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);		
	}
	function get_payment_credit()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);		
		$payments = DB::fetch_all('
			SELECT
				payment.id,
				payment.time,
				payment.amount,
				payment.description,
				payment.bank_acc,
				customer.code as customer_code,
				customer.name as customer_name,
				customer.address as customer_address
			FROM
				payment
				LEFT OUTER JOIN customer ON customer.id = payment.customer_id
			WHERE
				payment.payment_type_id = \'CREDIT_CARD\'
		');
		$i=1;
		foreach($payments as $key=>$value)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['customer_code']?$value['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['customer_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['customer_address']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['bank_acc']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'VND');
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['description']);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 1121);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 131);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $value['amount']);
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $value['customer_code']?$value['customer_code']:'KL');			
			$i++;
		}
		$fileName = "export/".date('d-m-Y')."/Mau_chung_tu_nop_tien_vao_tai_khoan.xls";		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);		
	}	
	function get_massage_invoice()
	{
		return DB::fetch_all('
			SELECT
				massage_reservation_room.id
			FROM
				massage_reservation_room
			WHERE
				1=1
		');
	}
	function get_warehouse_code($department_code)
	{
		$row = DB::fetch('
			SELECT
				portal_department.id,
				portal_department.warehouse_id,				
				warehouse.code
			FROM
				portal_department
				INNER JOIN warehouse ON warehouse.id = portal_department.warehouse_id
			WHERE
				portal_department.department_code = \''.$department_code.'\'
		');
		return $row['code'];
	}
	/*--------------------/Invoice -----------------------------*/
	function draw()
	{
		$_REQUEST['date_from'] = '01/'.date('m/Y');
		$_REQUEST['date_to'] = date('d/m/Y');
		$this->parse_layout('list');
	}

}
?>