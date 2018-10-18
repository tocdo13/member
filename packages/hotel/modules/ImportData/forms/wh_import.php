<?php
class WhImportForm extends Form
{
	function WhImportForm()
	{
		Form::Form('WhImportForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');		
	}
	function on_submit()
	{		
        require_once 'packages/core/includes/utils/vn_code.php';
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        //import to db
        $warehouses = array();
        $product_remain = array();
        if(Url::get('save'))
        {
            if(isset($_SESSION['update']))
            {
                //dua du lieu vao db
                foreach($_SESSION['update'] as $key=>$value)
                {
                    if(!isset($warehouses[$value['warehouse_id']]) and $value['status']==0)
                    {
                        $warehouses[$value['warehouse_id']] = 1;
                        $product_remain += get_remain_products($value['warehouse_id']);
                        
                    }
                }
                
                //get remain and old price
                foreach($_SESSION['update'] as $key=>$product)
        		{
                    if($product['status']!=0)
                        continue;
                    $time_create = Date_Time::to_time(str_replace('-','/',$product['create_date']));
                    //neu sp ton tai trong remain
        			if(isset($product_remain[$product['product_id']]) and $product['product_id']==$product_remain[$product['product_id']]['id'])
        			{
        				$product['remain_num'] = $product_remain[$product['product_id']]['remain_number'];
                        //Ton tai gia (tuc la da co phieu nhap, tinh dc gia TB roi`)
                        if(isset($product_remain[$product['product_id']]['price']))
        				{
        					$product['old_price'] = $product_remain[$product['product_id']]['price'];
        				}
        				else
        				{
        				    //neu khong thi lay gia la gia ton dau ki
            				if(isset($product_remain[$product['product_id']]['start_term_price']))
            				    $product['old_price'] = $product_remain[$product['product_id']]['start_term_price'];
                            else
                                $product['old_price'] = 0;
        				}
                        
                        $product['lastest_imported_price'] = 0;  
                        if(isset($product_remain[$product['product_id']]['lastest_imported_price']) and $product_remain[$product['product_id']]['lastest_imported_price'] > 0)
    				        $product['lastest_imported_price'] = $product_remain[$product['product_id']]['lastest_imported_price'];
        			}
        			else
        			{
        				$product['remain_num'] = 0;
        				$product['old_price'] = 0;
                        $product['lastest_imported_price'] = 0;  
        			}
                    
                    //nhap kho
                    $action = '';$title = '';$description = '';$id = 0; // For log
                    /**
                     * Xu ly bang wh_invoice
                     */
                    //cac thong tin chung
                	$array = array(
                        'type'=>'IMPORT',
    					'deliver_name'=>Session::get('user_id'),
    					'note'=>'',
    					'receiver_name'=>'',
    					'warehouse_id'=>$product['warehouse_id'],
    					'total_amount'=>$product['payment_price'],
    					'create_date'=>Date_Time::convert_time_to_ora_date($time_create),
    					'portal_id'=>PORTAL_ID
    				);
                    $description = '
    					Bill number: '.$this->get_new_bill_number($product['warehouse_id']).'<br>
    					Type: IMPORT<br>
    					Create date: '.date('d/m/y',$time_create).'<br>
    					Deliver name: <br>
    					Receiver name: <br>
    					Note: <br>
    					Total amount: '.$product['payment_price'].'<br>
    					Warehouse: '.$product['warehouse_id'].'<br>';
                    $array['bill_number'] = $this->get_new_bill_number($product['warehouse_id']);
                    $id = DB::insert('wh_invoice',$array+array('user_id'=>Session::get('user_id'),'time'=>$time_create));
					$action = 'Add';
					$title = 'Add warehouse invoice '.$id.'';
                    $invoice_id = $id;
                    if(!DB::select('wh_start_term_remain','product_id=\''.$product['product_id'].'\' and warehouse_id='.$product['warehouse_id'].' and portal_id = \''.PORTAL_ID.'\' '))
                   	{
              		    DB::insert('wh_start_term_remain',array('product_id'=>$product['product_id'],'warehouse_id'=>$product['warehouse_id'],'quantity'=>0,'portal_id'=>PORTAL_ID));
                   	}
                    $description .= '<hr>';
                    $remain_num = $product['remain_num'];
                    $old_average_price = $product['old_price'];
                    //So tong va gia trung binh cu, de tinh gia trung binh moi
                    //Lay so hoa don
					$product['invoice_id'] = $invoice_id;
                    $description .= 'Add [Product id: '.$product['product_id'].', Price: '.(isset($product['price'])?$product['price']:0).', Quantity: '.$product['num'].']<br>';
					//neu la phieu nhap thi tinh l?i gia trung binh
                    //Lay so luong san pham xuat chuyen sang kho khac
                    $export_quantity = 0;
                                        
                    //nhap vao so tien de dieu chinh gia binh quan
                    // so luong = 0
                    // price o day lay la tong to tien nhap vao
                    $product['average_price'] = round( 
                                                (($remain_num+$export_quantity) * $old_average_price + $product['price'] * $product['num'])
                                                /($remain_num +$export_quantity+ $product['num'])  ,4);
                    $product['time_calculation'] = $time_create;
                    
                    unset($product['create_date']);
                    unset($product['name']);
                    unset($product['unit']);
                    unset($product['unit_id']);
                    unset($product['payment_price']);
                    unset($product['warehouse_code']);
                    unset($product['note']);
                    unset($product['stt']);
                    unset($product['status']);
                    unset($product['remain_num']);
                    unset($product['old_price']);
                    
                    DB::insert('wh_invoice_detail',$product);
                    
                    System::log($action,$title,$description,$invoice_id);
                    $_SESSION['update'][$key]['status'] = 1;
                    if(isset($product_remain[$product['product_id']]))
        			{
                        $product_remain[$product['product_id']]['price'] = $product['average_price'];
                        $product_remain[$product['product_id']]['remain_number'] += $product['num'];
                        $product_remain[$product['product_id']]['lastest_imported_price'] = $product['lastest_imported_price'];
                    }
                    else
                    {
                        $product_remain[$product['product_id']] = array();
                        $product_remain[$product['product_id']]['price'] = $product['average_price'];
                        $product_remain[$product['product_id']]['remain_number'] = $product['num'];
                        $product_remain[$product['product_id']]['lastest_imported_price'] = $product['lastest_imported_price'];
                    }
        		}
            }
        }
	}
	function draw()
	{
		$this->map = array();
        $this->map['title'] = Portal::language('import');
        
        //upload file anh preview
        if(Url::get('do_upload'))
		{
            $file = $this->save_file('path_file');
            
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);			
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            //file chi co 1 sheet
            //xu ly du lieu va delete file
            $this->result = $this->parse_sheet($sheetData);
            //System::debug($this->result);exit();
            @unlink($file);
            //Luu vao session
            $_SESSION['update'] = $this->result;
            $this->map['items'] = $this->result;
            //System::debug($_SESSION['content']);
            $this->parse_layout('wh_import', $this->map);
		}
        else if(Url::get('save'))
        {
            if(!empty($_SESSION['update']))
                $this->map['items'] = $_SESSION['update'];
            else
                $this->map['items'] = array();
            
            $this->parse_layout('wh_import',$this->map); 
            unset($_SESSION['update']);
        }
        else{
            $this->map['items'] = array();
            $this->parse_layout('wh_import',$this->map); 
        }
	}
    
    function save_file($file)
	{
		require_once 'packages/core/includes/utils/upload_file.php';
		$dir = 'excel';
		update_upload_file('path',$dir);
		return Url::get('path');
	}
    
    function parse_sheet($sheet)
	{
        require_once 'packages/core/includes/utils/vn_code.php';
        $result = array();
        $i = 1;
        foreach($sheet as $rows=>$col)
        {
			if($rows > 1 and !($col['A'] == '' and $col['B'] == '' and $col['C'] == '' and $col['D'] == '' and $col['E'] == '' and $col['F'] == '' and $col['G'] == '' and $col['H'] == ''))
			{
			    $date = $this->set_array($col,'A');
			    $product_id = $this->set_array($col,'B');
                $product_name = $this->set_array($col,'C');
                $unit = $this->set_array($col,'D');
                if(!$unit)
   				{
  					$unit_id = 97;
  					$unit = Portal::language('piece');
   				}
   				else
   				{
  					if($row = DB::fetch('Select * from unit where (UPPER(unit.name_1)) = UPPER(\''.trim(($unit)).'\') OR (UPPER(unit.name_2)) = UPPER(\''.trim(($unit)).'\') '))
  					{
 						$unit_id = $row['id'];
 						$unit = $row['name_1'];
  					}
  					else
  					{
 						$unit_id = DB::insert('unit',array('name_1'=>$unit,'name_2'=>$unit,'value'=>1));
  					}
   				}
                $quantity = $this->set_array($col,'E');
                $price = $this->set_array($col,'F');
                $amount = $this->set_array($col,'G');
                $warehouse_code = $this->set_array($col,'H');
                if($warehouse_code)
                {
                    $warehouse_id = DB::fetch('select id from warehouse where code = \''.$warehouse_code.'\'','id');
                }
                else
                {
                    $warehouse_id = '';
                }
                
                $result[$i] = array(  'create_date' => $date,
                                        'product_id' => $product_id,
                                        'name' => $product_name,
                                        'unit' => $unit,
                                        'unit_id' => $unit_id,
                                        'num' => $quantity,
                                        'price' => $price,
                                        'payment_price' => $amount,
                                        'warehouse_code' => $warehouse_code,
                                        'warehouse_id' => $warehouse_id,
                                        'note' => '',
                                        'stt' => $i,
                                        'status' => 0
                                        //'type' => $type,
                                        //'category' => $category
                                        );
                if($amount=='')
                {
                    if($quantity != '' and $price != '')
                    {
                        $result[$i]['payment_price'] = $quantity * $price;
                    }
                    else
                    {
                        $result[$i]['payment_price'] = 0;
                    }
                }
                else
                {
                    $result[$i]['payment_price'] = $amount;
                }
                if($quantity!='' and $amount!='' and $price=='')
                {
                    $result[$i]['price'] = $amount/$quantity;
                }
                //System::debug($result);
                //check error
                if(!DB::exists("Select * from product where id = '".$product_id."' and name_1 = '".$product_name."' and unit_id =".$unit_id))
                {
                    if(!$result[$i]['note'])
                    {
                        $result[$i]['status'] = -1;
                        $result[$i]['note'] = 'Invalid product';       
                    }
                    else
                    {
                        $result[$i]['note'] .= ', product';
                    }
                }
                /*
                if($quantity != '' and $price != '' and $amount != '' and $quantity*$price != $amount)
                {
                    if(!$result[$i]['note'])
                    {
                        $result[$i]['status'] = -1;
                        $result[$i]['note'] = 'Invalid amount';       
                    }
                    else
                    {
                        $result[$i]['note'] .= ', amount';
                    }
                }
                */
                if($product_id == '' or $product_name == '' or $date == '' or $quantity == '' or $price == '' /*or $amount == '' or $warehouse_id == '' or $type == '' or $category == ''*/)
                {
                    if($product_id == '')
                    {
                        if(!$result[$i]['note'])
                        {
                            $result[$i]['status'] = -1;
                            $result[$i]['note'] = 'Invalid product_id';       
                        }
                        else
                        {
                            $result[$i]['note'] .= ', product_id';
                        }
                    }
                    if($product_name == '')
                    {
                        if(!$result[$i]['note'])
                        {
                            $result[$i]['status'] = -1;
                            $result[$i]['note'] = 'Empty product_name';       
                        }
                        else
                        {
                            $result[$i]['note'] .= ', product_name';
                        }
                    }
                    if($date == '')
                    {
                        if(!$result[$i]['note'])
                        {
                            $result[$i]['status'] = -1;
                            $result[$i]['note'] = 'Invalid create_date';       
                        }
                        else
                        {
                            $result[$i]['note'] .= ', create_date';
                        }
                    }
                    if($quantity == '')
                    {
                        if(!$result[$i]['note'])
                        {
                            $result[$i]['status'] = -1;
                            $result[$i]['note'] = 'Invalid quantity';       
                        }
                        else
                        {
                            $result[$i]['note'] .= ', quantity';
                        }
                    }
                    if($price == '' and $amount =='')
                    {
                        if(!$result[$i]['note'])
                        {
                            $result[$i]['status'] = -1;
                            $result[$i]['note'] = 'Invalid price';       
                        }
                        else
                        {
                            $result[$i]['note'] .= ', price';
                        }
                    }
                    /*
                    if($amount == '')
                    {
                        if(!$result[$i]['note'])
                        {
                            $result[$i]['status'] = -1;
                            $result[$i]['note'] = 'Invalid amount';       
                        }
                        else
                        {
                            $result[$i]['note'] .= ', amount';
                        }
                    }
                    */
                    if($warehouse_id == '')
                    {
                        if(!$result[$i]['note'])
                        {
                            $result[$i]['status'] = -1;
                            $result[$i]['note'] = 'Invalid warehouse_id';       
                        }
                        else
                        {
                            $result[$i]['note'] .= ', warehouse_id';
                        }
                    }
                }
                
                $i++;
			}    
        }
        return $result;    
	}
    function set_array($col,$index,$default='')
    {
        if(isset($col[$index]))
            return trim($col[$index]);
        else
            return $default;
    }
    
    function get_new_bill_number($warehouse_id)
    {
        $prefix = 'PN';
            
        $code_wh = DB::fetch('SELECT WAREHOUSE.code as code
                                      FROM WAREHOUSE 
                                      where WAREHOUSE.id = '.$warehouse_id.' and WAREHOUSE.portal_id = \''.PORTAL_ID.'\'
                                      ','code');
        $max_bill =  DB::fetch("SELECT max(TO_NUMBER(REPLACE(bill_number,'".$prefix."-".$code_wh."'))) as bill
                                      FROM wh_invoice 
                                      where 
                                        wh_invoice.type='IMPORT' and wh_invoice.WAREHOUSE_ID = ".$warehouse_id." and wh_invoice.portal_id = '".PORTAL_ID."'
                                      ",'bill');
        
        if(!$max_bill)
            $max_bill = 0;
        $bill = $prefix."-".$code_wh.($max_bill + 1);
        return $bill;
    }
}
?>