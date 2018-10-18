<?php
class MassageEditForm extends Form
{
	function MassageEditForm()
	{
		Form::Form('MassageEditForm');
		$this->link_css('packages/hotel/'.Portal::template('massage').'/css/style.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');		
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->add('product_group.room_id',new TextType(true,'miss_room',0,255));
		$this->add('product_group.code',new TextType(true,'miss_product',0,255));
        $this->add('product_group.price',new TextType(true,'miss_price',0,255));
		$this->add('product_group.quantity',new TextType(true,'miss_quantity',0,255));
		$this->add('product_group.time_in_hour',new TextType(true,'miss_time_in',0,255));
		$this->add('product_group.time_out_hour',new TextType(true,'miss_time_out',0,255));
		//$this->add('people_number',new TextType(true,'miss_people_number',0,50));
		$this->add('staff_group.staff_id',new TextType(true,'miss_staff_id',0,50));
		$this->add('staff_group.room_id',new TextType(true,'miss_room_for_staff',0,50));
		//$this->add('staff_group.full_name',new TextType(false,'invalid_full_name',0,255));
        @$this->link_js('cache/data/'.strtolower(str_replace('#','',PORTAL_ID)).'/SPA_default.js?v='.time());
	}
	function on_submit()
    {
        //System::debug($_REQUEST);exit();
/*-----------------------------------Send Mail------------------------------*/   
        if(file_exists('cache/portal/default/config/config_email.php'))
        {
            require_once ('cache/portal/default/config/config_email.php');
        }     
        if(Url::get('cmd')=='edit' && SPA_INVOICE==1)
        {
            $array_massage_before = DB::fetch('SELECT * FROM massage_reservation_room WHERE id='.Url::get('id'));
                unset($array_massage_before['status']);
                unset($array_massage_before['note']);
                unset($array_massage_before['time']);
                unset($array_massage_before['member_code']);
                unset($array_massage_before['member_level_id']);
                unset($array_massage_before['create_member_date']);
                unset($array_massage_before['lastest_edited_time']);
            $array_massage_product_before = DB::fetch_all('select id,product_id,price,quantity from massage_product_consumed WHERE reservation_room_id='.Url::get('id'));
            
        }
/*-----------------------------------Send Mail------------------------------*/        
        
		if($this->check())
        {
            /** Check hóa đơn chuyển về phòng đã tạo bill */
            if(Url::get('cmd')=='edit')
            {
                
                $sql = "SELECT id,hotel_reservation_room_id, pay_with_room FROM  massage_reservation_room WHERE id=".Url::get('id');
                $result = DB::fetch($sql);
                $old_hotel_reservation_room_id = $result['hotel_reservation_room_id'];
                if($old_hotel_reservation_room_id!="" && $result['pay_with_room']==1)
                {
                     $sql = '
            			select
            				reservation_room.id,
                            traveller.first_name || \' \' || traveller.last_name as agent_name,
                            room.name,
                            reservation_room.traveller_id
            			from
                            reservation 
                            inner join reservation_room on reservation.id = reservation_room.reservation_id
            				inner join room on room.id=reservation_room.room_id
            				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
            				left outer join traveller on traveller.id=reservation_room.traveller_id 
            			where 
            				reservation_room.id =\''.$old_hotel_reservation_room_id.'\'
                            AND reservation_room.status=\'CHECKOUT\'
            			order by 
            				room.name
            			'; 
                    $hotel_reservation_room_id = DB::fetch($sql);
                    //System::debug($hotel_reservation_room_id) ; 
                    if(!empty($hotel_reservation_room_id))
                    {
                        $this->error('','Phòng đã checkout không thể chỉnh sửa!');
                        return false;                    
                    }
                }
                
                if(Url::get('hotel_reservation_room_id_old') != Url::get('hotel_reservation_room_id'))
                {
                    if(DB::fetch('SELECT * FROM traveller_folio WHERE invoice_id =\''.Url::get('id').'\' AND type =\'MASSAGE\''))
                    {
                        $this->error('','Hóa đơn đã được tạo thanh toán trong phòng, không thể đổi phòng khách sạn, vui lòng kiểm tra lại. Xin cảm ơn!');
                        return false;
                    }
                }
                
            }
            
            
            
            if(Url::get('hotel_reservation_room_id')!='') 
            {
                $sql = '
        			select
        				reservation_room.id,
                        traveller.first_name || \' \' || traveller.last_name as agent_name,
                        room.name,
                        reservation_room.traveller_id
        			from
                        reservation 
                        inner join reservation_room on reservation.id = reservation_room.reservation_id
        				inner join room on room.id=reservation_room.room_id
        				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
        				left outer join traveller on traveller.id=reservation_room.traveller_id 
        			where 
        				reservation_room.id =\''.Url::get('hotel_reservation_room_id').'\'
                        AND reservation_room.status=\'CHECKOUT\'
        			order by 
        				room.name
        			'; 
                $hotel_reservation_room_id = DB::fetch($sql);
                //System::debug($hotel_reservation_room_id) ; 
                if(!empty($hotel_reservation_room_id))
                {
                    $this->error('','Phòng đã checkout không thể thêm dịch vụ tại hóa đơn!');
                    return false;                    
                }
            }
            if(!isset($_REQUEST['mi_product_group']))
                return false;
                
			$this->check_staff_conflict($this);
			if($this->is_error())
            {
				return;
			}
			$action = 'add';$title = '';$description = '';$id = 0; // For log
			//$guest_id dc lay tu viec chon khach hang co' san~ ??'
            $guest_id = 0;
			if(Url::get('code'))
            {
                if( $row = DB::fetch('Select * from massage_guest where upper(code)=\''.strtoupper(Url::get('code')).'\' and portal_id = \''.PORTAL_ID.'\' ') )
                {
                    if( strtoupper($row['full_name']) == strtoupper(Url::get('full_name')) )
                        $guest_id = $row['id'];
                    else
                    {
                        $this->error('duplicate_guest_code','duplicate_guest_code');
                        return false;
                    }
                }
                else
                {
                    $guest_id =  DB::insert('massage_guest',array('code'=>strtoupper(Url::get('code')),'full_name'));
                }   
			}   
            
            $amount_pay_with_room = 0;
            $amount_part_payment=0;
            if(Url::get('id'))
            {
                $amount_part_payment = DB::fetch('select sum(amount) as amount from payment where bill_id='.Url::get('id').' and type=\'SPA\' ','amount');
                $total = System::calculate_number(Url::get('total_amount'));    
                if(Url::get('hotel_reservation_room_id')!='') 
                {
                    //tinh tong tien tip chuyen cung ve phong 
                    $total_tip =0;
                    foreach($_REQUEST['mi_staff_group'] as $tip)
                    {
                        if($tip['tip']!='' && $tip['tip']!=0)
                        {
                            $total_tip +=System::calculate_number($tip['tip']);
                        }
                    }
                    //end tinh tong tien tip
                    $amount_pay_with_room = $total - $amount_part_payment + $total_tip;
                }
                    
            }
	    else
	    {
	    	if(Url::get('hotel_reservation_room_id')!='') 
                {
                    $total = System::calculate_number(Url::get('total_amount'));
                    //tinh tong tien tip chuyen cung ve phong 
                    $total_tip =0;
                    foreach($_REQUEST['mi_staff_group'] as $tip)
                    {
                        if($tip['tip']!='' && $tip['tip']!=0)
                        {
                            $total_tip +=System::calculate_number($tip['tip']);
                        }
                    }
                    //end tinh tong tien tip
                    $amount_pay_with_room = $total + $total_tip;
                }
	    }
        
			$array = array(
				'hotel_reservation_room_id'=>Url::get('hotel_reservation_room_id'),
				'full_name'=>Url::get('full_name'),
				'guest_id'=>$guest_id,
				'note'=>Url::get('note'),
                'net_price'=>(Url::get('net_price')?1:0),
                'discount_before_tax'=>DISCOUNT_BEFORE_TAX,
				'discount'=>System::calculate_number(Url::get('discount')),
				'discount_amount'=>System::calculate_number(Url::get('discount_amount')),
                'extra_charge'=>System::calculate_number(Url::get('extra_charge')),
				'tax'=>Url::get('tax'),
                'service_rate'=>Url::get('service_rate'),
				'total_amount'=>System::calculate_number(Url::get('total_amount')),
				'exchange_rate'=>DB::fetch('select exchange from currency where id=\'VND\'','exchange'),
                'member_code'=>Url::get('member_code'),
                'member_level_id'=>Url::get('member_level_id'),
                'create_member_date'=>Url::get('create_member_date'),
		'pay_with_room' => Url::get('pay_with_room') ? Url::get('pay_with_room'): '',
                'amount_pay_with_room' =>System::calculate_number($amount_pay_with_room),
                'customer_id'=>Url::get('customer_id'),
				'portal_id'=>PORTAL_ID
			);
            $description = '';
            $room_name_h = '';
            if(Url::get('hotel_reservation_room_id'))
            {
                $room_name_hotel = DB::fetch('Select room.name as id, reservation_room.reservation_id as reservation_id from reservation_room inner join room on reservation_room.room_id = room.id where reservation_room.id='.Url::get('hotel_reservation_room_id'));
                $room_name_h = $room_name_hotel['id'];
                $description .= "reservation code: ".$room_name_hotel['reservation_id'];
            }
            
			$description .= '
				Customer: Code: '.Url::get('full_name').', Name:'.Url::get('full_name').($room_name_h?', Room hotel: '.$room_name_h:'').'<br>
				Total amount: '.Url::get('total_amount').'<br>
				Discount: '.Url::get('discount').'%<br>
				'.(Url::get('tax')?'Tax: '.Url::get('tax').'%<br>':'').'
				'.(Url::get('service_rate')?'Extra Charge: '.Url::get('service_rate').'%<br>':'').'
				'.(Url::get('note')?'Note: '.Url::get('note').'<br>':'').'
			';  
			if(Url::get('cmd')=='edit')
            {   
                $ma_consumed = DB::fetch_all('select * from massage_product_consumed where reservation_room_id = '.Url::iget('id'));
                //System::debug($ma_consumed);
                //System::debug($_REQUEST['mi_product_group']);//exit();
                
                foreach($ma_consumed as $k=>$v)
                {
                    foreach($_REQUEST['mi_product_group'] as $k1 => $v1)
                    {
                        if($v['id']==$v1['id'])
                        {
                            if($v['status']!=$v1['status'])
                            {
                                if($v1['status']=='CHECKOUT')
                                {
                                    $_REQUEST['mi_product_group'][$k1]['user_checkout'] = Session::get('user_id');
                                    
                                }
                            }
                        }
                    }
                    
                }
				//System::debug($_REQUEST['mi_product_group']);exit();
                
                $id = Url::iget('id');
				$action = 'Edit'; 
				$title = 'Edit masssage invoice '.$id.'';
                //System::debug($array); exit();
                if(DB::fetch('SELECT * FROM traveller_folio WHERE invoice_id =\''.Url::get('id').'\' AND type =\'MASSAGE\''))
                {
                    unset($array['net_price']);
                }
				DB::update('massage_reservation_room',$array+array('lastest_edited_time'=>time(),'lastest_edited_user_id'=>Session::get('user_id')),'id='.Url::iget('id'));
/*-----------------------------------Send Mail------------------------------*/
                if(SPA_INVOICE==1)
                {
                    $array_massage_after = DB::fetch('SELECT * FROM massage_reservation_room WHERE id='.Url::get('id'));
                        unset($array_massage_after['status']);
                        unset($array_massage_after['note']);
                        unset($array_massage_after['time']);
                        unset($array_massage_after['member_code']);
                        unset($array_massage_after['member_level_id']);
                        unset($array_massage_after['create_member_date']);
                        unset($array_massage_after['lastest_edited_time']);
                    
                    $array_massage_product_after = DB::fetch_all('select id,product_id,price,quantity from massage_product_consumed WHERE reservation_room_id='.Url::get('id'));                    
                    if($array_massage_before != $array_massage_after || $array_massage_product_after != $array_massage_product_before)
                    {
                        DB::update('massage_reservation_room',array('check_edit'=>'1'),'id='.Url::get('id'));
                    }
                }                
/*-----------------------------------Send Mail------------------------------*/ 			
            }
            else
            {
                
                //giap.ln them truong hop package spa cho: chinh sua gia 
                if(Url::get('package_id'))
                {
                    $array['package_id'] = Url::get('package_id');
                    $array['hotel_reservation_room_id'] = Url::get('rr_id'); 
                    //$package_sale_detail = DB::fetch("SELECT * FROM package_sale_detail WHERE id=".Url::get('package'))
                    $array['amount_pay_with_room'] = $array['total_amount'];
                       
                }
                //end giap.ln 
				$id = DB::insert('massage_reservation_room',$array+array('time'=>time(),'user_id'=>Session::get('user_id'),'check_edit'=>'1'));
				$action = 'Add'; 
				$title = 'Add masssage invoice '.$id.'';
			}
			//----------------------------------------------
			if(Url::get('product_deleted_ids'))
			{
				$group_deleted_ids = explode(',',Url::get('product_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('massage_product_consumed',$delete_id);
				}
			}
			$room_arr = array();
			$description .='<hr>';
			$invoice_id = $id;
			if(isset($_REQUEST['mi_product_group']))
			{
			     
                 
				foreach($_REQUEST['mi_product_group'] as $key=>$record)
				{
				    
				    $room_id = $record['room_id'];
					$record['price'] = System::calculate_number($record['price']);
					if(!$record['room_id']){
						$record['room_id'] = 0;
					}else{
						$room_arr[$record['room_id']] = $record['room_id'];
					}
                    $service_name = $record['name'];
					unset($record['name']);
					$code = $record['code'];					
					unset($record['code']);
					unset($record['amount']);
					$time_in = 0;
					$time_out = 0;
					if($record['time_in_hour'] and $record['time_out_hour'])
					{
						$arr = explode(':',$record['time_in_hour']);
						$time_in = Date_Time::to_time($record['time_in_date'])+ intval($arr[0])*3600+intval($arr[1])*60;
						$arr = explode(':',$record['time_out_hour']);
						$time_out = Date_Time::to_time($record['time_out_date'])+ intval($arr[0])*3600+intval($arr[1])*60;
					}
					$record['time_in'] = $time_in;
					$record['time_out'] = $time_out;
					unset($record['time_in_hour']);
					unset($record['time_in_date']);
					unset($record['time_out_hour']);
					unset($record['time_out_date']);
                    unset($record['minute']);
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
						$record['reservation_room_id'] = $invoice_id;
						//Them dk !room_ids boi vi khi co room_ids thi se co record id mac du la add
                        if( $record['id'] and DB::exists('select id from massage_product_consumed where id = '.$record['id']) and !Url::get('room_ids') )
						{
							$record['lastest_edited_time']=time();
							$record['lastest_edited_user_id']=Session::get('user_id');
							$description .= '<strong>Edit</strong> [Service ID: '.$code.', Service name: '.$service_name.', Price: '.$record['price'].', Quantity: '.$record['quantity'].', Time: '.date('d/m/Y H:i\'',$time_in).' - '.date('d/m/Y H:i\'',$time_out).', Status: '.$record['status'].']<br>';
							DB::update('massage_product_consumed',$record,'id=\''.$record['id'].'\'');
						}
						else
						{
							unset($record['id']);
							if(DB::exists('SELECT id FROM product WHERE id=\''.$record['product_id'].'\'')){
								$record['time']=time();
								$record['user_id']=Session::get('user_id');
								$description .= '<strong>Add</strong> [Service ID: '.$code.', Service name: '.$service_name.', Price: '.$record['price'].', Quantity: '.$record['quantity'].', Time: '.date('d/m/Y H:i\'',$time_in).' - '.date('d/m/Y H:i\'',$time_out).', Status: '.$record['status'].']<br>';
                                DB::insert('massage_product_consumed',$record);
							}
						}
					}
				}
			}
			else{
				//$this->error('product_code','miss_room');
				return;
			}
            //System::debug($record);exit();
            //start: KID them de tu dong xuat kho cho san pham
            
            require_once 'packages/hotel/includes/php/product.php';
            if (Url::get('cmd') == 'add')
            {
                //Nếu không còn detail thì xóa bản ghi chính
                if( !DB::exists('Select * from massage_product_consumed where reservation_room_id = '.$id ) )
                    DB::delete_id('massage_reservation_room',$id);
                else//Neu ton tai thi tao phieu xuat
                {
                    $warehouse_id = DB::fetch('Select * from portal_department 
                                                inner join warehouse w1 on portal_department.warehouse_id = w1.id 
                                                where portal_department.department_code = \'SPA\' and portal_department.portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
                    if($warehouse_id['id'])
                    {
                    DeliveryOrders::get_delivery_orders($id,'SPA',$warehouse_id);
                    }
                }
            }
            else
            {
                //Vẫn còn tồn tại bản ghi trong detail thì update lại
                if( DB::exists('Select * from massage_product_consumed where reservation_room_id = '.Url::get('id')) )
                {
                    DB::update_id( 'massage_reservation_room',array( 'note','lastest_edited_user_id'=>Session::get('user_id'),'lastest_edited_time'=>time() ),Url::get('id') );
                    $warehouse_id = DB::fetch('Select * from portal_department 
                                                inner join warehouse w1 on portal_department.warehouse_id = w1.id 
                                                where portal_department.department_code = \'SPA\' and portal_department.portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
                    if($warehouse_id['id'])
                    {
                     DeliveryOrders::get_delivery_orders(Url::get('id'),'SPA',$warehouse_id);
                     }
    
                }
                else//Nếu không còn detail thì xóa bản ghi chính
                {
                    DB::delete_id('massage_reservation_room',Url::get('id'));
                    DeliveryOrders::delete_delivery_order(Url::get('id'),'SPA');
                }
            
            }
            
            
            //end
			//----------------------------------------------
			if(Url::get('staff_deleted_ids'))
			{
				$group_deleted_ids = explode(',',Url::get('staff_deleted_ids'));
				foreach($group_deleted_ids as $delete_id)
				{
					DB::delete_id('massage_staff_room',$delete_id);
				}
			}
			if(isset($_REQUEST['mi_staff_group']) and !empty($room_arr))
			{
			    $description .='<hr>'; 
				foreach($_REQUEST['mi_staff_group'] as $key=>$record)
				{
				    $record['tip'] = System::calculate_number($record['tip']);
					$staff_name = DB::fetch('select full_name as id from massage_staff where id='.$record['staff_id']);
                    unset($record['full_name']);
					$record['portal_id']=PORTAL_ID;
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
						unset($record['id']);
						$record['reservation_room_id'] = $id;
						if($staff = DB::select('massage_staff_room','reservation_room_id = '.$id.' and staff_id = '.$record['staff_id'].''))
						{
						    $description .= '<strong>Add</strong> [Staff ID: '.$record['staff_id'].', Staff name: '.$staff_name['id'].', Tip: '.$record['tip'].']<br>';  
							DB::update('massage_staff_room',$record,'id=\''.$staff['id'].'\'');
						}
						else
						{
							if(DB::exists('SELECT id FROM massage_staff WHERE id=\''.$record['staff_id'].'\'')){
								$description .= '<strong>Add</strong> [Staff ID: '.$record['staff_id'].', Staff name: '.$staff_name['id'].', Tip: '.$record['tip'].']<br>';
                                DB::insert('massage_staff_room',$record);
							}
						}
					}
				}
			}
			$currency_arr = array();
			$currencies = DB::select_all('currency','allow_payment=1','name');
			foreach($currencies as $c_value){
				if(Url::get('currency_'.$c_value['id'])){
					$currency_arr[$c_value['id']]['id'] = $c_value['id'];
					$currency_arr[$c_value['id']]['value'] = Url::get('currency_'.$c_value['id']);
					$currency_arr[$c_value['id']]['exchange_rate'] = $c_value['exchange'];
				}else{
					DB::delete('pay_by_currency','bill_id='.$id.' and currency_id=\''.$c_value['id'].'\' and type=\'MASSAGE\'');
				}
			}
			System::log($action,$title,$description,$id);
			foreach($currency_arr as $c_a_key=>$c_a_value){
				if($c_a_value['value']){
					if($row=DB::fetch('select * from pay_by_currency where bill_id='.$id.' and currency_id=\''.$c_a_value['id'].'\' and type=\'MASSAGE\'')){
						DB::update('pay_by_currency',array('exchange_rate'=>$c_a_value['exchange_rate'],'amount'=>str_replace(',','',$c_a_value['value'])),'id='.$row['id']);
					}else{
						DB::insert('pay_by_currency',array('bill_id'=>$id,'currency_id'=>$c_a_value['id'],'amount'=>str_replace(',','',$c_a_value['value']),'exchange_rate'=>$c_a_value['exchange_rate'],'type'=>'MASSAGE'));
					}
				}
			}
            if(Url::get('save_stay'))
            {
                
                Url::redirect_current(array('cmd'=>'edit','id'=>$id,'room_id'=>$room_id));
                //echo '<script>windown.location='.Url::build('massage_daily_summary',array('cmd'=>'edit','id'=>$id,'room_id'=>$room_id)).';</script>';
            }
            else
            {
    			echo '<script>if(window.opener){window.opener.history.go(0);}else{window.location="'.Url::build_current(array('just_edited_id'=>$id)).'"} window.close();</script> ';
    			Url::redirect_current();
            }
		}
	}
	function draw()
	{
		$this->map = array(); 
        $this->map['hotel_reservation_room_id'] = '';
		$this->map['room_number'] = DB::fetch('SELECT NAME FROM MASSAGE_ROOM WHERE ID = '.Url::iget('room_id').' ORDER BY NAME','name');
		$this->map['status_list'] = array(
			'BOOKED'=>'BOOKED',		
			'CHECKIN'=>'CHECKIN',		
			'CHECKOUT'=>'CHECKOUT'			
		);
        
        $this->map['total_amount'] = 0;
        $this->map['amount_pay_with_room']=0;
        $this->map['check_discount'] = 0;
        if(DB::fetch('SELECT * FROM traveller_folio WHERE invoice_id =\''.Url::get('id').'\' AND type =\'MASSAGE\''))
        {
            $this->map['check_discount'] = 1;           
        }
		$this->map['status_options'] = '';
		foreach($this->map['status_list'] as $key=>$value){
			$this->map['status_options'] .= '<option value="'.$key.'">'.$value.'</option>';	
		}
		$this->map['exchange_rate'] = DB::fetch('SELECT exchange FROM currency WHERE id=\'VND\'','exchange');
		$currencies = DB::select_all('currency','allow_payment=1','name');
		foreach($currencies as $key=>$value){
			$currencies[$key]['name'] = ($key=='USD')?'Credit card':$value['name'];
		}
		$this->map['currencies'] = $currencies;
		$item = MassageDailySummary::$item;
        //System::debug($item);		
		$this->map['total_amount_vnd'] = '';
		/**
		 * when edit
		 */
        if($item)
        {
            $this->map['total_amount'] = $item['total_amount'];
			$this->map['hotel_reservation_room_id'] = $item['hotel_reservation_room_id'];
            $this->map['amount_pay_with_room'] = $item['amount_pay_with_room'];
            //$payment = DB::fetch('select sum(amount) as amount from payment where bill_id='.Url::get('id').' and type=\'SPA\' ','amount');
            $payment_1 = DB::fetch('select sum(amount*exchange_rate) as amount from payment where payment.bill_id=\''.Url::get('id').'\' and payment_type_id != \'REFUND\' AND payment.type=\'SPA\'','amount');
            $payment_2 = DB::fetch('select sum(amount*exchange_rate) as amount from payment where payment.bill_id=\''.Url::get('id').'\' and payment_type_id = \'REFUND\' AND payment.type=\'SPA\'','amount');
            $payment_1=$payment_1?$payment_1:0;
            $payment_2=$payment_2?$payment_2:0;
            $payment = $payment_1-$payment_2;
            $this->map['payment'] = $payment?$payment:0;
            //System::debug($payment);
			$currency_arr = DB::fetch_all('select currency_id as id,amount,bill_id from pay_by_currency where bill_id='.$item['id'].' and type=\'MASSAGE\'');
			foreach($currency_arr as $key=>$value){
				$_REQUEST['currency_'.$value['id']] = $value['amount'];
			}
			foreach($item as $key=>$value){
				if($key=='time_in'){
					$_REQUEST['time_in_date'] = !isset($_REQUEST['time_in_date'])?date('d/m/Y',$value):$_REQUEST['time_in_date'];
					$_REQUEST['time_in_hour'] = !isset($_REQUEST['time_in_hour'])?date('H:i',$value):$_REQUEST['time_in_hour'];
				}
				if($key=='time_out'){
					$_REQUEST['time_out_date'] = !isset($_REQUEST['time_out_date'])?date('d/m/Y',$value):$_REQUEST['time_out_date'];
					$_REQUEST['time_out_hour'] = !isset($_REQUEST['time_out_hour'])?date('H:i',$value):$_REQUEST['time_out_hour'];
				}
				if(!isset($_REQUEST[$key])){
					$_REQUEST[$key] = $value;
				}
			}
			if(!isset($_REQUEST['mi_staff_group']))
			{
				$sql = '
					SELECT
						massage_staff_room.*,
						massage_staff.full_name
					FROM
						massage_staff_room
						INNER JOIN massage_staff ON massage_staff.id = staff_id
					WHERE
						massage_staff_room.reservation_room_id=\''.$item['id'].'\'
				';
				$mi_staff_group = DB::fetch_all($sql);
                foreach($mi_staff_group as $key=>$value){
                    $mi_staff_group[$key]['tip'] = System::display_number($value['tip']);
                }
				$_REQUEST['mi_staff_group'] = $mi_staff_group;
			}
            //System::debug($mi_staff_group); 
			if(!isset($_REQUEST['mi_product_group']))
			{
				$sql = '
					SELECT
						massage_product_consumed.*,
                        (massage_product_consumed.price*massage_product_consumed.quantity) as amount,
						product.name_'.Portal::language().' as name,
                        product.id as code,
                        product_price_list.use_time as minute
					FROM
                        massage_reservation_room
						inner join massage_product_consumed on massage_reservation_room.id = massage_product_consumed.reservation_room_id
						INNER JOIN product ON product.id = massage_product_consumed.product_id
                        INNER JOIN product_price_list ON product_price_list.product_id = product.id
					WHERE
						massage_product_consumed.reservation_room_id=\''.$item['id'].'\' and massage_reservation_room.portal_id=\''.PORTAL_ID.'\'
				';
				if($mi_product_group = DB::fetch_all($sql)){
					foreach($mi_product_group as $key=>$value){
					    if($value['quantity'] >= 1)
                        {
                            $mi_product_group[$key]['quantity'] = $value['quantity'];
                        }
                        else
                        {
                            $mi_product_group[$key]['quantity'] = '0'.$value['quantity'];
                        }   
						$this->map['total_amount_vnd'] += $value['price_vnd']*$value['quantity'];
						$mi_product_group[$key]['time_in_hour'] = date('H:i',$value['time_in']);
						$mi_product_group[$key]['time_in_date'] = date('d/m/Y',$value['time_in']);
						$mi_product_group[$key]['time_out_hour'] = date('H:i',$value['time_out']);
						$mi_product_group[$key]['time_out_date'] = date('d/m/Y',$value['time_out']);
                        $mi_product_group[$key]['price'] = System::display_number($value['price']);
                        $mi_product_group[$key]['amount'] = System::display_number($value['amount']);
					}
                    
				}
				$_REQUEST['mi_product_group'] = $mi_product_group;
			}
			$this->map['total_amount_vnd'] += $this->map['total_amount_vnd']*$item['tax']/100;
			$this->map['total_amount_vnd'] -= $this->map['total_amount_vnd']*$item['discount']/100;
            $this->map['tax'] = $item['tax'];
            $this->map['pay_with_room'] = $item['pay_with_room'];
            $this->map['service_rate'] = $item['service_rate'];
            $this->map['room_traveller_name'] = ($item['guest_name'])?$item['guest_name']:'';
		}
        else // when add
        {
			if(Url::get('room_ids') and !isset($_REQUEST['mi_product_group']))
            {
				$room_ids = explode(',',Url::get('room_ids'));
				$mi_product_group = array();
				$i = 1;
				foreach($room_ids as $value)
                {
					$mi_product_group[$i]['id'] = $i;
					$mi_product_group[$i]['room_id'] = $value;
					$mi_product_group[$i]['status'] = 'CHECKIN';
					$i++;
				}
				$_REQUEST['mi_product_group'] = $mi_product_group;
			}
            $this->map['tax'] = SPA_TAX_RATE;
            $this->map['service_rate'] = SPA_SERVICE_RATE;		
            $this->map['discount_before_tax'] = DISCOUNT_BEFORE_TAX;
            
            //giap.ln hien thi mac dinh so phong ma package do chuyen ve 
            if(Url::get('package_id'))
            {
                $this->map['hotel_reservation_room_id'] = Url::get('rr_id');
            }	
            //end giap.ln hien thi mac dinh so phong co su dung package 
            $this->map['room_traveller_name'] = '';
            $this->map['pay_with_room'] = 0;
		}
		$this->map['total_amount_vnd'] = System::display_number($this->map['total_amount_vnd']);
		$sql = '
			SELECT
				massage_staff.id,massage_staff.full_name
			FROM
				massage_staff
			WHERE
				1=1 AND massage_staff.status =\'ready\' AND massage_staff.portal_id = \''.PORTAL_ID.'\'
		';
		$staffs = DB::fetch_all($sql);
		$this->map['staff_options'] = '<option value="">'.Portal::language('select_staff').'</option>';
		foreach($staffs as $key=>$value){
			$this->map['staff_options'] .= '<option value="'.$value['id'].'">'.$value['full_name'].'</option>';
		}
		$this->map['staffs'] = $staffs;
		$sql = '
			SELECT
				massage_room.id,massage_room.name
			FROM
				massage_room
			WHERE
				1=1 and portal_id = \''.PORTAL_ID.'\'
                
			ORDER BY
				massage_room.name
		';
		$rooms = DB::fetch_all($sql);
		$this->map['room_options'] = '<option value="">'.Portal::language('select_room').'</option>';
        $this->map['room_options_staff'] = '<option value="">'.Portal::language('select_room').'</option>';        
		foreach($rooms as $key=>$value)
        {
			$this->map['room_options'] .= '<option  value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        foreach($rooms as $key=>$value)
        {
			$this->map['room_options_staff'] .= '<option style="display:none;" class="staff_'.$value['id'].'" value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        //System::debug($this->map);
        $hotel_reservation_room_id = array();
        if(isset($this->map['hotel_reservation_room_id']))
        {
            $sql = '
    			select
    				reservation_room.id,
                    traveller.first_name || \' \' || traveller.last_name as agent_name,
                    room.name,
                    reservation_room.traveller_id
    			from
                    reservation 
                    inner join reservation_room on reservation.id = reservation_room.reservation_id
    				inner join room on room.id=reservation_room.room_id
    				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
    				left outer join traveller on traveller.id=reservation_room.traveller_id 
    			where 
    				reservation_room.id =\''.$this->map['hotel_reservation_room_id'].'\'
                    AND reservation_room.status=\'CHECKOUT\'
    			order by 
    				room.name
    			'; 
            $hotel_reservation_room_id = DB::fetch_all($sql);        
        }
		$sql = '
			select
				reservation_room.id,
                traveller.first_name || \' \' || traveller.last_name as agent_name,
                room.name,
                reservation_room.traveller_id
			from
                reservation 
                inner join reservation_room on reservation.id = reservation_room.reservation_id
				inner join room on room.id=reservation_room.room_id
				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
			where 
				reservation_room.status=\'CHECKIN\'
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
                and reservation.portal_id = \''.PORTAL_ID.'\'
			order by 
				room.name
			';
		$reservations = DB::fetch_all($sql) + $hotel_reservation_room_id;
        //System::debug($reservations);
		$guest_id_list = '\'\'';
		foreach($reservations as $key=>$value){
			$guest_id_list .=',\''.$value['traveller_id'].'\'';
		}
		if(
            $travellers = DB::fetch_all('
                            			SELECT 
                            				reservation_room.id,
                                            traveller.first_name || \' \' || traveller.last_name as full_name
                            			FROM 
                                            reservation 
                                            inner join reservation_room on reservation.id = reservation_room.reservation_id
                                            left join traveller ON reservation_room.traveller_id = traveller.id 
                            			WHERE 
                            				traveller.id IN ('.$guest_id_list.') ')
            )
        {
			$this->map['travellers'] = String::array2js($travellers);
		}
        else
        {
			$this->map['travellers'] = '{}';
		}
		//$reservations += $this->room_overdue();
		$this->map['hotel_reservation_room_id_list'] = array(''=>Portal::language('select')) + String::get_list($reservations);
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('invoice'):Portal::language('edit_invoice');
		
                                        
		$sql = 'select  
                    product.id as id,
                    product.id as product_id,
                    product.name_'.Portal::language().' as name, 
                    product_price_list.price,
                    product_price_list.use_time
				from 	
                    product_price_list
					INNER JOIN product ON product_price_list.product_id = product.id
				where
                    product_price_list.portal_id = \''.PORTAL_ID.'\'
                    and product.status=\'avaiable\'
                    and product_price_list.department_code = \'SPA\'
			';	
		$this->map['products'] = DB::fetch_all($sql);
        //System::debug($sql);
        if(Url::get('id'))
        {
            $member = DB::fetch("SELECT member_code,member_level_id,create_member_date FROM massage_reservation_room WHERE massage_reservation_room.id=".Url::get('id'));
            $_REQUEST += $member;
        }
        //tinh tien package su dung neu co
        if(Url::get('package_id'))
        {
            $package_sale_detail = DB::fetch("SELECT * FROM package_sale_detail WHERE id=".Url::get('package_id'));
            $price_package = intval($package_sale_detail['price']);
            $this->map['price_package'] = $price_package;
        }
        else
            $this->map['price_package'] = 0;
        
        $this->map['customer_id'] = '';
        $this->map['customer_name'] = '';
        $_REQUEST['customer_id'] = '';
        $_REQUEST['customer_name'] = '';
        if(Url::get('id')){
            $customer = DB::fetch('select customer.id,customer.name from customer inner join massage_reservation_room on massage_reservation_room.customer_id=customer.id where massage_reservation_room.id='.Url::get('id'));
            $this->map['customer_id'] = isset($customer['id'])?$customer['id']:'';
            $this->map['customer_name'] = isset($customer['name'])?$customer['name']:'';
            $_REQUEST['customer_id'] = isset($customer['id'])?$customer['id']:'';
            $_REQUEST['customer_name'] = isset($customer['name'])?$customer['name']:'';
        }
        //System::debug($_REQUEST['mi_product_group']);
        $this->parse_layout('edit',$this->map);
	}
	function room_overdue($cond = '')
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,room.name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
				left outer join traveller on traveller.id=reservation_room.traveller_id 
			where
				reservation_room.status=\'CHECKIN\' and FROM_UNIXTIME(reservation_room.time_out)<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
			order by
				room.name
		';
		return DB::fetch_all($sql);
	}
	function check_conflick($form){
		
	}
	function check_staff_conflict(){
	   
	}
}
?>
