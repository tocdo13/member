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
	function on_submit()
	{
		
	}
    
    //doanh thu
    function export_reven($from,$to)
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Ngày chứng từ (*)');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Ngày hạch toán (*)');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Số CT bán hàng (*)');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Mã khách hàng (*)');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Diễn giải');
        $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, 'Mã hàng (*)');
		$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, 'Tên hàng');
        $objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, 'TK Nợ');
		$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, 'TK Có');
        $objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, 'Số lượng');
		$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, 'Đơn giá');
        $objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, 'Đơn giá quy đổi');
		$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, 'Thành tiền');
        
        //du lieu
		$revens = DB::fetch_all("
                select tmp.id ||'_'|| traveller_folio.type ||'_'|| traveller_folio.amount ||'_'|| traveller_folio.service_rate ||'_'|| traveller_folio.tax_rate as id,
                    tmp.id as folio_id,
                    tmp.max_time,
                    traveller_folio.type,
                    count(*) as quantity,
                    round(traveller_folio.amount * (1+NVL(traveller_folio.service_rate,0)/100) * (1+NVL(traveller_folio.tax_rate,0)/100)) as price,
                    NVL(customer.code,'WALK IN') as customer_code,
                    extra_service.code as service_code
                from(
                    select folio.id,folio.total,folio.reservation_id, max(payment.time) as max_time, sum(payment.amount*payment.exchange_rate) as payment
                    from folio
                        inner join payment on payment.folio_id = folio.id
                    group by folio.id,folio.total,folio.reservation_id
                ) tmp
                    inner join traveller_folio on  traveller_folio.folio_id = tmp.id
                    left outer join extra_service_invoice_detail on (extra_service_invoice_detail.id = traveller_folio.invoice_id and traveller_folio.type = 'EXTRA_SERVICE')
                    left outer join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    inner join reservation on reservation.id = tmp.reservation_id
                    left outer join customer on customer.id = reservation.customer_id
                where tmp.max_time>=".$from." and tmp.max_time<=".$to." 
                    and tmp.total - tmp.payment < 100
                group by tmp.id,tmp.max_time,traveller_folio.type,traveller_folio.amount,traveller_folio.service_rate,traveller_folio.tax_rate,customer.code,extra_service.code
                order by tmp.max_time");
        $result = array();
        foreach($revens as $key=>$value)
		{
            if($value['type'] == 'ROOM')
            {
                $result[$value['folio_id']."_ROOM_".$value['price']] = $value;
            }
            else
            {
                if(!isset($result[$value['folio_id']."_".$value['type']]))
                {
                    $result[$value['folio_id']."_".$value['type']] = $value;
                    $result[$value['folio_id']."_".$value['type']]['quantity'] = 1;
                }
                else    
                    $result[$value['folio_id']."_".$value['type']]['price'] += $value['price']*$value['quantity'];
            }
        }
        //System::debug($result);exit();
        $i++;
		foreach($result as $key=>$value)
		{
            switch($value['type'])
            {
                case 'ROOM':
                {
                    $value['product_code'] = 'TP';
                    $value['product_name'] = 'Tiền phòng';
                    $value['tk_co'] = 51111;
                    break;
                }
                case 'MINIBAR':
                {
                    $value['product_code'] = 'MD';
                    $value['product_name'] = 'MN BAR';
                    $value['tk_co'] = 51113;
                    break;
                }
                case 'MASSAGE':
                {
                    $value['product_code'] = 'SPA';
                    $value['product_name'] = 'SPA';
                    $value['tk_co'] = 51115;
                    break;
                }
                case 'BAR':
                {
                    $value['product_code'] = 'NH';
                    $value['product_name'] = 'F&B';
                    $value['tk_co'] = 51113;
                    break;
                }
                case 'EXTRA_SERVICE':
                {
                    if(strpos('a'.strtolower($value['service_code']),'tour')>0)
                    {
                        $value['product_code'] = 'TOUR';
                        $value['product_name'] = 'TOUR';
                        $value['tk_co'] = 51116;
                    }
                    else
                    {
                        $value['product_code'] = 'KHAC';
                        $value['product_name'] = 'OTHER';
                        $value['tk_co'] = 51118;
                    }
                    break;
                }
                case 'LAUNDRY':
                {
                    $value['product_code'] = 'LD';
                    $value['product_name'] = 'LAUNDRY';
                    $value['tk_co'] = 51114;
                    break;
                }
                case 'TELEPHONE':
                {
                    $value['product_code'] = 'TEL';
                    $value['product_name'] = 'TEL';
                    $value['tk_co'] = 51117;
                    break;
                }
                default:
                {
                    $value['product_code'] = 'KHAC';
                    $value['product_name'] = 'OTHER';
                    $value['tk_co'] = 51118;
                    break;
                }
            }
            $value['tk_no'] = 131;
            $value['amount'] = $value['price']*$value['quantity'];
            
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('d/m/Y',$value['max_time']));
    		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, '');
    		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['folio_id']);
    		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['customer_code']);
    		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '');
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $value['product_code']);
    		$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $value['product_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $value['tk_no']);
    		$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $value['tk_co']);
            $objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, $value['quantity']);
    		$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, (float)$value['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, '');
    		$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, (float)$value['amount']);
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
    //
	function save_product()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Mã');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Tên');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Tính chất ');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Loại VTHH/CCDC');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Đơn vị tính');
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'Kho ngầm định');
		$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, 'Là CCDC');
        
        //du lieu
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
		$i++;
		foreach($products as $key=>$value)
		{
			$tool = 0;
			$acc_code = '152';
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
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['category_code']!='ROOT'?$value['category_code']:'UN_DE');
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
        
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Mã loại VTHH/CCDC ');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Tên loại VTHH/CCDC ');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Thuộc loại');
        
        //du lieu
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
		$i++;
		foreach($products as $key=>$value)
		{
			$parent = DB::fetch('SELECT code FROM product_category WHERE structure_id='.IDStructure::parent($value['structure_id']),'code');
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['id'].$value['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['name']?$value['name']:'undefind');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, ($parent!='ROOT')?$parent:'undefined');
			$i++;				
		}
		$fileName = "export/".date('d-m-Y')."/Mau_loai_vat_tu_hang_hoa.xls";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);				
	}
	function save_customer()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        //tieu de
        $i=1;
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Là tổ chức/cá nhân');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Là KH/NCC');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Mã KH/NCC');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Tên KH/NCC');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Nhóm KH/NCC');
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Địa chỉ');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Mã số thuế');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Điện thoại');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Fax');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Email');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'TK ngân hàng');
        
        //dulieu
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
		$i++;
		foreach($customers as $key=>$value)
		{
            /*
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
            */
            
            $com_per = 0;//0 la to chu, 1 la ca nhan, bo trong la to chuc
            $sup_cus = 0;//bo trong hoac 0 thi vua la khach hang, vua la nha cung cap;  1 la khach hang; 2 la nha cung cap
            
            switch($value['group_name'])
            {
                case "KNN" : {$com_per = 1;$sup_cus = 1;break;}
                case "KNH" : {$com_per = 1;$sup_cus = 1;break;}
                case "CORPORATE" : {$com_per = 0;$sup_cus = 1;break;}
                case "SUPPLIER" : {$com_per = 0;$sup_cus = 2;break;}
                case "KL" : {$com_per = 1;$sup_cus = 1;break;}
                case "TOURISM" : {$com_per = 0;$sup_cus = 1;break;}
                case "ONLINE" : {$com_per = 1;$sup_cus = 1;break;}
                default : {$com_per = 0;$sup_cus = 1;break;}
            }
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $com_per);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $sup_cus);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['group_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['address']?$value['address']:"undefined");
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['tax_code']?$value['tax_code']:'123456');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $value['phone']?$value['phone']:123456);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $value['fax']?$value['fax']:123456);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value['email']?$value['email']:"undefined");
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '123456');
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
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Mã nhóm KH/NCC');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Tên nhóm KH/NCC');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Thuộc');
        //du lieu
  
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
		$i++;
		foreach($customer_groups as $key=>$value)
		{
			$parent = DB::fetch('SELECT id as code FROM customer_group WHERE structure_id='.IDStructure::parent($value['structure_id']),'code');
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $value['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $value['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, ($parent!='ROOT')?$parent:'UN_DE');
			$i++;				
		}
		$fileName = "export/".date('d-m-Y')."/Mau_nhom_khach_hang_nha_cung_cap.xls";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);				
	}
	function save_wh_import($time_in,$time_out)
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
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['supplier_code']?$value['supplier_code']:'CT_UNDE');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['supplier_code']?$value['supplier_name']:'undefined');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['supplier_code']?$value['supplier_address']:'undefined');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['deliver_name']?$value['deliver_name']:'undefined');
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['note']?$value['note']:'undefined');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, HOTEL_CURRENCY);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'NV_UNDE');			
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
				    /*
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
                    */					
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $invoice_detail['product_id']?$invoice_detail['product_id']:'HH_UNDE');
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $invoice_detail['product_id']?$invoice_detail['product_name']:'undefined');
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $invoice_detail['warehouse_code']?$invoice_detail['warehouse_code']:'WA_UNDE');
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $invoice_detail['warehouse_code']?$invoice_detail['warehouse_code']:'155');
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '331');
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $invoice_detail['unit_name']?$invoice_detail['unit_name']:0);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $invoice_detail['num']?$invoice_detail['num']:0);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $invoice_detail['price']?$invoice_detail['price']:0);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $invoice_detail['price']?$invoice_detail['price']*$invoice_detail['num']:0);
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $value['supplier_code']?$value['supplier_code']:'CT_UNDE');
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
	function save_invoice()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        //tieu de
        $i = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Kiêm phiếu xuất');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Ngày chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Ngày hạch toán');			
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Số CT bán hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Mã khách hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Tên khách hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Địa chỉ');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Mã số thuế');
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Người liên hệ');			
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Diễn giải');
		$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, 'Mã hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, 'Tên hàng');
		$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, 'Kho');
		$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, 'TK Nợ');
		$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, 'TK Có');
		$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, 'Đối tượng');
		$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, 'Đơn vị tính');
		$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, 'Số lượng');
		$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, 'Đơn giá');
		$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, 'Thành tiền');
		//$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, 'Tỷ lệ CK (%)');
        $objPHPExcel->getActiveSheet()->setCellValue('AL'.$i, 'Tiền CK');
		$objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, '% thuế GTGT');
		$objPHPExcel->getActiveSheet()->setCellValue('AR'.$i, 'TK thuế GTGT');
		$objPHPExcel->getActiveSheet()->setCellValue('AS'.$i, 'TK kho');
		$objPHPExcel->getActiveSheet()->setCellValue('AT'.$i, 'TK giá vốn');
        
        //du lieu
        
        $data = $this->get_invoice();
        $i++;
        foreach($data as $key=>$value)
		{
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 1);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $value['time']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['time']);			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $value['customer_code']?$value['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $value['customer_code']?$value['full_name']:'underfined');
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $value['customer_code']?$value['customer_address']:'underfined');
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $value['customer_code']?$value['tax_code']:'8306095187');
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['customer_code']?$value['contact_name']:'underfined');			
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $value['descrip']);  
            $objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $value['product_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $value['product_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, $value['warehouse_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $value['acc_revenue_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $value['acc_deposit_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $value['customer_code']?$value['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $value['unit']);
			$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, $value['quantity']);
			$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, $value['price']);
			$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, $value['amount']);
            //$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, $value['discount']);
			$objPHPExcel->getActiveSheet()->setCellValue('AL'.$i, $value['discount']);
			$objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, $value['tax_rate']);
			$objPHPExcel->getActiveSheet()->setCellValue('AR'.$i, $value['tax_acc_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('AS'.$i, $value['warehouse_acc_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('AT'.$i, $value['cogs_acc']);
			$i++;
        } 
		$fileName = "export/".date('d-m-Y')."/Mau_hoa_don_ban_hang_chua_thu_tien.xls";		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);
	}
    
    function get_invoice()
	{
        
        $date = date("d/m/Y");
        
        $month = date('m');
        $year = date('Y');
        
        $date = Date_Time::to_orc_date($date);
        $begin_month = Date_Time::to_orc_date('1/'.$month.'/'.$year);
        
        //kho hang hoa
        $query_total_revenue = "
        select 'BH_REC'||ROOM_STATUS.ID as id
            ,TO_CHAR(ROOM_STATUS.IN_DATE, 'dd/mm/yyyy') as time
            ,case
                 when RESERVATION_ROOM.net_price = 0
                    then CHANGE_PRICE
                 else
                    CHANGE_PRICE/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
            end price
            ,case
                 when RESERVATION_ROOM.net_price = 0
                    then CHANGE_PRICE
                 else
                    CHANGE_PRICE/(1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0)
            end amount
            ,case
                 when RESERVATION_ROOM.net_price = 0
                    then CHANGE_PRICE * NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0 + NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0)
                 else
                    CHANGE_PRICE / (1 + NVL(RESERVATION_ROOM.TAX_RATE,0)/100.0) * NVL(RESERVATION_ROOM.REDUCE_BALANCE,0)/100.0 + NVL(RESERVATION_ROOM.REDUCE_AMOUNT,0)
            end discount
            ,NVL(RESERVATION_ROOM.TAX_RATE,0) as TAX_RATE
            ,customer.code as customer_code
            ,customer.name as full_name
            ,customer.address as customer_address
            ,customer.tax_code
            ,customer.CONTACT_PERSON_NAME as contact_name
            ,'thu tien phong' as descrip
            ,'ROOM_NIGHT' as product_code
            ,'dem phong' as product_name
            ,'156' as warehouse_code
            ,131 as acc_revenue_code
            ,5111 as acc_deposit_code
            ,'dem' as unit
            ,1 as quantity
            ,33311 as tax_acc_code
            ,1561 as warehouse_acc_code
            ,632 as cogs_acc
        from ROOM_STATUS
            inner join RESERVATION on RESERVATION.ID = ROOM_STATUS.RESERVATION_ID
            inner join RESERVATION_ROOM on RESERVATION_ROOM.ID = ROOM_STATUS.RESERVATION_ROOM_ID
            LEFT OUTER JOIN customer on customer.id = reservation.customer_id
        WHERE ROOM_STATUS.STATUS = 'OCCUPIED'
            AND (ROOM_STATUS.HOUSE_STATUS != 'HOUSEUSE' OR ROOM_STATUS.HOUSE_STATUS is null)
            AND (RESERVATION_ROOM.FOC is null and RESERVATION_ROOM.FOC_ALL = 0)
            and ";
        $query_total_housekeepng = "
        select 'BH_HK'||housekeeping_invoice_detail.id as id
            ,TO_CHAR(from_unixtime(housekeeping_invoice.time), 'dd/mm/yyyy') as time
            ,housekeeping_invoice_detail.price
            ,housekeeping_invoice_detail.price 
            ,housekeeping_invoice_detail.PROMOTION
            ,housekeeping_invoice_detail.quantity
            ,housekeeping_invoice.discount as discount_rate
            ,NVL(housekeeping_invoice.tax_rate,0) as TAX_RATE
            ,customer.code as customer_code
            ,customer.name as full_name
            ,customer.address as customer_address
            ,customer.tax_code
            ,customer.CONTACT_PERSON_NAME as contact_name
            ,case 
                when housekeeping_invoice.type='MINIBAR'
                    then 'thu tien minibar'
                when housekeeping_invoice.type='LAUNDRY'
                    then 'thu tien giat la'
                when housekeeping_invoice.type='EQUIP'
                    then 'thu tien boi thuong trang thiet bi'
                else
                    ''
            end descrip
            ,housekeeping_invoice_detail.product_id  as product_code
            ,product.name_1 as product_name
            ,case 
                when housekeeping_invoice.type='MINIBAR'
                    then (SELECT			
            				warehouse.code
            			FROM
            				portal_department
            				INNER JOIN warehouse ON warehouse.id = portal_department.warehouse_id
            			WHERE
            				portal_department.department_code = 'MINIBAR')
                when housekeeping_invoice.type='LAUNDRY'
                    then (SELECT			
            				warehouse.code
            			FROM
            				portal_department
            				INNER JOIN warehouse ON warehouse.id = portal_department.warehouse_id
            			WHERE
            				portal_department.department_code = 'LAUNDRY')
                when housekeeping_invoice.type='EQUIP'
                    then (SELECT			
            				warehouse.code
            			FROM
            				portal_department
            				INNER JOIN warehouse ON warehouse.id = portal_department.warehouse_id
            			WHERE
            				portal_department.department_code = 'EQUIP')
                else
                    ''
            end warehouse_code
            ,131 as acc_revenue_code
            ,5111 as acc_deposit_code
            ,unit.name_1 as unit
            ,33311 as tax_acc_code
            ,1561 as warehouse_acc_code
            ,632 as cogs_acc
        from housekeeping_invoice_detail
            INNER JOIN housekeeping_invoice ON housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
            INNER JOIN product on product.id = housekeeping_invoice_detail.product_id
            INNER JOIN unit on unit.id = product.unit_id
            INNER JOIN reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
            INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
            LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
        where (housekeeping_invoice.type='MINIBAR' 
            or housekeeping_invoice.type='LAUNDRY'
            or housekeeping_invoice.type='EQUIP') and ";
        $query_total_extraservice = "
        select 'BH_EXTRA'||EXTRA_SERVICE_INVOICE_DETAIL.id as id
            ,TO_CHAR(EXTRA_SERVICE_INVOICE_DETAIL.in_date, 'dd/mm/yyyy') as time
            ,EXTRA_SERVICE_INVOICE_DETAIL.price
            ,EXTRA_SERVICE_INVOICE_DETAIL.price * EXTRA_SERVICE_INVOICE_DETAIL.quantity as amount
            ,0 as discount
            ,NVL(EXTRA_SERVICE_INVOICE.TAX_RATE,0) as TAX_RATE
            ,customer.code as customer_code
            ,customer.name as full_name
            ,customer.address as customer_address
            ,customer.tax_code
            ,customer.CONTACT_PERSON_NAME as contact_name
            ,'doanh thu extra service' as descrip
            ,EXTRA_SERVICE.ID as product_code
            ,EXTRA_SERVICE.NAME as product_name
            ,'EX_SER' as warehouse_code
            ,131 as acc_revenue_code
            ,5111 as acc_deposit_code
            ,EXTRA_SERVICE.unit
            ,EXTRA_SERVICE_INVOICE_DETAIL.quantity
            ,33311 as tax_acc_code
            ,1561 as warehouse_acc_code
            ,632 as cogs_acc
        from EXTRA_SERVICE_INVOICE_DETAIL
            inner join EXTRA_SERVICE on EXTRA_SERVICE_INVOICE_DETAIL.SERVICE_ID = EXTRA_SERVICE.ID
            inner join EXTRA_SERVICE_INVOICE on EXTRA_SERVICE_INVOICE_DETAIL.INVOICE_ID = EXTRA_SERVICE_INVOICE.ID
            INNER JOIN reservation_room on reservation_room.id = EXTRA_SERVICE_INVOICE.reservation_room_id
            INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
            LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
        where NVL(reservation_room.foc_all,0) != 1 and (NVL(reservation_room.foc,0) !=1 or EXTRA_SERVICE_INVOICE.payment_type = 'SERVICE') and ";
        $query_total_spa = "
        select 'BH_SPA'||MASSAGE_PRODUCT_CONSUMED.id as id
            ,TO_CHAR(from_unixtime(MASSAGE_PRODUCT_CONSUMED.time_out), 'dd/mm/yyyy') as time
            ,MASSAGE_PRODUCT_CONSUMED.price
            ,MASSAGE_PRODUCT_CONSUMED.price * MASSAGE_PRODUCT_CONSUMED.quantity as amount
            ,MASSAGE_PRODUCT_CONSUMED.price * MASSAGE_PRODUCT_CONSUMED.quantity * NVL(MASSAGE_RESERVATION_ROOM.DISCOUNT,0)/100.0 as discount
            ,NVL(MASSAGE_RESERVATION_ROOM.tax,0) as TAX_RATE
            ,customer.code as customer_code
            ,customer.name as full_name
            ,customer.address as customer_address
            ,customer.tax_code
            ,customer.CONTACT_PERSON_NAME as contact_name
            ,'doanh thu spa' as descrip
            ,PRODUCT.ID as product_code
            ,PRODUCT.NAME_1 as product_name
            ,'SPA' as warehouse_code
            ,131 as acc_revenue_code
            ,5111 as acc_deposit_code
            ,UNIT.name_1 as unit
            ,MASSAGE_PRODUCT_CONSUMED.quantity
            ,33311 as tax_acc_code
            ,1561 as warehouse_acc_code
            ,632 as cogs_acc
        from MASSAGE_PRODUCT_CONSUMED
            inner join PRODUCT on MASSAGE_PRODUCT_CONSUMED.PRODUCT_ID = PRODUCT.ID
            inner join unit on unit.id = PRODUCT.UNIT_ID
            inner join MASSAGE_RESERVATION_ROOM on MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID = MASSAGE_RESERVATION_ROOM.ID
            LEFT OUTER JOIN reservation_room on reservation_room.id = MASSAGE_PRODUCT_CONSUMED.HOTEL_RESERVATION_ROOM_ID
            LEFT OUTER JOIN reservation ON reservation_room.reservation_id = reservation.id
            LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
        where (MASSAGE_PRODUCT_CONSUMED.STATUS= 'CHECKOUT' or MASSAGE_PRODUCT_CONSUMED.STATUS= 'CHECKIN') and ";
        $query_total_sale = "
        select 'BH_SALE'||VE_RESERVATION_PRODUCT.id as id
            ,TO_CHAR(from_unixtime(VE_RESERVATION.time), 'dd/mm/yyyy') as time
            ,VE_RESERVATION_PRODUCT.price
            ,VE_RESERVATION_PRODUCT.price * VE_RESERVATION_PRODUCT.QUANTITY as amount
            ,VE_RESERVATION_PRODUCT.price 
            * (
                VE_RESERVATION_PRODUCT.QUANTITY
                -
                (VE_RESERVATION_PRODUCT.QUANTITY - NVL(VE_RESERVATION_PRODUCT.QUANTITY_DISCOUNT,0))
                *
                (1 - (NVL(VE_RESERVATION_PRODUCT.PROMOTION,0) + NVL(VE_RESERVATION_PRODUCT.DISCOUNT_RATE,0))/100)
                *
                (1 - NVL(VE_RESERVATION.DISCOUNT,0)/100)
               )
            as discount
            ,NVL(VE_RESERVATION.TAX_RATE,0) as TAX_RATE
            ,VENDING_CUSTOMER.code as customer_code
            ,VENDING_CUSTOMER.name as full_name
            ,VENDING_CUSTOMER.address as customer_address
            ,VENDING_CUSTOMER.tax_code
            ,VENDING_CUSTOMER.CONTACT_PERSON_NAME AS contact_name
            ,'doanh thu ban hang' as descrip
            ,PRODUCT.ID as product_code
            ,PRODUCT.NAME_1 as product_name
            ,'SALE' as warehouse_code
            ,131 as acc_revenue_code
            ,5111 as acc_deposit_code
            ,UNIT.name_1 as unit
            ,VE_RESERVATION_PRODUCT.quantity
            ,33311 as tax_acc_code
            ,1561 as warehouse_acc_code
            ,632 as cogs_acc
        from VE_RESERVATION_PRODUCT
            inner join PRODUCT on VE_RESERVATION_PRODUCT.PRODUCT_ID = PRODUCT.ID
            inner join unit on unit.id = PRODUCT.UNIT_ID
            inner join VE_RESERVATION on VE_RESERVATION.id = VE_RESERVATION_PRODUCT.BAR_RESERVATION_ID
            left outer join VENDING_CUSTOMER on VENDING_CUSTOMER.id = VE_RESERVATION.CUSTOMER_ID
        where ";
        $query_total_ticket = "
        select TICKET_INVOICE.id
            ,from_unixtime(TICKET_INVOICE.time)
            ,TICKET_INVOICE.price
            ,TICKET_INVOICE.price * TICKET_INVOICE.QUANTITY as amount
            ,(TICKET_INVOICE.price * TICKET_INVOICE.QUANTITY
            -
             (TICKET_INVOICE.price * (1 - NVL(TICKET_INVOICE.DISCOUNT_RATE,0)/100) - NVL(TICKET_INVOICE.DISCOUNT_CASH,0))
             * 
             (TICKET_INVOICE.QUANTITY - NVL(TICKET_INVOICE.DISCOUNT_QUANTITY,0))
            ) as DISCOUNT
            ,TICKET_RESERVATION.TAX_RATE
            ,customer.code as customer_code
            ,customer.name as full_name
            ,customer.address as customer_address
            ,customer.tax_code
            ,customer.CONTACT_PERSON_NAME as contact_name
            ,'doanh thu ban ve' as descrip
            ,TICKET.ID as product_code
            ,TICKET.NAME as product_name
            ,'TICKET' as warehouse_code
            ,131 as acc_revenue_code
            ,5111 as acc_deposit_code
            ,'chiec' as unit
            ,TICKET_INVOICE.quantity
            ,33311 as tax_acc_code
            ,1561 as warehouse_acc_code
            ,632 as cogs_acc
        from TICKET_INVOICE
            inner join ticket on ticket_invoice.ticket_id = ticket.id 
            inner join ticket_reservation on ticket_reservation.id = ticket_invoice.ticket_reservation_id
            LEFT OUTER JOIN customer ON customer.id = TICKET_RESERVATION.customer_id
        where ";
        $query_total_bar = "
        select BAR_RESERVATION_PRODUCT.id
            ,from_unixtime(BAR_RESERVATION.TIME_OUT)
            ,BAR_RESERVATION_PRODUCT.price
            ,BAR_RESERVATION_PRODUCT.price * BAR_RESERVATION_PRODUCT.QUANTITY as amount
            ,BAR_RESERVATION_PRODUCT.price 
            * 
            (
                BAR_RESERVATION_PRODUCT.QUANTITY
                -
                (BAR_RESERVATION_PRODUCT.QUANTITY - BAR_RESERVATION_PRODUCT.QUANTITY_DISCOUNT)
                *
                (1 - BAR_RESERVATION_PRODUCT.DISCOUNT_RATE/100)
                *
                (1 - BAR_RESERVATION.DISCOUNT_PERCENT/100)
            ) 
            +
            
            as discount
            ,BAR_RESERVATION_PRODUCT.DISCOUNT_RATE
            ,BAR_RESERVATION_PRODUCT.DISCOUNT as discount_amount
            ,BAR_RESERVATION.TAX_RATE
            ,BAR_RESERVATION.DISCOUNT as invoice_discount
            ,customer.code as customer_code
            ,customer.name as full_name
            ,customer.address as customer_address
            ,customer.tax_code
            ,customer.CONTACT_PERSON_NAME as contact_name
            ,'doanh thu nha hang' as descrip
            ,product.ID as product_code
            ,product.NAME_1 as product_name
            ,'BAR' as warehouse_code
            ,131 as acc_revenue_code
            ,5111 as acc_deposit_code
            ,'chiec' as unit
            ,BAR_RESERVATION_PRODUCT.quantity
            ,BAR_RESERVATION_PRODUCT.QUANTITY_DISCOUNT
            ,33311 as tax_acc_code
            ,1561 as warehouse_acc_code
            ,632 as cogs_acc
        from BAR_RESERVATION_PRODUCT
            inner join BAR_RESERVATION on BAR_RESERVATION.id = BAR_RESERVATION_PRODUCT.BAR_RESERVATION_ID 
            INNER JOIN product on product.id = BAR_RESERVATION_PRODUCT.product_id
            INNER JOIN unit on unit.id = product.unit_id
            left outer JOIN reservation_room on reservation_room.id = BAR_RESERVATION.reservation_room_id
            left outer JOIN reservation ON reservation_room.reservation_id = reservation.id
            LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
        where (BAR_RESERVATION.STATUS='CHECKOUT' or BAR_RESERVATION.STATUS='CHECKIN') AND ";
        
        $cond_potal = " and 1=1 ";
        if(Url::get('portal_id'))
            $cond_potal .= "and portal_id = '".Url::get('portal_id')."'";
            
        $cond_potal_room = " and 1=1 ";
        if(Url::get('portal_id'))
            $cond_potal_room .= "and RESERVATION.portal_id = '".Url::get('portal_id')."'";
        
        $cond_potal_spa = " and 1=1 ";
        if(Url::get('portal_id'))
            $cond_potal_spa .= "and MASSAGE_RESERVATION_ROOM.portal_id = '".Url::get('portal_id')."'";
        
        $cond_potal_ticket = " and 1=1 ";
        if(Url::get('portal_id'))
            $cond_potal_ticket .= "and TICKET_RESERVATION.portal_id = '".Url::get('portal_id')."'";
            
        $cond_potal_bar = " and 1=1 ";
        if(Url::get('portal_id'))
            $cond_potal_bar .= "and BAR_RESERVATION.portal_id = '".Url::get('portal_id')."'";
        
        //
        $room_cond = "room_status.IN_DATE <= '".$date."' and room_status.IN_DATE >= '".$begin_month."' AND (IN_DATE < DEPARTURE_TIME OR  DEPARTURE_TIME=ARRIVAL_TIME)".$cond_potal_room;
        $housekeepng_cond = "to_date(FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME),'DD-mon-YY') <= '".$date."' AND to_date(FROM_UNIXTIME(HOUSEKEEPING_INVOICE.TIME),'DD-mon-YY') >= '".$begin_month."'".$cond_potal;
        $extraservice_cond = "to_date(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE) <= '".$date."' AND to_date(EXTRA_SERVICE_INVOICE_DETAIL.IN_DATE) >= '".$begin_month."'".$cond_potal;
        $spa_cond = "to_date(FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.time_in),'DD-mon-YY') <= '".$date."' AND to_date(FROM_UNIXTIME(MASSAGE_PRODUCT_CONSUMED.time_in),'DD-mon-YY') >= '".$begin_month."'".$cond_potal_spa;
        $sale_cond = "to_date(FROM_UNIXTIME(VE_RESERVATION.TIME),'DD-mon-YY') <= '".$date."' AND to_date(FROM_UNIXTIME(VE_RESERVATION.TIME),'DD-mon-YY') >= '".$begin_month."'".$cond_potal;
        $ticket_cond = "to_date(FROM_UNIXTIME(TICKET_INVOICE.TIME),'DD-mon-YY') <= '".$date."' AND to_date(FROM_UNIXTIME(TICKET_INVOICE.TIME),'DD-mon-YY') >= '".$begin_month."'".$cond_potal_ticket;
        $bar_cond = "to_date(FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME),'DD-mon-YY') <= '".$date."' AND to_date(FROM_UNIXTIME(BAR_RESERVATION.DEPARTURE_TIME),'DD-mon-YY') >= '".$begin_month."'".$cond_potal_bar;
        
        /*
        $result['TOTAL_ROOM_IN_MONTH'] = DB::fetch_all($query_total_revenue.$room_cond);
        $result['TOTAL_HOUSEKEEPING_IN_MONTH'] = DB::fetch_all($query_total_housekeepng.$housekeepng_cond);
        
        foreach($result['TOTAL_HOUSEKEEPING_IN_MONTH'] as $key => $value)
        {
            if(defined('NET_PRICE_SERVICE'))
                $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['price'] = $value['price']/(1 + $value['tax_rate']/100.0);
            $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['amount'] = $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['price'] * $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['quantity'];
            $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['discount'] = $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['amount']-
                                                                    $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['price'] 
                                                                    *
                                                                    ($result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['quantity'] - $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['promotion'])*(1 - $result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['discount_rate']/100);
            unset($result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['discount_rate']);
            unset($result['TOTAL_HOUSEKEEPING_IN_MONTH'][$key]['promotion']);
        }
        $result['TOTAL_EXTRASERVICE_IN_MONTH'] = DB::fetch_all($query_total_extraservice.$extraservice_cond);
        $result['TOTAL_SPA_IN_MONTH'] = DB::fetch_all($query_total_spa.$spa_cond);
        $result['TOTAL_SALE_IN_MONTH'] = DB::fetch_all($query_total_sale.$sale_cond);
        */
        $result['TOTAL_TICKET_IN_MONTH'] = DB::fetch_all($query_total_ticket.$ticket_cond);
        //$result['TOTAL_BAR_IN_MONTH'] = DB::fetch_all($query_total_bar.$bar_cond);
         
        return $result['TOTAL_TICKET_IN_MONTH'];
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
	function save_payment_cash()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);	
        
        //tieu de
        $i=1;
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Ngày chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Ngày hạch toán');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Số chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Mã đối tượng');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Tên đối tượng');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Địa chỉ');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Loại tiền');
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Diễn giải');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'TK Nợ');
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'TK Có');
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'Số tiền');
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Đối tượng');
        
        //du lieu
        	
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
		$i++;
		foreach($payments as $key=>$value)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['customer_code']?$value['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['customer_code']?$value['customer_name']:'Khách lẻ');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['customer_address']?$value['customer_address']:'undefined');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, HOTEL_CURRENCY);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['description']?$value['description']:'note');
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 1111);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 131);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $value['amount']?$value['amount']:0);
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $value['customer_code']?$value['customer_code']:'KL');			
			$i++;
		}
		$fileName = "export/".date('d-m-Y')."/Mau_chung_tu_thu_tien.xls";		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($fileName);		
	}
	function save_payment_credit()
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        
        $i=1;
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Ngày chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Ngày hạch toán');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Số chứng từ');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Mã đối tượng');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Tên đối tượng');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Địa chỉ');
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Nộp vào TK');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Loại tiền');
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Diễn giải');
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'TK Nợ');
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'TK Có');
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'Số tiền');
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Đối tượng');
        		
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
		$i++;
		foreach($payments as $key=>$value)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, date('d/m/Y',$value['time']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['customer_code']?$value['customer_code']:'KL');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['customer_code']?$value['customer_name']:'Khách lẻ');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['customer_code']?$value['customer_address']:'undefined');
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$i, '0001232225411', PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, HOTEL_CURRENCY);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $value['description']?$value['description']:'note');
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 1121);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 131);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $value['amount']?$value['amount']:0);
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
		$this->parse_layout('reven');
        
        if(Url::get('act'))
        {
            require_once 'packages/core/includes/utils/vn_code.php';
    		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel.php';		
    		require_once ROOT_PATH.'packages/core/includes/utils/PHPExcel/RichText.php';
    		include ROOT_PATH.'packages/core/includes/utils/PHPExcel/IOFactory.php';
    		//set_time_limit(0);
    		$from = Date_Time::to_time(Url::get('date_from')?Url::get('date_from'):'01/06/2010');
    		$to = Date_Time::to_time(Url::get('date_to')?Url::get('date_to'):date('d/m/Y',time()))+24*3600;
    		if(!is_dir('export/'.date('d-m-Y')))
    		{
    			mkdir('export/'.date('d-m-Y'));
    		}
            $this->export_reven($from,$to);
    		/*----------------- Danh muc mat hang -------------------------*/
    		// Create new PHPExcel object
    		//$this->save_product();//Mau_vat_tu_hang_hoa_cong_cu_dung_cu -OK
    		//$this->save_product_category();//Mau_loai_vat_tu_hang_hoa -OK
    		/*---------------- /Danh muc mat hang--------------------------*/
    		/*----------------- Danh muc khach hang -----------------------*/
    		//$this->save_customer();//Mau_khach_hang_nha_cung_cap -OK
    		//$this->save_customer_group();//Mau_nhom_khach_hang_nha_cung_cap
    		//$this->save_wh_import($time_in,$time_out);//Mau_chung_tu_nhap_kho
    		//$this->save_payment_cash();//Mau_chung_tu_thu_tien
    		//$this->save_payment_credit();//Mau_chung_tu_nop_tien_vao_tai_khoan
            //$this->save_invoice();//Mau_hoa_don_ban_hang_chua_thu_tien
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
            //Url::redirect_current();
        }
	}

}
?>