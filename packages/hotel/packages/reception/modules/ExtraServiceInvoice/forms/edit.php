<?php
class EditExtraServiceInvoiceForm  extends Form
{
	function EditExtraServiceInvoiceForm ()
	{
		Form::Form();
		System::set_page_title(HOTEL_NAME);		
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.jec.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.alphanumeric.pack.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/hotel/packages/mice/includes/js/mice_function.js');
		$this->add('bill_number',new TextType(true,'miss_bill_number',0,255));
		if(Url::get('cmd')=='add'){
			//$this->add('reservation_room_id',new IDType(true,'miss_room','reservation_room'));
		}
        
		$this->add('product_group.quantity',new FloatType(true,'invalid_quantity',0.000001,1000000));
        //giap.ln comment cho phep sua gia =0
		//$this->add('product_group.price',new FloatType(true,'invalid_service_price',0.000001,1000000000));
        $this->add('product_group.price',new TextType(true,'invalid_price',0,255));
        //end giap.ln
		$this->add('product_group.from_date',new DateType(true,'not_input_from_date'));
        $this->add('product_group.to_date',new DateType(true,'not_input_to_date'));
	}
	function on_submit()
    {
        $type='';
        $check = true;
        if($check==true)
        {
		if(Url::get('act')){
            /** Son Le Thanh check phong da dong ko dc them DV*/
            $check_close_room = DB::fetch('SELECT * FROM reservation_room WHERE id=\''.Url::get('reservation_room_id').'\'');
            
            if($check_close_room['closed'] == 1){
                $this->error('','Phòng đã đóng không thể thêm dịch vụ!');
                return false;
            }
            /** Son Le Thanh check phong da dong ko dc them DV*/ 
			if($this->check()){
				if(isset($_REQUEST['mi_product_group']))
				{
				    if(Url::get('deleted_ids')){
                    $id=explode(',',Url::get('deleted_ids'));
                     foreach($id as $key=>$value){
                        DB::delete('extra_service_invoice_table','id='.$value.'');
                        DB::delete('extra_service_invoice_detail','table_id='.$value.'');  
                     } 
                    } 				    
				    if(Url::get('cmd')=='add')
                    {
                        $lastest_item_bill = DB::fetch('SELECT id,bill_number FROM extra_service_invoice ORDER BY id DESC');
        				$total_bill = intval(str_replace('ES','',$lastest_item_bill['bill_number']))+1;
        				$total_bill = (strlen($total_bill)<2)?'0'.$total_bill:$total_bill;					
        				$bill_number = 'ES'.$total_bill;
                    }
                    /** Daund Start check loi sua so luong vuot qua sl cho phep va khong cho sua khi hoa don da co dv duoc tao bill or folio*/
                    $check_quantity_exb_from = 0;
                    $check_quantity_exb_to = 0;
                    $check_quantity_bbc_from = 0;
                    $check_quantity_bbc_to = 0;
                    $i =1;
                    foreach($_REQUEST['mi_product_group'] as $key=>$record)
					{
					    //Check số lượng EB
                        if(Url::get('cmd')=='add')
                        {
                            $check_code = DB::fetch('SELECT extra_service.code FROM extra_service WHERE extra_service.id = \''.$record['service_id'].'\'');
                            if($check_code['code'] =='EXTRA_BED')
                            {
                                $extra_status_from = DB::fetch('
                    				SELECT
                    					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                        extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                    				FROM
                    					extra_service_invoice_detail
                    					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    				WHERE
                    					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['from_date'])).'\' AND extra_service.code=\'EXTRA_BED\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\'
                    				GROUP BY
                    					extra_service.code
                    			');
                                $extra_status_to = DB::fetch('
                        				SELECT
                        					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                            extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                        				FROM
                        					extra_service_invoice_detail
                        					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                        					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        				WHERE
                        					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['to_date'])).'\' AND extra_service.code=\'EXTRA_BED\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\'
                        				GROUP BY
                        					extra_service.code
                    			');
                                $check_quantity_exb_from += $extra_status_from['quantity']+$extra_status_from['change_quantity'] + $record['quantity'];
                                $check_quantity_exb_to += $extra_status_to['quantity']+$extra_status_to['change_quantity'] + $record['quantity']; 
                            }
                            if($check_code['code'] == 'BABY_COT')
                            {
                                $extra_status_from = DB::fetch('
                    				SELECT
                    					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                        extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                    				FROM
                    					extra_service_invoice_detail
                    					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    				WHERE
                    					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['from_date'])).'\' AND extra_service.code=\'BABY_COT\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\'
                    				GROUP BY
                    					extra_service.code
                    			');
                                $extra_status_to = DB::fetch('
                        				SELECT
                        					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                            extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                        				FROM
                        					extra_service_invoice_detail
                        					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                        					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        				WHERE
                        					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['to_date'])).'\' AND extra_service.code=\'BABY_COT\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\'
                        				GROUP BY
                        					extra_service.code
                    			');
                                $check_quantity_bbc_from += $extra_status_from['quantity']+$extra_status_from['change_quantity'] + $record['quantity'];
                                $check_quantity_bbc_to += $extra_status_to['quantity']+$extra_status_to['change_quantity'] + $record['quantity'];                                    
                            }
                        }else
                        {
                            $check_code = DB::fetch('SELECT extra_service.code FROM extra_service WHERE extra_service.id = \''.$record['service_id'].'\'');
                            if($check_code['code'] == 'EXTRA_BED')
                            {
                                $extra_status_from = DB::fetch('
                        				SELECT
                        					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                            extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                        				FROM
                        					extra_service_invoice_detail
                        					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                        					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        				WHERE
                        					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['from_date'])).'\' AND extra_service.code=\'EXTRA_BED\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\' and extra_service_invoice.id !=\''.Url::get('id').'\'
                        				GROUP BY
                        					extra_service.code
                    			');
                                $extra_status_to = DB::fetch('
                        				SELECT
                        					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                            extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                        				FROM
                        					extra_service_invoice_detail
                        					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                        					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        				WHERE
                        					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['to_date'])).'\' AND extra_service.code=\'EXTRA_BED\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\' and extra_service_invoice.id !=\''.Url::get('id').'\'
                        				GROUP BY
                        					extra_service.code
                    			');
                                $check_quantity_exb_from += $extra_status_from['quantity']+$extra_status_from['change_quantity'] + $record['quantity'] + $record['change_quantity'];
                                $check_quantity_exb_to += $extra_status_to['quantity']+$extra_status_to['change_quantity'] + $record['quantity'] + $record['change_quantity']; 
                            }
                            if($check_code['code'] == 'BABY_COT')
                            {
                                $extra_status_from = DB::fetch('
                        				SELECT
                        					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                            extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                        				FROM
                        					extra_service_invoice_detail
                        					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                        					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        				WHERE
                        					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['from_date'])).'\' AND extra_service.code=\'BABY_COT\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\' and extra_service_invoice.id !=\''.Url::get('id').'\'
                        				GROUP BY
                        					extra_service.code
                    			');
                                $extra_status_to = DB::fetch('
                        				SELECT
                        					extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity,
                                            extra_service.code as id,sum(NVL(extra_service_invoice_detail.change_quantity,0)) as change_quantity
                        				FROM
                        					extra_service_invoice_detail
                        					INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                            inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                        					INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        				WHERE
                        					extra_service_invoice_detail.in_date=\''.Date_Time::convert_time_to_ora_date(Date_Time::to_time($record['to_date'])).'\' AND extra_service.code=\'BABY_COT\' and reservation_room.status != \'CANCEL\' and reservation_room.status != \'CHECKOUT\' and reservation_room.status != \'NOSHOW\' and extra_service_invoice.id !=\''.Url::get('id').'\'
                        				GROUP BY
                        					extra_service.code
                    			');
                                $check_quantity_bbc_from += $extra_status_from['quantity']+$extra_status_from['change_quantity'] + $record['quantity'] + $record['change_quantity'];
                                $check_quantity_bbc_to += $extra_status_to['quantity']+$extra_status_to['change_quantity'] + $record['quantity'] + $record['change_quantity'];                                   
                            }                              
                        }
                    }
                    //System::debug($check_quantity_exb_from);
                    //System::debug($check_quantity_exb_to);
                    //exit();
                    if(($check_quantity_exb_from > EXTRA_BED_QUANTITY) or ($check_quantity_exb_to > EXTRA_BED_QUANTITY))
                    {
                        $this->error('','Số lượng Extra Bed vượt quá số lượng cho phép!');
                    }
                    if(($check_quantity_bbc_from > BABY_COT_QUANTITY) or ($check_quantity_bbc_to > BABY_COT_QUANTITY))
                    {
                        $this->error('','Số lượng Baby Cot vượt quá số lượng cho phép!');
                    }
                    if($this->is_error())
                    {
                        return;
                    }
                    /** Daund End check loi sua so luong vuot qua sl cho phep va khong cho sua khi hoa don da co dv duoc tao bill or folio*/
					$array = array(
						'payment_type',
						'note',
                        'close'=>(Url::get('close')?1:0),
                        'net_price'=>(Url::get('net_price')?1:0),
						'code'=>Url::get('code')?Url::get('code'):'',
						'total_amount'=>System::calculate_number(Url::get('total')),
						'total_before_tax'=>System::calculate_number(Url::get('total_amount')),
						'tax_rate'=>System::calculate_number(Url::get('tax_rate')),
						'service_rate'=>System::calculate_number(Url::get('service_rate')),
                        'type'=>Url::get('type')?Url::get('type'):'',
						'portal_id'=>PORTAL_ID				
					);
                    
                    $type=Url::get('type');
                    if(Url::get('cmd')=='add')
                    {
                        $array['bill_number'] = $bill_number;
                    }
                    else
                    {
                        $array['bill_number'] = Url::get('bill_number');
                    }
					if(Url::get('cmd')=='edit'){  
						$id = Url::iget('id');
						DB::update('extra_service_invoice',$array+array('last_time'=>time(),'lastest_edited_time'=>time(),'lastest_edited_user_id'=>Session::get('user_id')),'id='.Url::iget('id'));
						$log_action  = 'edit';
						$log_title = 'Edit extra service invoice #'.$array['bill_number'].'';
					}else{
						$id = DB::insert('extra_service_invoice',$array+array('reservation_room_id'=>Url::get('reservation_room_id'),'last_time'=>time(),'lastest_edited_time'=>time(),'lastest_edited_user_id'=>Session::get('user_id'),'time'=>time(),'user_id'=>Session::get('user_id')));
						$log_action  = 'add';
						$log_title = 'Add extra service invoice #'.$array['bill_number'].'';
					}
                    $reservation_id = '';
                    if(Url::get('reservation_room_id'))
                    {
                        $reservation = DB::fetch('select reservation_id from reservation_room where id='.Url::get('reservation_room_id'));
                        $reservation_id = $reservation['reservation_id'];
                    }elseif(Url::get('id')){
                        $reservation = DB::fetch('select reservation_room.reservation_id from reservation_room inner join extra_service_invoice on reservation_room.id=extra_service_invoice.reservation_room_id where extra_service_invoice.id='.Url::get('id'));
                        $reservation_id = $reservation['reservation_id'];
                    }
					$invoice_id = $id; 
					$log_description = 'Payment type: '.Url::get('payment_type').'<br>';
                    $log_description = 'Reservation code: '.$reservation_id.'<br>';
					//$log_description .= 'Payment method: '.DB::fetch('select id,name_'.Portal::language().' as name from payment_type where id = '.Url::get('payment_method_id').'','name').'<br>';
					$log_description .= 'Note: '.Url::get('note').'<br><hr><br>';
					$old_items = DB::select_all('extra_service_invoice_detail','invoice_id='.$invoice_id.'');
					foreach($_REQUEST['mi_product_group'] as $key=>$record)
					{
                        if(DB::fetch('select code as id from extra_service where id='.$record['service_id'],'id')=='EXTRA_BED'){
                            DB::update('extra_service_invoice',array('use_extra_bed'=>1),'id ='.$invoice_id.'');
                        }
                        if(DB::fetch('select code as id from extra_service where id='.$record['service_id'],'id')=='BABY_COT'){
                            DB::update('extra_service_invoice',array('use_baby_cot'=>1),'id ='.$invoice_id.'');
                        }					   
					    $array_date='';
                        $from_date=$record['from_date'];
                        $to_date=$record['to_date'];
                        if($from_date==$to_date){
                            $array_date[Date_Time::to_time($from_date)]=$record['from_date'];
                        }else{
                            for($i=Date_Time::to_time($from_date);$i<=Date_Time::to_time($to_date);$i+=86400){
                                $array_date[$i]= date('d/m/Y',$i);
                            }
                        }
						$record['price']=System::calculate_number($record['price']);
						//$record['quantity']=$record['quantity'];
                        //$record['change_quantity']=System::calculate_number($record['change_quantity']);
						$record['used'] = isset($record['used'])?1:0;
						if($service = DB::fetch('SELECT name FROM extra_service WHERE id=\''.intval($record['service_id']).'\'')){
							$service_name = $service['name'];
						}else{
							$service_name = $record['service_id'];
							//$record['service_id'] = DB::insert('extra_service',array('name'=>$service_name,'unit'=>$record['unit'],'status'=>'SHOW','price'=>$record['price']));
						}
						$record['name'] = $service_name;
						unset($record['payment_price']);
                        unset($record['service_id1']);
                        unset($record['total_day']);
                        unset($record['package_sale_detail_id1']);
						$unit_name = $record['unit'];
						unset($record['unit']);
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
							if($record['id']!='')
							{								 							 
								$log_description .= 'Edit [Service: '.$service_name.', Price: '.System::display_number($record['price']).', Quantity: '.($record['quantity']).', Change Quantity: '.$record['change_quantity'].', Unit: '.$unit_name.', Date:, Used: '.$record['used'].']<br>';
                                DB::update('extra_service_invoice_table',array('from_date'=>Date_Time::to_orc_date($record['from_date']),'to_date'=>Date_Time::to_orc_date($record['to_date'])),'id='.$record['id'].'');
								DB::delete('extra_service_invoice_detail','(in_date>\''.Date_Time::to_orc_date($record['to_date']).'\' or in_date<\''.Date_Time::to_orc_date($record['from_date']).'\') and table_id='.$record['id'].' ');
                                DB::update('extra_service_invoice_detail',array('name'=>$record['name'],'service_id'=>$record['service_id'],'quantity'=>$record['quantity'],'price'=>$record['price'],'usd_price'=>$record['usd_price'],'change_quantity'=>$record['change_quantity'],'note'=>$record['note'],'package_sale_detail_id'=>$record['package_sale_detail_id']),'table_id='.$record['id'].'');
                                $table_id=$record['id'];
                                unset($record['id']);
                                $record['table_id']=$table_id;
                                foreach($array_date as $k=>$v){
                                    $record['in_date']=Date_Time::to_orc_date($v);
                                    unset($record['to_date']);
                                    unset($record['from_date']);
                                    if(!DB::fetch('select id from extra_service_invoice_detail where in_date=\''.$record['in_date'].'\' and table_id='.$table_id.'')){  
                                        DB::insert('extra_service_invoice_detail',$record);                                      
                                    }      
                                }
                                 
							}   
							else
							{
								if(DB::exists('SELECT id FROM extra_service WHERE id=\''.$record['service_id'].'\''))
								{
									if(isset($record['id'])){
										unset($record['id']);
									}
                                    $table_id=DB::insert('extra_service_invoice_table',array('from_date'=>Date_Time::to_orc_date($record['from_date']),'to_date'=>Date_Time::to_orc_date($record['to_date']),'invoice_id'=>$invoice_id));
                                    $record['table_id']=$table_id;
                                    unset($record['to_date']);
                                    unset($record['from_date']);
                                    foreach($array_date as $k=>$v){
                                        $record['in_date']=Date_Time::to_orc_date($v);
                                        DB::insert('extra_service_invoice_detail',$record);
                                    }
									$log_description .= 'Add [Service: '.$service_name.', Price: '.System::display_number($record['price']).', Quantity: '.$record['quantity'].', Unit: '.$unit_name.', Date: '.$record['in_date'].', Used: '.$record['used'].']<br>';
									$record['time'] = time();
								}
							}
						}
					}
                    //echo $reservation_id; die;		
					$log_id = System::log($log_action,$log_title,$log_description,$id);// Edited in 06/01/2010
                    System::history_log('RECODE',$reservation_id,$log_id);
                    System::history_log('EXTRA_SERVICE',$id,$log_id);
                    if(Url::get('fast')){
                        if(Url::get('type')=='SERVICE'){
                         $title= Portal::language('add_extra_service_invoice');
                        }else{
                         $title= Portal::language('add_extra_room_charge');   
                        }
                        echo $title.' '.Portal::language('successful'); die();
                    }else{
					       echo'<script>window.opener.location.reload();</script>';
                           echo'<script>window.close();</script>';
                           //exit();  
                           Url::redirect_current(array('cmd'=>'list','type'=>$type));
					}
				}else{
					$this->error('mi_product_group','there_is_not_any_service');				
				}
			}
		  }else{
		      if(isset($_REQUEST['mi_product_group'])){
		          unset($_REQUEST['mi_product_group']);
		      }
		  }
        }
	}
	function draw()
	{
		$this->map = array();
		$this->map['in_date_options'] = '';
		$item = ExtraServiceInvoice::$item;
        /** MANH THEM BIEN LAY CAC BAN GHI PACKAGE **/
            $this->map['package_service_detail_options'] = '<option value="" >'.portal::language("select_package_service").'</option>';
        /** END MANH **/
		if(isset($item['reservation_room_id'])){
			$reservation_room_id = $item['reservation_room_id'];
            $this->map['reservation_room_id'] = $item['reservation_room_id'];
		}else{
			if(Url::get('reservation_room_id')){
				$reservation_room_id = Url::iget('reservation_room_id');
                $this->map['reservation_room_id'] = Url::iget('reservation_room_id');
			}else{
				$reservation_room_id = 0;
                $this->map['reservation_room_id'] = '';
			}
		}
        /** manh them package **/
        if($reservation_room_id!=0)
        {
            $package_detail = DB::fetch_all("SELECT 
                                                package_sale_detail.*,
                                                package_service.name as package_service_name
                                            FROM 
                                                package_sale_detail
                                                inner join package_service on package_sale_detail.service_id=package_service.id
                                                inner join package_sale on package_sale.id=package_sale_detail.package_sale_id
                                                inner join reservation_room on package_sale.id=reservation_room.package_sale_id
                                            WHERE
                                                reservation_room.id=".$reservation_room_id."
                                            ");
            foreach($package_detail as $detail_id=>$detail_value)
            {
                $this->map['package_service_detail_options'] .= '<option value="'.$detail_value['id'].'" >'.$detail_value['package_service_name'].'</option>';
            }
            /** Son Le Thanh check ko cho tao mice neu DVMR an theo MICE tai phong*/
            $this->map['check_mice'] = 1;
            $check_mice = DB::fetch_all("SELECT *
                FROM 
                    extra_service_invoice
                    INNER JOIN reservation_room ON reservation_room.id=extra_service_invoice.reservation_room_id
                    INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                    INNER JOIN mice_reservation ON reservation.mice_reservation_id=mice_reservation.id
                WHERE
                    reservation_room.id=".$reservation_room_id."
                ");
            if($check_mice ){
                $this->map['check_mice'] = 0;
            }
           /** Son Le Thanh*/
           
           /** Son Le Thanh Check phong chua DVMR da Checkout thi ko dc tao MICE*/
           $check_room_extra = DB::fetch_all("SELECT *
                FROM 
                    extra_service_invoice
                    INNER JOIN reservation_room ON reservation_room.id=extra_service_invoice.reservation_room_id
                    INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                WHERE
                    reservation_room.status= 'CHECKOUT' and reservation_room.id=".$reservation_room_id."
                    ");
            if($check_room_extra){
                $this->map['check_mice'] = 0;
            }
            /** Son Le Thanh*/
        }
        $service = DB::fetch('select * from extra_service where code=\'VFD\'');
		$room = DB::fetch('
			select 
				reservation_room.id,
				reservation.id as reservation_id,
				DECODE(room.name,NULL,reservation_room.temp_room,room.name) name,
				to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_date,
				to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_date,
				reservation_room.time_in,
				reservation_room.time_out,
				reservation_room.status 
			from 
				reservation_room 
				inner join reservation on reservation.id = reservation_room.reservation_id
				left outer join room on room.id = reservation_room.room_id 
			where 
				reservation.portal_id = \''.PORTAL_ID.'\' AND reservation_room.id = '.$reservation_room_id.'');        
		$arrival_time = Date_Time::to_time($room['arrival_date']);
		$departure_time = Date_Time::to_time($room['departure_date']);
		$date_option_temp_str = '';
		for($i=$arrival_time;$i<=$departure_time;$i=$i+(24*3600))
		{
			$date = Date_Time::to_orc_date(date('d/m/Y',$i));
			$current_date = Date_Time::to_orc_date(date('d/m/Y'));
			if(Date_Time::to_time(date('d/m/Y')) <= Date_Time::to_time(date('d/m/Y',$i))){
				$date_option_temp_str .= '<option value="'.date('d/m/Y',$i).'">'.date('d/m/Y',$i).'</option>';
			}
		}
		$this->map['arrival_time'] = $arrival_time;
		$this->map['departure_time'] = $departure_time;
        $lock = 0;
            $this->map['room_name'] = 'RE'.$room['reservation_id'].' - '.$room['name'];
			$this->map['arrival_date'] = $room['arrival_date'];
			$this->map['departure_date'] = $room['departure_date'];
			$this->map['status'] = $room['status'];  
		if($item){
            $this->map['mice_reservation_id'] = $item['mice_reservation_id'];
            $this->map['mice_action_module'] = $item['mice_action_module'];
			$_REQUEST['code'] = $item['code'];
            $_REQUEST['close'] = $item['close'];   
            if($item['late_checkin']==1 or $item['late_checkout']==1 or $item['early_checkin']==1 )
            {
                $lock = 1;
            }
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
            //start: KID them de hien thi % thue, phi theo csdl
            $this->map['service_rate'] = $item['service_rate'];
            $this->map['tax_rate'] = $item['tax_rate'];
            //end: KID them de hien thi % thue, phi theo csdl
			if(!isset($_REQUEST['mi_product_group']))
			{
			    $mi_product_group=DB::fetch_all('
                                            SELECT extra_service_invoice_table.* from extra_service_invoice_table
                                            INNER JOIN extra_service_invoice ON extra_service_invoice.id = extra_service_invoice_table.invoice_id
                                            WHERE
                                                extra_service_invoice_table.invoice_id=\''.$item['id'].'\' 
                                            ');                                                                                                                                             
               foreach($mi_product_group as $k=>$v){
                  	$sql = '
        			  	SELECT * from(SELECT
        					extra_service_invoice_detail.name,
                            extra_service_invoice_detail.quantity,
                            extra_service_invoice_detail.in_date,
                            extra_service_invoice_detail.note,
                            extra_service_invoice_detail.service_id,
                            extra_service_invoice_detail.used,
                            extra_service_invoice_detail.invoice_id,
                            extra_service_invoice_detail.price,
                            extra_service_invoice_detail.usd_price,
                            extra_service_invoice_detail.package_sale_detail_id,
                            extra_service_invoice_detail.change_quantity,
                            --extra_service_invoice_detail.night_audit_date,
                            extra_service_invoice_detail.table_id,
        					(extra_service_invoice_detail.price*extra_service_invoice_detail.quantity) as payment_price,
        					extra_service.unit,
                            extra_service.name as service_name,
                            extra_service.code,
                            extra_service_invoice.reservation_room_id,
                            reservation.id as r_id
        				FROM
        					extra_service_invoice_detail
                            INNER JOIN extra_service ON extra_service.id = extra_service_invoice_detail.service_id
        					INNER JOIN extra_service_invoice_table ON extra_service_invoice_table.id = extra_service_invoice_detail.table_id
                            INNER JOIN extra_service_invoice ON extra_service_invoice.id = extra_service_invoice_table.invoice_id
                            left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                            left join reservation on reservation_room.reservation_id = reservation.id    
        				WHERE
        					extra_service_invoice_detail.table_id='.$v['id'].' 
                            --order by night_audit_date desc
        			     ) where rownum =1';
                    $invoice_detail =DB::fetch($sql);  
    				$mi_product_group[$k]['price'] = System::display_number_report($invoice_detail['price']);
                    $mi_product_group[$k]['usd_price'] = System::display_number_report($invoice_detail['usd_price']);
    				$mi_product_group[$k]['to_date'] = Date_Time::convert_orc_date_to_date($v['to_date'],'/');
                    $mi_product_group[$k]['from_date'] = Date_Time::convert_orc_date_to_date($v['from_date'],'/');
    				$mi_product_group[$k]['payment_price'] = System::display_number_report($invoice_detail['payment_price']);
    				/** Son Le Thanh them so 0 vao trc so luong nho hon 0*/
                    if($invoice_detail['quantity'] < 1)
                    {
                        $mi_product_group[$k]['quantity'] = '0'.$invoice_detail['quantity'];
                    }
                    else
                    {
                        $mi_product_group[$k]['quantity'] = $invoice_detail['quantity'];
                    }
    				if($invoice_detail['change_quantity'] < 1)
                    {
    				    if($invoice_detail['change_quantity'] > 0)
                        {
    				        $mi_product_group[$k]['change_quantity'] = '0'.$invoice_detail['change_quantity'];
    				    }
                        else
                        {
                            $minus = substr($invoice_detail['change_quantity'],0,1);
                            $change_quantity = substr($invoice_detail['change_quantity'],1,8);
                            $mi_product_group[$k]['change_quantity'] = $minus.'0'.$change_quantity;
                        }
    				}
                    else
                    {
                        $mi_product_group[$k]['change_quantity'] = $invoice_detail['change_quantity'];
                    }
                    /** Son Le Thanh them so 0 vao trc so luong nho hon 0*/
                    $mi_product_group[$k]['service_id'] = $invoice_detail['service_id'];
                    $mi_product_group[$k]['unit'] = $invoice_detail['unit'];
                    $mi_product_group[$k]['note'] = $invoice_detail['note'];
                    //$night_audit_date=DB::fetch('select max(night_audit_date) as id from extra_service_invoice_detail where extra_service_invoice_detail.table_id='.$v['id'].'','id');
                    //$mi_product_group[$k]['night_audit_date'] = $night_audit_date?Date_Time::convert_orc_date_to_date($night_audit_date,'/'):'';
                    /**if($v['package_sale_detail_id']!='')
                    {
                        $mi_product_group[$k]['package_sale_detail_id1'] = DB::fetch('SELECT 
                                                package_sale_detail.*,
                                                package_service.name as package_service_name
                                            FROM 
                                                package_sale_detail
                                                inner join package_service on package_sale_detail.service_id=package_service.id
                                            where
                                            package_sale_detail = '.$v['package_sale_detail_id'],'package_service_name'
                                            );
                    }
                    else
                    {
                        $mi_product_group[$k]['package_sale_detail_id1'] = portal::language("select_package_service");
                    }
        			 **/
               }                        
				$_REQUEST['mi_product_group'] = $mi_product_group;
                
                
			}
		}else{
            //start: KID them de hien thi % thue, phi theo csdl
            $this->map['service_rate'] = EXTRA_SERVICE_SERVICE_CHARGE;
            $this->map['tax_rate'] = EXTRA_SERVICE_TAX_RATE;
            //end: KID them de hien thi % thue, phi theo csdl
			if(!Url::get('create_date')){
				$_REQUEST['time'] = date('d/m/Y',time());
			}
			if(!Url::get('bill_number')){
				$lastest_item = DB::fetch('SELECT id,bill_number FROM extra_service_invoice ORDER BY id DESC');
				$total = intval(str_replace('ES','',$lastest_item['bill_number']))+1;
				$total = (strlen($total)<2)?'0'.$total:$total;					
				$_REQUEST['bill_number'] = 'ES'.$total;
			}
		}
        $_REQUEST['lock'] = $lock;
		$this->map['in_date_options'] = $date_option_temp_str;
		$rooms = $this->get_reservations();
        $room_time=$rooms;
        foreach($room_time as $key=>$value){
            $room_time[$key]['arrival_date']=explode('%',$value['arrival_date']);
            $room_time[$key]['departure_date']=explode('%',$value['departure_date']);
            $room_time[$key]['arrival_date']=$room_time[$key]['arrival_date'][1].$room_time[$key]['arrival_date'][2].'20'.$room_time[$key]['arrival_date'][3];
            $room_time[$key]['departure_date']=$room_time[$key]['departure_date'][1].$room_time[$key]['departure_date'][2].'20'.$room_time[$key]['departure_date'][3];
        }
        $this->map['room_time']=String::array2js($room_time);
		$this->map['reservation_room_id_list'] = array(''=>Portal::language('select_room')) + String::get_list($rooms);
        /** start kieu xu li du lieu cu khi them DVMR tu ngay den ngay **/
        if(Url::get('cmd')=='edit' and !$service_type=DB::fetch('select type as id from extra_service_invoice where id='.$item['id'].'','id')){
            $services = DB::select_all('extra_service','(status=\'SHOW\' or code=\'LATE_CHECKIN\')','name');
            $sql='select extra_service.type as id from extra_service_invoice
                    inner join extra_service_invoice_detail on extra_service_invoice_detail.invoice_id=extra_service_invoice.id
                    inner join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                    where extra_service_invoice.id='.$item['id'].'';
           $type=DB::fetch($sql);
           $this->map['payment_type']=$type['id']; 
           if($type['id']=='ROOM'){
            $this->map['payment_type_name']=Portal::language('room');
           }else{
            $this->map['payment_type_name']=Portal::language('service');
           }        
        }else{
            $this->map['type']=Url::get('type');
            $this->map['payment_type']=Url::get('type');
            if(Url::get('type')=='ROOM'){
                $this->map['payment_type_name']=Portal::language('room');
            }else{
                $this->map['payment_type_name']=Portal::language('service');
            }
            $services = DB::select_all('extra_service','(status=\'SHOW\' or code=\'LATE_CHECKIN\') and type=\''.Url::get('type').'\'','name');
        }
        /** end kieu xu li du lieu cu khi them DVMR tu ngay den ngay **/
		foreach($services as $key=>$value){
			$services[$key]['price'] = System::display_number(round($value['price']));
		}
		$this->map['service_options'] = '';
		foreach($services as $value){
			$this->map['service_options'] .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
		$this->map['services'] = String::array2js($services);
		$this->map['payment_method_id_list'] = String::get_list(DB::select_all('payment_type','structure_id <> \''.ID_ROOT.'\'','structure_id'));
        if(Url::get('cmd')=='add'){
            if(Url::get('type')=='SERVICE'){
             $this->map['title']= Portal::language('add_extra_service_invoice');
            }else{
             $this->map['title']= Portal::language('add_extra_room_charge');   
            }
        }else{
           if(Url::get('type')=='SERVICE'){
             $this->map['title']= Portal::language('edit_extra_service_invoice');
            }else{
             $this->map['title']= Portal::language('edit_extra_room_charge');   
            }
        }
		//System::debug($this->map);
        /** MICE **/
        $this->map['close_mice'] = 0;
        if(Url::get('cmd')=='edit' AND $this->map['mice_reservation_id']!='' AND DB::exists('SELECT id FROM mice_reservation WHERE close=1 AND id='.$this->map['mice_reservation_id']))
        {
            $this->map['close_mice'] = 1;
        }
        /** end MICE **/
        /** fix sinh ra ban ghi rong */
        if(Url::get('cmd')=='edit')
        {
            foreach($_REQUEST['mi_product_group'] as $key => $value)
            {
                if($value['service_id'] == '')
                {
                    unset($_REQUEST['mi_product_group'][$key]);
                }
            }            
        }
        /** fix sinh ra ban ghi rong */
        /** tỉ giá quy đổi usd*/
        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
	    $this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
        
        $this->map['last_time'] = time();
        $this->parse_layout('edit',$this->map);
	}	
	function get_reservations(){
		if(Url::get('booking_code')){
			$cond = 'AND UPPER(reservation.booking_code) LIKE \'%'.strtoupper(Url::sget('booking_code')).'%\'';
		}else{
		   if(Url::get('cmd')=='add'){
		      $cond = 'AND
					(
						(reservation_room.status = \'CHECKIN\' AND room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\')
						OR (reservation_room.status = \'BOOKED\' )
					)';
		   }else{
		      if(ExtraServiceInvoice::$item['reservation_room_id']){
		          $cond='AND reservation_room.id='.ExtraServiceInvoice::$item['reservation_room_id'].'';
		      }else{
		          $cond = 'AND reservation_room.status = \'BOOKED\'';
		      }
		      
		   }
		}
        //	OR (reservation_room.status = \'BOOKED\' AND room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\')
		$sql = '
			select
				reservation_room.id,reservation.id as reservation_id,
				CONCAT(CONCAT((CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END),CONCAT(\' - \',CONCAT(\''.Portal::language('duration').': \',CONCAT(to_char(reservation_room.arrival_time,\'DD/MM/YY\'),CONCAT(\' - \',CONCAT(to_char(reservation_room.departure_time,\'DD/MM/YY\'),CONCAT(\', \',reservation_room.status))))))),CONCAT(\' - \',CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)))) as name, 
				to_char(reservation_room.arrival_time,\'%DD/%MM/%YY\') as arrival_date,to_char(reservation_room.departure_time,\'%DD/%MM/%YY\') as departure_date
			from
				reservation_room
				INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				left outer join reservation_traveller on reservation_traveller.traveller_id = reservation_room.traveller_id
				left outer join traveller on traveller.id = reservation_traveller.traveller_id
				left outer join room on room.id  =  reservation_room.room_id
				left outer join room_status on room_status.reservation_room_id  =  reservation_room.id
			where
				reservation.portal_id = \''.PORTAL_ID.'\'
				'.$cond.'
			order by
				reservation_room.arrival_time
		';
		$rrs = DB::fetch_all($sql);//OR(reservation_room.status = \'BOOKED\' and room_status.status = \'BOOKED\' and reservation_room.departure_time >= \''.Date_time::to_orc_date(date('d/m/Y')).'\')
		// (reservation_room.closed <> 1 OR reservation_room.closed IS NULL) AND
		foreach($rrs as $key=>$value)
		{
			$rrs[$key]['name'] = 'RE'.$value['reservation_id'].' - '.$value['name'];
		}
		return $rrs;
	}
}
?>
