<?php
class EditWarehouseInvoiceForm extends Form
{
	function EditWarehouseInvoiceForm()
	{
		Form::Form('EditWarehouseInvoiceForm');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/invoice.css');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->add('bill_number',new TextType(true,'miss_bill_number',0,255));
		$this->add('create_date',new DateType(true,'invalid_create_date'));
		$this->add('product.product_id',new TextType(true,'product_id_is_required',0,255));
        $this->add('product.unit_id',new TextType(true,'unit_is_required',0,255));
        $this->add('product.category_id',new TextType(true,'category_is_required',0,255));
        $this->add('product.type',new TextType(true,'type_is_required',0,255));
		$this->add('product.num',new FloatType(true,'miss_quantity',0.0000000000,1000000000));
		//$this->add('receiver_name',new TextType(false,'invalid_receiver_name',0,255));
        //$this->add('wh_receiver_name',new TextType(false,'invalid_receiver_name',0,255));
        //$this->add('product.price',new TextType(true,'invalid_price',0,255));
	}
    function display_number_four($num)
	{
		$num = $num?$num:0;
		if($num==round($num))
		{
			return number_format($num,0,'.',',');
		}
		else
		{
			return number_format($num,4,'.',',');
		}
	}
	function on_submit()
    {
        if(Url::get('cmd')=='add' && !isset($_REQUEST['mi_product']))
            return false;  
        if($this->check())
        {
            //lay san pham ton kho cua kho do
            $product_remain = get_remain_products(Url::get('warehouse_id'),false,false,$_REQUEST['mi_product'],false);
            //System::debug($product_remain);
    		$check = false;
    		$data_check = array();
            
            if(Url::check('move_product') or Url::check('direct_export'))
            {
                if(Url::get('to_warehouse_id'))
            	{
                    $product_remain_to_wh = get_remain_products(Url::get('to_warehouse_id'),false,false,$_REQUEST['mi_product'],false);
            	} 
            }        
            if(isset($_REQUEST['mi_product']) and Url::get('type')=='EXPORT')
    		{
                foreach($_REQUEST['mi_product'] as $id=>$product)
    			{
    				//So luong xuat ra
                    $quantity = 0;
    				//Buoc nay de check so luong xuat ra trong truong hop co 2 mi cung` m? sp
                    //theo ban moi thi se khong cho nhap 2 mi cung ma
                    if(!isset($data_check[$product['product_id']]))
    				{
    					$data_check[$product['product_id']] = $product['num'];
    					$quantity = $product['num'];
    				}
    				else
    					$quantity = $data_check[$product['product_id']]+$product['num'];
                    
                    //neu co khai bao ton dau ki
                    if(isset($product_remain[$product['product_id']]))
                    {
                        //neu so luong xuat ra l? so am
    					if($product['num']<=0)
    					{
    						$this->error('quantity_'.$id,Portal::language('product').' "'.$product['name'].'" '.Portal::language('is_lager_than_0'),false);
    						$check = true;
    					}
    					else
                        {
                            //neu khong cho phep xuat am thi kiem tra ton kho
                            if(ALLOW_OVER_EXPORT==0)
                            {
                                //neu ton kho = 0
                                if($product_remain[$product['product_id']]['remain_number']==0)
            					{
            						$this->error('quantity_'.$id,Portal::language('product').' "'.$product['product_id'].'" '.Portal::language('is_empty'),false);
            						$check = true;
            					}
            					else
                                    //neu so luong dinh xuat ra lon hon so luong trong kho
                                    if($quantity>$product_remain[$product['product_id']]['remain_number'])
                					{
                						$this->error('quantity_'.$id,Portal::language('You_only_export').' "'.$product['name'].'" '.Portal::language('is_smaller').' '.$product_remain[$product['product_id']]['remain_number'],false);
                						$check = true;
                					}
                            }
                        }
    				}
    				else//neu khong khai bao ton dau ki
    				{
    					$this->error('product_'.$id,Portal::language('Product').' "'.$product['name'].'" '.Portal::language('not_exists_in_warehouse'));
    					$check = true;
    				}
    			}//end foreach
    		}
    		if($check)
    		{
    			return false;
    		}
            
            $error = false;
			$action = '';$title = '';$description = '';$id = 0; // For log
            
            //edit ma bill number khong dung vs id
            $sql = 'select 
                        id,
                        bill_number 
                    from 
                        wh_invoice 
                    where 
                        bill_number = \''.Url::sget('bill_number').'\' 
                        and id<>'.Url::iget('id').' 
                        and wh_invoice.portal_id=\''.PORTAL_ID.'\'';
            $exist = DB::fetch_all($sql);
            if(User::is_admin())
            {
                //System::debug($exist);
                //exit();
            }
            if(Url::get('cmd')=='edit' and DB::exists('select id,bill_number from wh_invoice where bill_number = \''.Url::sget('bill_number').'\' and id<>'.Url::iget('id').' and wh_invoice.portal_id=\''.PORTAL_ID.'\''))
            {
				$this->error('bill_nubmer','bill_number_is_duplicated');
				$error = true;
			}
			if($error==false)
            {
				$import_id = 0;
                /**
                 * Xu ly bang wh_invoice
                 */
                //cac thong tin chung
                
            	$array = array(
					'type'=>Url::get('type'),
					'deliver_name'=>Url::get('deliver_name'),
					'wh_receiver_name'=>Url::get('wh_receiver_name'),
                    'note'=>Url::get('note'),
					'receiver_name'=>Url::get('receiver_name'),
					'warehouse_id'=>Url::get('warehouse_id'),
					'total_amount'=>str_replace(',','',Url::get('total_amount')),
					'create_date'=>Date_Time::to_orc_date(Url::get('create_date')),
                    
                    
                    'tax'=> System::calculate_number(Url::get('tax')),
					'portal_id'=>PORTAL_ID
				); // manh bo dong 'tax_code'=> Url::get('tax_code'),'address' =>Url::get('address'),
                
                //so hoa don khi nhap hang
                if(Url::get('type')=='IMPORT')
                {
                    $array+= array('invoice_number'=>Url::get('invoice_number'));
                }
				
                $suppler_id = '';
                if(Url::get('supplier_id'))
				{
				    if(Url::get('type')=='IMPORT')//import thi nhap code cua ncc
                    {
    					if(Url::get('supplier_id')!='')
                        {
                            if($row = DB::select('supplier','id='.Url::get('supplier_id').''))
        					{
                                $array['supplier_id'] = $row['id'];
        					}
                            else
        					{
        						$data = array(
        							'code'=>strtoupper(Url::get('supplier_id')),
        							'name'=>Url::get('supplier_name'),
        						);
                                $array['supplier_id'] = DB::insert('supplier',$data);
        					}
                        }
                        else
                        {
                            $array['supplier_id'] = '';
                        }
                    }
				}
                else
                {
                    $array['supplier_id'] = '';
                }
                if(Url::get('move_product') and Url::get('type')=='EXPORT')
                {
					$array += array('move_product'=>1);
                    $array['note'] = Url::get('note')?Url::get('note'):Portal::language('move_product');
				}
                if(Url::get('get_back_supplier') and Url::get('type')=='EXPORT')
                {
					$array['supplier_id'] = Url::get('ware_supplier_id');
                    $array += array('get_back_supplier'=>1);
                    $array['note'] = Url::get('note')?Url::get('note'):Portal::language('returned_supplier');
				}
                if(!Url::get('get_back_supplier') and Url::get('type')=='EXPORT')
                {
                    $array += array('get_back_supplier'=>0);
                    $array['supplier_id'] = '';
                    //$array['note'] = '';
                    //$array['supplier_id'] = '';
                }
                if(Url::get('direct_export') and Url::get('type')=='IMPORT' and Url::get('cmd')=='add')
                {
					$array += array('direct_export'=>1);

                    $array['note'] = Url::get('note')?Url::get('note'):Portal::language('direct_export');
				}
                $bill_log = Url::get('bill_number')?Url::get('bill_number'):$this->get_new_bill_number(Url::sget('type'));
                $description = '
					Bill number: '.$bill_log.'<br>
					Type: '.Url::get('type').'<br>
					Create date: '.Url::get('create_date').'<br>
					Deliver name: '.Url::get('deliver_name').'<br>
					Receiver name: '.Url::get('receiver_name').'<br>
					Wh receiver name: '.Url::get('wh_receiver_name').'<br>
                    Note: '.($array['note']?$array['note']:Url::get('note')).'<br>
					Total amount: '.Url::get('total_amount').'<br>
					Warehouse: '.Url::get('warehouse_id').'<br>
					'.((Url::get('move_product') and Url::get('type')=='EXPORT')?'Move product<br>':'').'
                    '.((Url::get('get_back_supplier') and Url::get('type')=='EXPORT')?'Get back supplier<br>':'').'
                    '.((Url::get('direct_export') and Url::get('type')=='IMPORT')?'Direct export<br>':'').'
				';
				if(Url::get('cmd')=='edit' || Url::get('cmd')=='saves')
                {
					$id = Url::iget('id');
                    $isMoveProduct = DB::fetch('Select move_product from wh_invoice where id ='.$id,'move_product');
                    if($isMoveProduct==1) $isMoveProduct = true;
					$action = 'Edit';
					$title = 'Edit warehouse invoice '.$id.'';
					DB::update('wh_invoice',$array+array('last_modify_user_id'=>Session::get('user_id'),'last_modify_time'=>time()),'id='.Url::iget('id'));
				}
                else
                {
                    if(Url::get('type')=='EXPORT' and Url::check('move_product'))
                    {
                        
                        $bill_number = $this->get_new_bill_number('IMPORT');
                        $code_wh = DB::fetch('SELECT WAREHOUSE.code as code
                                      FROM WAREHOUSE 
                                      where WAREHOUSE.id = '.Url::get('to_warehouse_id').' and WAREHOUSE.portal_id = \''.PORTAL_ID.'\'
                                      ','code');
                        $array['along_receipt'] = str_replace(array('PN-'.$code_wh),'',$bill_number);
                    }
                    $array['bill_number'] = $this->get_new_bill_number(Url::sget('type'));
                    
					$id = DB::insert('wh_invoice',$array+array('user_id'=>Session::get('user_id'),'time'=>time()));
					
                    if(Url::get('purchases_invoice_id'))
                    {
                        DB::update('purchases_invoice',array('warehouse_invoice_id'=>$id),'id='.Url::get('purchases_invoice_id'));
                    }
                    $action = 'Add';
					$title = 'Add warehouse invoice '.$id.'';
					
                    if(Url::get('type')=='EXPORT')
					{
						if(Url::check('move_product'))
                        {
							$array['type'] = 'IMPORT';
							//-------------------Tao Phieu nhap kho-----------------------
							$array['bill_number'] = $bill_number;
                            $array['along_receipt'] = $id;
                            $array['warehouse_id'] = Url::get('to_warehouse_id');
							$description .= '<br><hr><br>';
							$description .= '<strong>Export with import:</strong><br>';
							$description .= 'Bill number: '.$bill_number.'';
							$import_id = DB::insert('wh_invoice',$array+array('user_id'=>Session::get('user_id'),'time'=>time()));
                            DB::update('wh_invoice',array('along_receipt'=>$import_id),'id='.$id);
						}
					}
					if(Url::get('type')=='IMPORT')
					{
						if(Url::check('direct_export'))
                        {
                            $array['invoice_number']='';
                            //------------------------ Tao Phieu Xuat Kho-------------------------------------
							$array['type'] = 'EXPORT';
							$bill_number = $this->get_new_bill_number('EXPORT');
							$array['bill_number'] = $bill_number;
							$array['warehouse_id'] = Url::get('warehouse_id');
							$description .= '<br><hr><br>';
							$description .= '<strong>Direct Export:</strong><br>';
							$description .= 'Bill number: '.$bill_number.'';
							$export_id = DB::insert('wh_invoice',$array+array('user_id'=>Session::get('user_id'),'time'=>time()));
							//-------------------------------------------------------------
							//--------------------------Tao Phieu nhap kho-----------------------------------
							$array['type'] = 'IMPORT';
                            //start: KID thay (1)->(2) loai bo truong hop ra 2 phieu nhap cung kho khi nhap - xuat thang
							//$bill_number = $this->get_new_bill_number('IMPORT');(1)
                            $bill_number = $this->get_new_bill_number_direct_export();//(2)
                            //end: KID thay (1)->(2) loai bo truong hop ra 2 phieu nhap cung kho khi nhap - xuat thang
							$array['bill_number'] = $bill_number;
							$array['warehouse_id'] = Url::get('to_warehouse_id');
                            unset($array['supplier_id']);
							$description .= '<br><hr><br>';
							$description .= '<strong>Direct Import:</strong><br>';
							$description .= 'Bill number: '.$bill_number.'';
							$import_id = DB::insert('wh_invoice',$array+array('user_id'=>Session::get('user_id'),'time'=>time()));
							//--------------------------------------------------------
						}
					}
				}
                /**
                 * Ket thuc xu ly bang wh_invoice
                 */
				$invoice_id = $id;
				if(isset($_REQUEST['mi_product']))
				{
                    $term_date = DB::fetch('select * from wh_remain_date where status = 1 and warehouse_id='.Url::iget('warehouse_id').' and portal_id = \''.PORTAL_ID.'\'');
                    foreach($_REQUEST['mi_product'] as $id=>$product)
                    {
                        $product['product_id'] = strtoupper($product['product_id']);
                        
                    	if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$product['product_id'].'\' and warehouse_id='.Url::iget('warehouse_id').' and portal_id = \''.PORTAL_ID.'\' '))
                    	{
                    		DB::insert('wh_start_term_remain',array('product_id'=>$product['product_id'],'warehouse_id'=>Url::iget('warehouse_id'),'quantity'=>0,'term_date'=>$term_date['term_date'],'wh_remain_date_id'=>$term_date['id'],'portal_id'=>PORTAL_ID));
                    	}
                        
                        if(Url::check('move_product') or Url::check('direct_export'))
                        {
                            if(Url::get('to_warehouse_id'))
                        	{
                        		if(!DB::select('wh_start_term_remain','upper(product_id)=\''.$product['product_id'].'\' and warehouse_id='.Url::iget('to_warehouse_id').' and portal_id = \''.PORTAL_ID.'\''))
                        		{
                        		    $term_date_to = DB::fetch('select * from wh_remain_date where status = 1 and warehouse_id='.Url::iget('to_warehouse_id').' and portal_id = \''.PORTAL_ID.'\'');  
                                	DB::insert('wh_start_term_remain',array('product_id'=>$product['product_id'],'warehouse_id'=>Url::iget('to_warehouse_id'),'quantity'=>0,'term_date'=>$term_date_to['term_date'],'wh_remain_date_id'=>$term_date_to['id'],'portal_id'=>PORTAL_ID));
                        		}
                        	} 
                        }
                    }
                    
    				$description .= '<hr>';
                    $warehouse_id = Url::get('warehouse_id');
                    $total_amount_import_id = 0;
                    $total_amount_export_id = 0;
					foreach($_REQUEST['mi_product'] as $key=>$record)
					{
                        $record['warehouse_id'] = $warehouse_id;
                        $record['product_id'] = strtoupper($record['product_id']);
						if(!DB::exists('select * from product where upper(id) = \''.$record['product_id'].'\''))
                        {
                            DB::insert('product',array('name_1'=>$record['name'],'name_2'=>$record['name'],'id'=>$record['product_id'],'category_id'=>$record['category_id'],'unit_id'=>$record['unit_id'],'type'=>$record['type'],'status'=>'avaiable'));
                        }
                        if(isset($record['price']))
                        {
                            $record['price'] = str_replace(',','',$record['price']);
                        }
                        else
                        {
                            $record['price'] = str_replace(',','',$record['old_price']);
                        }
                        $old_price_for_tmp = System::calculate_number($record['old_price']);
						$record['num']=str_replace(',','',$record['num']);
                        $remain_num = System::calculate_number($record['remain_num']);
                        $old_average_price = str_replace(',','',$record['old_price']);
						$product_name = $record['name'];
                        unset($record['name']);
                        $record['payment_price'] = System::calculate_number($record['payment_price']);
                        //==so tien ton o day===//
                        $remain_money = System::calculate_number($record['remain_money']);
                        $record['lastest_imported_price'] = (isset($record['lastest_imported_price']))?System::calculate_number($record['lastest_imported_price']):'';
                       //unset($record['payment_price']);
						unset($record['unit']);
                        unset($record['unit_id']);
                        unset($record['units_id']);
                        unset($record['category']);
                        unset($record['category_id']);
                        unset($record['categorys_id']);
                        unset($record['type']);
                        unset($record['types_id']);
                        unset($record['remain_num']);
                        //==unset===//
                        unset($record['remain_money']);
                        unset($record['old_price']);
                        unset($record['remain_id']);
                        unset($record['isset_money']);
						$empty = true;
						foreach($record as $record_value)
						{
							if($record_value)
							{
								$empty = false;
							}
						}
						if(!$empty)
						{
							$record['invoice_id'] = $invoice_id;
                            if($record['id'])
							{
								$id = $record['id'];
								unset($record['id']);
								$description .= 'Edit [Product id: '.$record['product_id'].',Product name: '.$product_name.', Price: '.(isset($record['price'])?$record['price']:0).', Quantity: '.$record['num'].', Add money: '.$record['money_add'].']<br>';
                                if(isset($isMoveProduct) and $isMoveProduct ==true)
                                {
                                    $record['to_warehouse_id'] = Url::iget('to_warehouse_id');
                                }
                                DB::update('wh_invoice_detail', $record, 'id=\''.$id.'\'');
                                unset($record['to_warehouse_id']);
                                unset($record['isset_money']);
							}
							else//them moi
							{
                                if(Url::check('move_product') or Url::check('direct_export'))
                                    $record['to_warehouse_id'] = Url::get('to_warehouse_id');
								if(DB::exists('SELECT id FROM product WHERE upper(id)=\''.$record['product_id'].'\''))
                                {
									if(isset($record['id']))
                                    {
										unset($record['id']);
									}
									$description .= 'Add [Product id: '.$record['product_id'].',Product name: '.$product_name.', Price: '.(isset($record['price'])?$record['price']:0).', Quantity: '.$record['num'].', Add money: '.$record['money_add'].']<br>';
                                    if(Url::get('type')=='IMPORT')
                                    {
                                        $export_quantity = isset($export_num[$record['product_id']]['export_quantity'])?$export_num[$record['product_id']]['export_quantity']:0;
                                        if(Url::get('edit_average_price'))
                                        {
                                            $record['average_price'] = round( 
                                                                        ($remain_num  * $old_average_price + $record['money_add'])
                                                                        /($remain_num) ,6);
                                            $record['for_edit_average_price'] = 1;
                                            $record['price'] = $record['lastest_imported_price'];
                                            $record['num'] = 0;
                                            DB::update('wh_invoice',array('for_edit_average_price'=>1),'id = '.$invoice_id);
                                        }
                                        else
                                        {
                                            
                                            $cond_tmp = 'warehouse_id = '. $warehouse_id . ' and product_id = \''. $record['product_id']. '\'';
                                            $average_sql = 'select 
                                                            * 
                                                            from 
                                                                wh_tmp 
                                                            where '. $cond_tmp;
                                            $isset_tmp_export = DB::fetch($average_sql);
                                            if (!empty($isset_tmp_export))/** update lai ban ghi tmp **/
                                            {
                                                $record['average_price'] = round( 
                                                                        ($isset_tmp_export['total'] + $record['payment_price'])
                                                                        /($isset_tmp_export['quantity'] + $record['num']) ,6);
                                                $this->update_export_tmp($warehouse_id,$record['product_id'],$remain_num,$record['num'],$record['average_price']);
                                            }
                                            else
                                            {
                                                $record['average_price'] = round( 
                                                                        ($remain_num * $old_average_price + $record['price'] * $record['num'])
                                                                        /($remain_num + $record['num'])  ,6);
                                                //echo  $remain_num.'__'.$old_average_price.'__'.$record['price'].'__'.$record['num'].'=='.$record['average_price'];exit();                          
                                            }
                                        }
                                        $record['time_calculation'] = time();
                                        $new_average_price = $record['average_price'];
                                    }
                                    if (Url::get('type') == 'EXPORT' and $record['num'] > $remain_num)
                                    {
                                        $record['tmp'] = 1;
                                        $tmp_data = array(
                                                            'warehouse_id' => $warehouse_id,
                                                            'product_id' => $record['product_id'],
                                                            'average_price' => $old_price_for_tmp,
                                                            'quantity' => $remain_num,
                                                            'total' => $remain_num * $old_price_for_tmp,
                                                            'time' => time()
                                                            );
                                        $cond_tmp = 'warehouse_id = '. $warehouse_id . ' and product_id = \''. $record['product_id']. '\'';
                                        $average_sql = 'select 
                                                        * 
                                                        from 
                                                            wh_tmp 
                                                        where '. $cond_tmp;
                                        $isset_tmp_export = DB::fetch($average_sql);
                                        if (empty($isset_tmp_export))
                                        {
                                            DB::insert('wh_tmp', $tmp_data);
                                        }
                                        DB::update('wh_invoice', array('tmp' => 1), ' id = '. $record['invoice_id']);
                                        unset($tmp_data);
                                    }
                                    /**
                                     * BEGIN
                                     * Check so luowng xuat = so luong ton thi gia xuat = so tien ton/ so ton 
                                    **/
                                    if (Url::get('type') == 'EXPORT' and abs($record['num'] - $remain_num) <= 0.001 and $remain_num > 0)
                                    {
                                        $record['price'] = $remain_money/$remain_num;
                                        $record['payment_price'] = $remain_money;   
                                    }
                                    /**
                                     * END 
                                    **/
                                    DB::insert('wh_invoice_detail',$record);
                                    unset($record['average_price']);
                                    unset($record['time_calculation']);
                                    unset($record['isset_money']);
                                    if(isset($export_id) && $export_id!=0)
                                    {
                                        $record['price'] = $new_average_price; 
                                        $total_amount_export_id += round($record['price']*$record['num']); 
                                        $record['payment_price'] = round($record['price']*$record['num']);
										$record['invoice_id'] = $export_id;
                                        $record['warehouse_id'] = Url::get('warehouse_id');
                                        $record['to_warehouse_id'] = Url::get('to_warehouse_id');
                                        unset($record['isset_money']);
										DB::insert('wh_invoice_detail',$record);
									}
                                    if(isset($import_id) && $import_id!=0)
                                    {
                                        $record['price'] = isset($new_average_price)?$new_average_price:$record['price'];
                                        $total_amount_import_id += round($record['price']*$record['num']);
                                        $remain_num_to_wh = isset($product_remain_to_wh[$record['product_id']]['remain_number'])?$product_remain_to_wh[$record['product_id']]['remain_number']:0;
                                        $record['payment_price'] = round($record['price']*$record['num']);
                                        $old_average_price_to_wh = isset($product_remain_to_wh[$record['product_id']]['price'])?$product_remain_to_wh[$record['product_id']]['price']:0;
                                        $record['time_calculation'] = time();
                                        $record['invoice_id'] = $import_id;
                                        $record['warehouse_id'] = Url::get('to_warehouse_id');
                                        unset($record['to_warehouse_id']);
                                        unset($record['isset_money']);
										/** Kimtan them doan nay khi chuyen hang giua cac kho. kho duoc nhap se update lai phieu xuat tam neu du dk **/
                                        $cond_tmp = 'warehouse_id = '. Url::get('to_warehouse_id') . ' and product_id = \''. $record['product_id']. '\'';
                                        $average_sql = 'select 
                                                        * 
                                                        from 
                                                            wh_tmp 
                                                        where '. $cond_tmp;
                                        $isset_tmp_export = DB::fetch($average_sql);
                                        if (!empty($isset_tmp_export))/** update lai ban ghi tmp **/
                                        {
                                            $record['average_price'] = round( 
                                                                    ($isset_tmp_export['total'] + $record['payment_price'])
                                                                    /($isset_tmp_export['quantity'] + $record['num']) ,6);
                                        }
                                        else
                                        {
                                            $record['average_price'] = round(   ($remain_num_to_wh * $old_average_price_to_wh + $record['price'] * $record['num'])/($remain_num_to_wh + $record['num']), 4   );                          
                                        }
                                        DB::insert('wh_invoice_detail',$record);
                                        //echo $remain_num_to_wh.'*'.$old_average_price_to_wh.'+'.$record['price'].'*'.$record['num'].'/'.$remain_num_to_wh .'+'. $record['num'];
                                        //echo $record['average_price'];exit();
                                        $this->update_export_tmp(Url::get('to_warehouse_id'),$record['product_id'],$remain_num_to_wh,$record['num'],$record['average_price']);
                                        /** end Kimtan them doan nay **/
									}
								}
							}
						}
                    }
                    if(isset($export_id) && $export_id!=0)
                    {
                        DB::update('wh_invoice', array('total_amount' => round($total_amount_export_id)), ' id = '. $export_id);
                    }
                    if(isset($import_id) && $import_id!=0)
                    {
                        DB::update('wh_invoice', array('total_amount' => round($total_amount_import_id)), ' id = '. $import_id);
                    }
				}
                /**
                 * Xoa cac san pham chuyen xuong day
                **/
                if(URl::get('group_deleted_ids'))
    			{
    				$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
    				$description .= '<hr>';
                    $invoice_id = '';
                    foreach($group_deleted_ids as $delete_id)
    				{
                        if(!$invoice_id)
                        {
                            $invoice_id = DB::fetch('Select * from wh_invoice_detail where id = '.$delete_id,'invoice_id');
                        }
    					$description .= 'Delete product id: '.$delete_id.'<br>';
                        // check xem co can xoa cac ban ghi trong wh_tmp
                        $wh_detail = DB::fetch('select * from wh_invoice_detail where id = '. $delete_id);
                        if (Url::get('type') == 'EXPORT')
                        {
                            if ($product_remain[$wh_detail['product_id']]['remain_number'] < 0 and ($product_remain[$wh_detail['product_id']]['remain_number'] + $wh_detail['num']) >= 0)
                            {
                                $cond_tmp = 'warehouse_id = '. Url::get('warehouse_id') . ' and product_id = \''. $wh_detail['product_id']. '\'';
                                // update cac gia xuat ra cho cac PX am
                                $price = isset($product_remain[$wh_detail['product_id']]['price'])? $product_remain[$wh_detail['product_id']]['price']:$product_remain[$wh_detail['product_id']]['start_term_price'];
                                DB::update('wh_invoice_detail', array('tmp' => '', 'price' => $price), $cond_tmp .' and tmp = 1');
                                // xoa ban ghi trong wh_tmp vi product_id trong warehouse_id da het am
                                DB::delete('wh_tmp', $cond_tmp);   
                            }
                        }
                        elseif (Url::get('type') == 'import')
                        {
                            if ($product_remain[$wh_detail['product_id']]['remain_number'] < 0)
                            {
                                $cond_tmp = 'warehouse_id = '. Url::get('warehouse_id') . ' and product_id = \''. $wh_detail['product_id']. '\'';
                                $tmp_items = DB::fetch('select * from wh_tmp where '.$cond_tmp);
                                $tmp_data['quantity'] = $temp_items['quantity'] - $wh_detail['num'];
                                $tmp_data['total'] = $temp_items['total'] - $wh_detail['payment_price'];
                                $tmp_data['average_price'] = $tmp_data['total'] / $tmp_data['quantity'];
                                DB::update('wh_tmp', $tmp_data, 'id = '.$temp_items['id']);
                            }
                        }
    					DB::delete_id('wh_invoice_detail',$delete_id);
    				}
                    if(!DB::exists('Select * from wh_invoice_detail where invoice_id ='.$invoice_id))
                    {
                        $description1 = '';
                        $code = DB::fetch('select bill_number from wh_invoice where id='.$invoice_id,'bill_number');
                        $title = 'Delete warehouse invoice '.$code.'';
                        $description1.= 'Delete warehouse invoice '.$code.'';
                        System::log($action,$title,$description1,$invoice_id);
                        DB::delete_id('wh_invoice',$invoice_id);
                    }
                        
    			}
                /**
                 * END
                 * xoa sp 
                **/
            }
            else
            {
				$this->error('bill_nubmer','bill_number_is_duplicated');
				return false;
			}
			if($error==false)
            {
				System::log($action,$title,$description,$invoice_id);
                if(Url::get('cmd') == 'edit' and Url::get('id'))
                {
                    $tems_check = DB::fetch('select * from wh_invoice where id = '.Url::get('id'));
                    if($tems_check and $tems_check['along_receipt'] != '')
                    {
                        $tems_check_macth = DB::fetch('select * from wh_invoice where id = '.$tems_check['along_receipt']); 
                        $import_url = URL::build('warehouse_invoice',array('cmd'=>'edit','id'=>$tems_check['along_receipt'],'type'=>'IMPORT','warehouse_id'=>$tems_check_macth['warehouse_id']));
                        echo '<script>
                                alert("Bạn đã sửa PX: '.$tems_check['bill_number'].' yêu cầu cập nhật PN: '.$tems_check_macth['bill_number'].'");
                                //window.open(\''.$import_url.'\',\'_blank\');
                                var new_a = document.createElement("a");
                                new_a.setAttribute("id","edit_import_link");
                                new_a.setAttribute("target","blank");
                                new_a.setAttribute("href",\''.$import_url.'\');
                                //new_a.innerHTML = \''.$import_url.'\';
                                ni = document.getElementsByTagName("html")[0];
                                ni.appendChild(new_a);
                                document.getElementById(\'edit_import_link\').click();
                                //jQuery("#edit_import_link").click();
                                //win.focus();  
                              </script>';
                    }
                    else 
                        Url::redirect_current(array('type','just_edited_id'=>$invoice_id));
                }
                if(Url::get('cmd') == 'add' && Url::get('save_type') == 'save')
			    {
			        Url::redirect_current(array('type','just_edited_id'=>$invoice_id));
			    }
                if(Url::get('cmd') == 'add' && Url::get('save_type') == 'save_and_import')
                {
                    Url::redirect_current(array('type','cmd'=>'add','warehouse_invoice','move_product','warehouse_id','purchases_invoice_id'));
                }
              }
		}
	}
    function get_new_bill_number($type)
    {
        $wh_id = Url::get('warehouse_id');
        if(Url::get('type')=='EXPORT' and Url::check('move_product') and $type == 'IMPORT')
            $wh_id = Url::get('to_warehouse_id');
        if($type == 'IMPORT')
            $prefix = 'PN';
        else
            $prefix = 'PX';
            
        $code_wh = DB::fetch('SELECT WAREHOUSE.code as code
                                      FROM WAREHOUSE 
                                      where WAREHOUSE.id = '.$wh_id.' and WAREHOUSE.portal_id = \''.PORTAL_ID.'\'
                                      ','code');
        
        $max_bill =  DB::fetch("SELECT max(TO_NUMBER(REPLACE(REPLACE(REPLACE(bill_number,'".$prefix."-".$code_wh."',''),'PN-REPT',''),'PN-KSP',''))) as bill
                                      FROM wh_invoice 
                                      where 
                                        wh_invoice.type='".$type."' and wh_invoice.WAREHOUSE_ID = ".$wh_id." and wh_invoice.portal_id = '".PORTAL_ID."'
                                      ",'bill');
        
        if(!$max_bill)
            $max_bill = 0;
        $bill = $prefix."-".$code_wh.($max_bill + 1);
        return $bill;
    }
    //start: KID them ham nay loai bo truong hop ra 2 phieu nhap cung kho khi nhap - xuat thang
    function get_new_bill_number_direct_export()
    { 
        $wh_id_dr = Url::get('to_warehouse_id');
        $prefix_dr = 'PN';
        $code_wh_dr = DB::fetch('SELECT WAREHOUSE.code as code
                                      FROM WAREHOUSE 
                                      where WAREHOUSE.id = '.$wh_id_dr.' and WAREHOUSE.portal_id = \''.PORTAL_ID.'\'
                                      ','code');
        $max_bill_dr =  DB::fetch("SELECT max(TO_NUMBER(REPLACE(REPLACE(REPLACE(bill_number,'".$prefix_dr."-".$code_wh_dr."',''),'PN-REPT',''),'PN-KSP',''))) as bill
                                      FROM wh_invoice 
                                      where 
                                        wh_invoice.type='IMPORT' and wh_invoice.WAREHOUSE_ID = ".$wh_id_dr." and wh_invoice.portal_id = '".PORTAL_ID."'
                                      ",'bill');
        if(!$max_bill_dr)
            $max_bill_dr = 0;
        $bill_dr = $prefix_dr."-".$code_wh_dr.($max_bill_dr + 1);
        return $bill_dr;
    }
    
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        $this->map = array();
		$sql = 'select 
                    id,
                    name
                from 
                    wh_receiver
                where portal_id = \''.PORTAL_ID.'\'
                ';
		$receiver_list = DB::fetch_all($sql);
        $this->map['wh_receiver_name_list'] = array(''=>Portal::language('select_receiver')) + String::get_list($receiver_list);
        $warehouses = get_warehouse(true);
        $this->map['warehouse_id_list'] = String::get_list($warehouses);
        $to_warehouse_id_list = $this->map['warehouse_id_list'];
        if(Url::get('warehouse_id'))
        {
            unset($to_warehouse_id_list[Url::get('warehouse_id')]);
        }
        $this->map['to_warehouse_id_list'] = $to_warehouse_id_list;
        $this->map['supplier_id_list'] = String::get_list(DB::fetch_all('SELECT
                                                                            id,
                                                                            CONCAT(code,CONCAT(\' - \',name)) AS name
                                                                        FROM
                                                                            supplier
                                                                        ORDER BY
                                                                            code'));
        $this->map['suppliers'] = DB::fetch_all('
			SELECT
				supplier.code as id,
                supplier.id as supplier_id,
                supplier.name,
                supplier.address,
                supplier.tax_code
			FROM
				supplier
			ORDER BY
				supplier.code
		');
        $types = WarehouseInvoiceDB::get_types();
        $this->map['types_id'] = $types;
        $units = WarehouseInvoiceDB::get_units();
        $this->map['units_id'] = $units;
        $categorys = WarehouseInvoiceDB::get_category();
        $this->map['categorys_id'] = $categorys;
        //Lay cac dau muc san pham va tinh toan so luong ton kho, gia cu neu co
		$all_products = DB::fetch_all('select id from product where type=\'PRODUCT\' OR type=\'SERVICE\' OR type=\'DRINK\'');
		$this->map['all_products'] = $all_products;
        $this->map['title'] = (Url::get('cmd')=='add')?((Url::get('type')=='IMPORT')?Portal::language('add_import_bill'):Portal::language('add_export_bill')):((Url::get('type')=='IMPORT')?Portal::language('edit_import_bill'):Portal::language('edit_export_bill'));
        /** manh comment cho nay vi ben kinh doanh khong muon de mac dinh nguoi tao **/
        //$_REQUEST['deliver_name'] = Session::get('user_id');
        /** end manh **/
		$item = WarehouseInvoice::$item;
		if($item)
        {
			$item['create_date'] = str_replace('-','/',Date_Time::convert_orc_date_to_date($item['create_date']));

			foreach($item as $key=>$value)
            {
				if(!isset($_REQUEST[$key]))
                {
					$_REQUEST[$key] = $value;
				}
			}
			if(!isset($_REQUEST['mi_product']))
			{
                $sql = '
					SELECT
						wh_invoice_detail.*,
						--(CASE
                            --when wh_invoice_detail.payment_price != 0 then wh_invoice_detail.payment_price
                            --else wh_invoice_detail.num * wh_invoice_detail.price
                         --END
                        --) as payment_price,
                        wh_invoice_detail.num * wh_invoice_detail.price as payment_price,
						product.name_'.Portal::language().' as name,
						unit.name_'.Portal::language().' as unit,
                        product_category.name as category,
                        product.type,
                        supplier.id as supplier_id,
                        supplier.code as supplier_code,
                        supplier.name as supplier_name,
                        supplier.tax_code as tax_code,
                        supplier.address as address,
                        wh_invoice.warehouse_id,
                        --wh_invoice.deliver_name,
                        wh_invoice.receiver_name,
                        unit.id as unit_id,
                        product_category.id as category_id,
                        product.id as product_id
					FROM
    					wh_invoice_detail
                        inner join wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
                        left outer join supplier on wh_invoice.supplier_id = supplier.id
                        inner join product on wh_invoice_detail.product_id = product.id
                        inner join unit on product.unit_id = unit.id
                        inner join product_category on product.category_id = product_category.id
					WHERE
						wh_invoice_detail.invoice_id=\''.$item['id'].'\'
                        and wh_invoice.portal_id = \''.PORTAL_ID.'\'
				';
				$mi_product = DB::fetch_all($sql);
                $product_remain = get_remain_products(Url::get('warehouse_id'),false,false,$mi_product,false);
                foreach($mi_product as $id=>$product)
        		{
        			if(isset($product_remain[$product['product_id']]) and $product['product_id']==$product_remain[$product['product_id']]['id'])
        			{
        				/**
                         * BEGIN edit
                         * Lấy ra số lượng tồn 
                         * số tiền tồn 
                        **/
        				$products[$product['product_id']]['remain_num'] = $product_remain[$product['product_id']]['remain_number'];
                        $products[$product['product_id']]['remain_money'] = $product_remain[$product['product_id']]['remain_money'];
                        /**
                         * END edit
                         * Lấy ra số lượng tồn 
                         * số tiền tồn 
                        **/
                        //Ton tai gia (tuc la da co phieu nhap, tinh dc gia TB roi`)
                        if(isset($product_remain[$product['product_id']]['price']))
        				{
        					$products[$product['product_id']]['old_price'] = $product_remain[$product['product_id']]['price'];
        				}
        				else
        				{
        				    //neu khong thi lay gia la gia ton dau ki
            				if(isset($product_remain[$product['product_id']]['start_term_price']))
            				    $products[$product['product_id']]['old_price'] = $product_remain[$product['product_id']]['start_term_price'];
                            else
                                $products[$product['product_id']]['old_price'] = 0;
        				}
                        //$products[$id]['lastest_imported_price'] = 0; 
                        if(Url::get('type') == 'IMPORT')
                        {
                            $products[$product['product_id']]['lastest_imported_price'] = 0;  
                            if(isset($product_remain[$product['product_id']]['lastest_imported_price']) and $product_remain[$product['product_id']]['lastest_imported_price'] > 0)
            				    $products[$product['product_id']]['lastest_imported_price'] = $product_remain[$product['product_id']]['lastest_imported_price'];
                            else
                                $products[$product['product_id']]['lastest_imported_price'] = 0;  
                        }
        			}
        			else
        			{
        				$products[$product['product_id']]['remain_num'] = 0;
                        //them o day nua nha
                        $products[$product['product_id']]['remain_money'] = 0;
        				$products[$product['product_id']]['old_price'] = 0;
                        $products[$product['product_id']]['lastest_imported_price'] = 0;  
        			}
        		}
                //System::debug($products);
                
				foreach($mi_product as $k=>$v)
                {
					$mi_product[$k]['price'] = System::display_number($v['price']);
					if($v['num'] >= 1)
                    {
                        $mi_product[$k]['num'] = System::display_number($v['num']);
                    }
                    else
                    {
                        $mi_product[$k]['num'] = '0'.$v['num'];
                    }
                    $mi_product[$k]['number'] = $v['num'];
					$mi_product[$k]['payment_price'] = System::display_number($v['payment_price']);
                    $mi_product[$k]['old_price'] = isset($products[$mi_product[$k]['product_id']]['old_price'])?System::display_number($products[$mi_product[$k]['product_id']]['old_price']):0;
                    $mi_product[$k]['lastest_imported_price'] = isset($products[$mi_product[$k]['product_id']]['lastest_imported_price'])?System::display_number($products[$mi_product[$k]['product_id']]['lastest_imported_price']):0;
                    if(Url::get('move_product'))
                        $mi_product[$k]['old_price'] = $mi_product[$k]['old_price']?$mi_product[$k]['old_price']:$mi_product[$k]['price'];
                    
                    $mi_product[$k]['remain_num'] = isset($products[$mi_product[$k]['product_id']]['remain_num'])?System::display_number($products[$mi_product[$k]['product_id']]['remain_num']):0;
                    //$_REQUEST['supplier_id'] = $mi_product[$k]['supplier_id']?$mi_product[$k]['supplier_id']:'';
                    //$_REQUEST['supplier_id'] = DB::fetch('Select code from supplier where code = \''.$_REQUEST['supplier_id'].'\' ','code');
                    $_REQUEST['supplier_id'] = $mi_product[$k]['supplier_id'];
                    $_REQUEST['supplier_code'] = $mi_product[$k]['supplier_code'];
                    $_REQUEST['supplier_name'] = $mi_product[$k]['supplier_name'];
                    $_REQUEST['ware_supplier_name'] = $mi_product[$k]['supplier_name'];
                    $_REQUEST['tax_code'] = $mi_product[$k]['tax_code'];
                    $_REQUEST['address'] = $mi_product[$k]['address'];
                    $_REQUEST['warehouse_id'] = $mi_product[$k]['warehouse_id'];
                    //$_REQUEST['deliver_name'] = $mi_product[$k]['deliver_name'];
                    $_REQUEST['receiver_name'] = $mi_product[$k]['receiver_name'];
                    if(isset($mi_product[$k]['to_warehouse_id'])) $_REQUEST['to_warehouse_id'] = $mi_product[$k]['to_warehouse_id'];
				}
                if (User::is_admin())
                {
                    //System::debug($mi_product);
                }
				$_REQUEST['mi_product'] = $mi_product;
                $_REQUEST['total'] = System::display_number(System::calculate_number($_REQUEST['total_amount'])+$_REQUEST['tax']);
                $_REQUEST['tax'] = System::display_number($_REQUEST['tax']);
                $_REQUEST['total_amount'] = System::display_number($_REQUEST['total_amount']);
                /*$_REQUEST['total'] = System::display_number(System::calculate_number($_REQUEST['total_amount']));
                $_REQUEST['total_amount'] = System::display_number($_REQUEST['total_amount'] - $_REQUEST['tax']);
                $_REQUEST['tax'] = System::display_number($_REQUEST['tax']);*/
			}
		}
        else
        {
			if(!Url::get('create_date'))
            {
				$_REQUEST['create_date'] = date('d/m/Y',time());
			}
            if(!Url::get('bill_number'))
            {
                $_REQUEST['bill_number'] = $this->get_new_bill_number(Url::get('type'));
			}
            if(Url::get('purchases_invoice_id'))
            {
                $purchases = DB::fetch_all("SELECT
                                                purchases_invoice_detail.id,
                                                purchases_detail.product_code as product_id,
                                                concat(concat(purchases_detail.note,' '),purchases_invoice_detail.note) as note,
                                                purchases_invoice_detail.quantity as num,
                                                purchases_invoice_detail.price,
                                                purchases_invoice_detail.price as lastest_imported_price,
                                                0 as for_edit_average_price,
                                                '' as money_add,
                                                '' as tmp,
                                                (purchases_invoice_detail.price*purchases_invoice_detail.quantity) as payment_price,
                                                purchases_invoice_detail.price as average_price,
                                                product.name_".Portal::language()." as name,
                                                unit.name_".Portal::language()." as unit,
                                                product_category.name as category,
                                                product.type as type,
                                                purchases_invoice_detail.supplier_code as supplier_id,
                                                supplier.id as supplier_id,
                                                supplier.code as supplier_code,
                                                supplier.name as supplier_name,
                                                unit.id as unit_id,
                                                product_category.id as category_id
                                            FROM
                                                purchases_invoice_detail
                                                inner join purchases_detail on purchases_detail.id=purchases_invoice_detail.purchases_detail_id
                                                inner join product on product.id=purchases_detail.product_code
                                                inner join product_category on product_category.id=product.category_id
                                                inner join unit on product.unit_id=unit.id
                                                left join supplier on supplier.code = purchases_invoice_detail.supplier_code
                                            WHERE
                                                purchases_invoice_detail.purchases_invoice_id=".Url::get('purchases_invoice_id')."
                                            ");
                $_REQUEST['total_amount'] = 0;
                foreach($purchases as $num=>$recode)
                {
                    $_REQUEST['note'] = $recode['note']." Nhập từ đơn hàng được đề xuất ";
                    $_REQUEST['supplier_id'] = $recode['supplier_id'];
                    $_REQUEST['supplier_code'] = $recode['supplier_code'];
                    $_REQUEST['supplier_name'] = $recode['supplier_name'];
                    $_REQUEST['total_amount'] += $recode['payment_price'];
                    $purchases[$num]['id'] = '';
                    $purchases[$num]['invoice_id'] = '';
                    $purchases[$num]['price'] = System::display_number($recode['price']);
                    $purchases[$num]['warehouse_id'] = '';
                    $purchases[$num]['to_warehouse_id'] = '';
                    $purchases[$num]['payment_price'] = System::display_number($recode['payment_price']);
                    $purchases[$num]['number'] = $recode['num'];
                    $purchases[$num]['old_price'] = System::display_number($recode['price']);
                    $purchases[$num]['remain_num'] = $recode['num'];
                }
                $_REQUEST['mi_product'] = $purchases;
            }
            
		}
        if(Url::get('cmd') == 'edit')
        {
            /** Kimtan cmt doan code nay
             * y nghia cua doan code: neu trong phieu xuat ton tai ghi chu. chu dau tien trong ghi chu là "Phieu xuat" thi ko cho sua phieu xuat
             * Ly do cmt: ben ks khong can va cung ko ro chuc nang nay phuc vu cai gi
            if(Url::get('note'))
            {
                $note = Url::get('note');
                $auto_note = Portal::language('export').' '.Portal::language('warehouse').' ';
                $check = strpos($note,$auto_note);
                if(($check === 0))
                {
                    if(User::is_admin(false,ANY_CATEGORY))
                        $this->map['auto_check'] = 1;
                    else 
                    {
                        $this->map['auto_check'] = 0;
                        echo 'You can not edit this receipt!!!!';
                    }
                }
                else 
                    $this->map['auto_check'] = 1;
            }
            else 
                $this->map['auto_check'] = 1;
            */
            $this->map['auto_check'] = 1;
        }
        if(Url::get('warehouse_id'))
            $_REQUEST['warehouse_name'] = DB::fetch('Select name from warehouse where id = '.Url::iget('warehouse_id'),'name');
        $this->map['index'] = 0;
         if(!isset($_REQUEST['deliver_name']) and Url::get('type')=='EXPORT'){
            $user_data = Session::get('user_data');
            $_REQUEST['deliver_name'] = $user_data['full_name']; 
        } 
        if(!isset($_REQUEST['receiver_name']) and Url::get('type')=='IMPORT'){
            $user_data = Session::get('user_data');
            $_REQUEST['receiver_name'] = $user_data['full_name']; 
        }
       
		$this->parse_layout('edit',$this->map);
	}
    /** Kimtan: them ham de update lai cac phieu xuat tam **/
    function update_export_tmp($wh_id,$product_id,$remain_num,$num,$average_price)
    {
        $cond_tmp = 'warehouse_id = '. $wh_id . ' and product_id = \''. $product_id. '\'';
        $temp_sql = 'select * from wh_invoice_detail where tmp = 1 and '.$cond_tmp.' order by id';
        $tmp_export = DB::fetch_all($temp_sql);
        //System::debug($tmp_export);
        if (!empty($tmp_export))
        {
            $num_tmp_export = DB::fetch('select sum(num) as total_num from wh_invoice_detail where tmp = 1 and '.$cond_tmp,'total_num');
            $check_num = $remain_num+$num_tmp_export+$num;
            $check_wh = false;
            $total_arr = array();
            //echo $check_num.'='.$remain_num.'+'.$num_tmp_export.'+'.$num;
            //System::debug($tmp_export);//exit();
            foreach($tmp_export as $k=>$v)
            {
                if($check_num>=$v['num'])
                {
                    $payment_price = $v['num'] * $average_price;
                    DB::update('wh_invoice_detail', array('tmp' => '', 'price' => $average_price, 'payment_price' => $payment_price), 'id = '.$k);
                    ///echo 1;exit();
                    $check_num -= $v['num'];
                    $check_wh = true;
                    if(!isset($total_arr[$v['invoice_id']]))
                    {
                        $total_arr[$v['invoice_id']]['id'] = $v['invoice_id'];
                        $total_arr[$v['invoice_id']]['total'] = $payment_price;
                    }
                    else
                    {
                        $total_arr[$v['invoice_id']]['total'] += $payment_price;
                    }
                }
                else
                {
                    break;
                }
            }
            if($check_wh)
            {
                foreach($total_arr as $key=>$val)
                {
                    $old_total_invoice = DB::fetch('select total_amount from wh_invoice where id ='.$key,'total_amount');
                    $new_total_invoice = $old_total_invoice+$val['total'];
                    DB::update('wh_invoice', array('total_amount'=>$new_total_invoice),'id ='.$key);
                    if (!DB::fetch_all($temp_sql))
                    {
                        DB::delete('wh_tmp', $cond_tmp);
                    }
                }
            }
        }
    }
    /** end Kimtan: them ham de update lai cac phieu xuat tam **/
    function get_remain_tan($wh_id,$product_id)
    {
        $cond = $invoice_cond = '1=1';
    $warehouse_cond = '';
    $warehouse_cond .= ' and wh_invoice_detail.product_id = \''.$product_id. '\'';
    $cond .= ' and wh_start_term_remain.product_id = \''.$product_id. '\'';
    $cond.=' and wh_start_term_remain.warehouse_id='.$wh_id;
    $warehouse_cond.=' and wh_invoice_detail.warehouse_id='.$wh_id.' ';
    $products = array();
    $sql_invoice = '
        SELECT 
    		wh_invoice_detail.id,
            wh_invoice_detail.to_warehouse_id,
            wh_invoice_detail.num,
            wh_invoice.id as invoice_id,
            (CASE
                WHEN money_add is null and (wh_invoice_detail.payment_price is not null and wh_invoice_detail.payment_price != 0) THEN wh_invoice_detail.payment_price
                WHEN money_add is null and (wh_invoice_detail.payment_price is null or wh_invoice_detail.payment_price = 0) THEN wh_invoice_detail.price * wh_invoice_detail.num
                ELSE wh_invoice_detail.money_add
             END
            ) as payment_price,
            wh_invoice_detail.product_id,
            wh_invoice.type
    	FROM
    		wh_invoice_detail
    		INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
    		INNER JOIN product on product.id = wh_invoice_detail.product_id
            INNER JOIN unit on unit.id = product.unit_id 
            INNER JOIN product_category ON product_category.id = product.category_id
    	WHERE
    		'.$invoice_cond.$warehouse_cond.'
    ';
    $product_invoice = DB::fetch_all($sql_invoice);
    //System::debug($product_invoice);
    $items = $product_invoice;
    $old_items = array();
	if(is_array($items))
	{
		foreach($items as $key=>$value)
        {
			$product_id = $value['product_id'];
			if(isset($old_items[$product_id]))
            {
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == $wh_id)
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) + System::calculate_number($value['payment_price']);
                }
				else
                {
                    if($value['type']=='EXPORT' and $value['to_warehouse_id'] != $wh_id)
                    {
						$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) - System::calculate_number(round($value['num'],8));
                        $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) - System::calculate_number($value['payment_price']);
                    }
                }
			}
            else
            {
                $old_items[$product_id]['id'] = $value['product_id'];
                $old_items[$product_id]['remain_invoice'] = 0;
                $old_items[$product_id]['remain_money'] = 0;
				if($value['type']=='IMPORT' or $value['to_warehouse_id'] == $wh_id)
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) + System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) + System::calculate_number($value['payment_price']);
                }
                //PX ma kho xuat den khong phai la kho can tinh(tranh th tao PX ma tu kho A den kho A)
				if($value['type']=='EXPORT' and $value['to_warehouse_id'] != $wh_id)
                {
					$old_items[$product_id]['remain_invoice'] =  System::calculate_number(round($old_items[$product_id]['remain_invoice'], 8)) - System::calculate_number(round($value['num'],8));
                    $old_items[$product_id]['remain_money'] =  System::calculate_number($old_items[$product_id]['remain_money']) - System::calculate_number($value['payment_price']);
                }
			}
		}
	}
    $product_invoice = $old_items;
    
    /**
     * END 
    **/
    
    /**
     * BEGIN
     * doan nay tra ve so ton tien va luowng
     * qua so du dau ki
    **/
    //tinh so ton dau ki cua sp
    $sql_start_term_remain = '
        SELECT
            wh_start_term_remain.product_id as id,
            product.name_'.Portal::language().' as product_name,
            unit.name_'.Portal::language().' as unit_name,
            wh_start_term_remain.warehouse_id,
            SUM(
                CASE 
                    WHEN wh_start_term_remain.quantity >0 THEN wh_start_term_remain.quantity
                    ELSE 0 
                END
            ) as remain_number,
            wh_start_term_remain.total_start_term_price as remain_money,
            wh_start_term_remain.start_term_price
        FROM
            wh_start_term_remain
            INNER JOIN product on product.id = wh_start_term_remain.product_id
            INNER JOIN unit on unit.id = product.unit_id
            INNER JOIN product_category ON product_category.id = product.category_id
        WHERE
            '.$cond.'
        GROUP BY
            wh_start_term_remain.product_id,
            wh_start_term_remain.total_start_term_price,
            product.name_'.Portal::language().',
            unit.name_'.Portal::language().',
            wh_start_term_remain.warehouse_id,
            wh_start_term_remain.start_term_price
            
    ';
    $start_term_remain = DB::fetch_all($sql_start_term_remain);
    
    $prices = DB::fetch_all("
                    SELECT dtail.product_id as id,
                        dtail.price,
                        dtail.average_price,
                        dtail.time_calculation
                    FROM wh_invoice_detail dtail
                    where dtail.id in (
                        SELECT
                            max(wh_invoice_detail.id)
                        FROM
                            wh_invoice_detail
                            INNER JOIN wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
                        WHERE
                            ".$invoice_cond." 
                            AND wh_invoice.type='IMPORT'
                            ".$warehouse_cond."
                        GROUP BY wh_invoice_detail.product_id
                    )");
    
        foreach($start_term_remain as $key=>$value)
        {
            if(isset($product_invoice[$key]))
            {
                $price = array('id'=>0,'price'=>0,'average_price'=>0);
                if(isset($prices[$key]))
                {
                    $price = $prices[$key];
                }
                if(Url::get('type')=='IMPORT')
                {
                    $lastest_imported_price = $price['price'];
                }
                else
                {
                    $lastest_imported_price = 0;
                }
    //            else//Xuat thi lay gia TB
    //            {
                    $price = $price['average_price'];
                //}
                
                //So ton thuc su
                $start_term_remain[$key]['remain_number'] = round(($value['remain_number']?$value['remain_number']:0)+($product_invoice[$key]['remain_invoice']?$product_invoice[$key]['remain_invoice']:0),8);
                $start_term_remain[$key]['remain_money'] = round(($value['remain_money']?$value['remain_money']:0)+($product_invoice[$key]['remain_money']?$product_invoice[$key]['remain_money']:0),8);
                $start_term_remain[$key]['lastest_imported_price'] = $lastest_imported_price?$lastest_imported_price:0;
                //===Gia TB se tinh theo cach sau, khong lay o PN gan nhat nua====/
                
                $start_term_remain[$key]['price'] = $start_term_remain[$key]['remain_number'] != 0? $start_term_remain[$key]['remain_money']/$start_term_remain[$key]['remain_number'] : 0;
                
            }
        }
        
        if($_REQUEST['type']=='IMPORT')//phieu nhap => lay moi sp da khai bao
        {
            $products = DB::fetch_all('
    				SELECT
                         product.id,
                         product.id as code,
                         product.name_'.Portal::language().' as name,
                         unit.id as unit_id,
                         unit.name_'.Portal::language().' as unit,
                         product_category.id as category_id,
                         product_category.name as category,
                         product.type
    				FROM
    					product
    					INNER JOIN unit ON unit.id = product.unit_id
                        INNER JOIN product_category ON product_category.id = product.category_id
    				WHERE
    					product.type in (\'GOODS\',\'TOOL\',\'EQUIPMENT\',\'MATERIAL\',\'DRINK\',\'PRODUCT\')
                        and product.status = \'avaiable\'
                        and product.id = \''.$product_id.'\'
    				ORDER BY
    					product.id
    		');
        }
        else //phieu xuat, chi lay san pham da nhap kho (va` ton` dau` ki`)
        {
            $products = DB::fetch_all('
                    				SELECT
                                        DISTINCT wh_invoice_detail.product_id as id,
                                        wh_invoice_detail.product_id as code,
                                        product.name_'.Portal::language().' as name,
                                        unit.id as unit_id,
                                        unit.name_'.Portal::language().' as unit,
                                        product_category.id as category_id,
                                        product_category.name as category,
                                        product.type
                    				FROM
                    					wh_invoice_detail
                                        inner join wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id
                                        inner join product on wh_invoice_detail.product_id = product.id
                                        inner join unit on product.unit_id = unit.id
                                        inner join product_category on product.category_id = product_category.id
                    				WHERE
                                        product.status = \'avaiable\'
                                        AND product.type != \'SERVICE\'
                                        --AND product.type != \'PRODUCT\'
                                        AND wh_invoice.type = \'IMPORT\'
                                        AND product.id = \''.$product_id.'\'
                                        AND wh_invoice.warehouse_id = '.$wh_id.'
                                        AND wh_invoice.portal_id = \''.PORTAL_ID.'\'
                                    UNION
                                    SELECT
                                        wh_start_term_remain.product_id as id,
                                        wh_start_term_remain.product_id as code,
                                        product.name_'.Portal::language().' as name,
                                        unit.id as unit_id,
                                        unit.name_'.Portal::language().' as unit,
                                        product_category.id as category_id,
                                        product_category.name as category,
                                        product.type
                    				FROM
                    					wh_start_term_remain
                                        inner join product on wh_start_term_remain.product_id = product.id
                                        inner join unit on product.unit_id = unit.id
                                        inner join product_category on product.category_id = product_category.id
                    				WHERE
                                        product.status = \'avaiable\'
                                        AND product.type != \'SERVICE\'
                                        --AND product.type != \'PRODUCT\'
                                        AND product.id = \''.$product_id.'\'
                                        AND wh_start_term_remain.warehouse_id = '.$wh_id.'
                                        AND wh_start_term_remain.portal_id = \''.PORTAL_ID.'\'
    		                          ');
        }
        $product_remain = array();
		$product_remain = $start_term_remain;
        foreach($products as $id=>$product)
		{
			if(isset($product_remain[$product['code']]) and $product['code']==$product_remain[$product['code']]['id'])
			{
				/**
                 * BEGIN edit
                 * L?y ra s? lu?ng t?n 
                 * s? ti?n t?n 
                **/
				$products[$id]['remain_num'] = $product_remain[$product['code']]['remain_number'];
                $products[$id]['remain_money'] = $product_remain[$product['code']]['remain_money'];
                /**
                 * END edit
                 * L?y ra s? lu?ng t?n 
                 * s? ti?n t?n 
                **/
                //Ton tai gia (tuc la da co phieu nhap, tinh dc gia TB roi`)
                if(isset($product_remain[$product['code']]['price']))
				{
					$products[$id]['old_price'] = $product_remain[$product['code']]['price'];
				}
				else
				{
				    //neu khong thi lay gia la gia ton dau ki
    				if(isset($product_remain[$product['code']]['start_term_price']))
    				    $products[$id]['old_price'] = $product_remain[$product['code']]['start_term_price'];
                    else
                        $products[$id]['old_price'] = 0;
				}
                //$products[$id]['lastest_imported_price'] = 0; 
                if(Url::get('type') == 'IMPORT')
                {
                    $products[$id]['lastest_imported_price'] = 0;  
                    if(isset($product_remain[$product['code']]['lastest_imported_price']) and $product_remain[$product['code']]['lastest_imported_price'] > 0)
    				    $products[$id]['lastest_imported_price'] = $product_remain[$product['code']]['lastest_imported_price'];
                    else
                        $products[$id]['lastest_imported_price'] = 0;  
                }
			}
			else
			{
				$products[$id]['remain_num'] = 0;
                //them o day nua nha
                $products[$id]['remain_money'] = 0;
				$products[$id]['old_price'] = 0;
                $products[$id]['lastest_imported_price'] = 0;  
			}
		}
        return $products;
    }
}
?>
