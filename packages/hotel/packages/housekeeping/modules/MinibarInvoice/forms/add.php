<?php
class AddMinibarInvoiceForm extends Form
{
	function AddMinibarInvoiceForm()
	{
		Form::Form('AddMinibarInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000'));
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
        //$this->add('total',new IntType(true,'please_add_product','1','100000000000'));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit()
	{
        require_once 'packages/hotel/includes/php/product.php';
		if($this->check())
		{
			$sql = '
				select
					minibar.*, 
                    reservation_room.id as reservation_room_id,
                    reservation_room.reservation_id as reservation_id
				from
					minibar
					inner join reservation_room on reservation_room.room_id = minibar.room_id
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
				where
					reservation.portal_id = \''.PORTAL_ID.'\'
					and reservation_room.status=\'CHECKIN\'
					and room_status.status = \'OCCUPIED\'
					and room_status.IN_DATE = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
					and minibar.id=\''.URL::get('minibar_id').'\'
					and minibar.status <> \'NO_USE\'
			';
			if(!($reservation = DB::fetch($sql)))
			{
				if(!($reservation = $this->one_minibar_overdue('and minibar.id=\''.URL::get('minibar_id').'\'')))
				{
					$this->error('minibar_id','no_reservation');
					return;
				}
			}
            if(isset($_REQUEST['items']) and is_array($_REQUEST['items']))
            {
                $quantity = 0;
                foreach($_REQUEST['items'] as $k => $v)
                {
                    $quantity += $v['quantity'];
                }
                if($quantity == 0)
                {
                    $this->error('','Vui lòng nhập số lượng sử dụng');
                    return;
                }
            }
            if(!isset($_REQUEST['items']))
            {
                $this->error('','Chưa được định mức minibar');
                return;                
            }
			$id = DB::insert('housekeeping_invoice', 
				((URL::get('id')=='(auto)')?array():array('id'))+
				array(
					'code'=>Url::get('code')?Url::get('code'):'',
					'note'=>Url::get('note')?Url::get('note'):'',
					'type'=>'MINIBAR',
					'reservation_room_id'=>$reservation['reservation_room_id'], 
					'minibar_id'=>Url::get('minibar_id'), 
					'user_id'=>Session::get('user_id'),
					'last_modifier_id'=>Session::get('user_id'),
					'total'=>System::calculate_number(URL::get('total')),
					'fee_rate'=>System::calculate_number(URL::get('fee_rate',0)),
					'tax_rate'=>System::calculate_number(URL::get('tax_rate',0)),
					'total_before_tax'=>(System::calculate_number(URL::get('total_before_tax',0)) - System::calculate_number(URL::get('total_discount',0))),
					'discount'=>Url::get('discount')?System::calculate_number(URL::get('discount')):0,
					'time'=>time(),
					'group_payment'=>Url::check('group_payment')?1:0,
					'portal_id'=>PORTAL_ID,
                    'net_price'=>NET_PRICE_MINIBAR,
                    'last_time'=>time(),
                    'lastest_user_id'=>User::id()
				)
			);
            		//start:KID them cai nay de lay ma theo thu tu tang dan
	            $pos = DB::fetch('SELECT max(position) as position FROM housekeeping_invoice WHERE housekeeping_invoice.portal_id=\''.PORTAL_ID.'\' and type =\'MINIBAR\'');
	            if(($pos['position']!=''))
	            {
	                $position = $pos['position'] + 1;
	            }
				else
	            {
	                $position = 1 ;
	            }
				DB::update('housekeeping_invoice',array('position'=>$position),'id='.$id);
	            //end
			$content = 'Use services of minibar <a href="?page=minibar_invoice&id='.$id.'">#'.$id.'</a> total='.URL::get('total').'$';
			$product_description = '';
			if(isset($_REQUEST['items']) and is_array($_REQUEST['items']))
			{
				foreach($_REQUEST['items'] as $key=>$record)
				{
					if(($record['quantity']>0))
					{
						$content.='<br>'.$record['quantity'].' '.$key.' '.$record['price'].'$';
						$record['price'] = System::calculate_number($record['price']);
						$record['invoice_id'] = $id;
						$record['product_id'] = $key;
						DB::insert('housekeeping_invoice_detail',$record);                        
						if(DB::select('minibar_product','product_id=\''.$key.'\' and minibar_id=\''.URL::get('minibar_id').'\''))
						{
							//Cap nhat lai so luong co trong minibar
							DB::query('update minibar_product set quantity=quantity-'.$record['quantity'].' where minibar_id=\''.URL::get('minibar_id').'\' and product_id=\''.$key.'\'');
						}
						else
						{
							//Dung quy trinh truong hop nay khong bao gio xay ra
							//Chi co the ban san pham da co trong minibar
							$id = DB::insert('minibar_product',
								array(
									'minibar_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>System::calculate_number($record['price']),
								)
							);
						}
						if($product=DB::fetch('select product_price_list.*,product.name_1,product.name_2 from product_price_list INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id where product_price_list.product_id=\''.$record['product_id'].'\' and product_price_list.department_code = \'MINIBAR\' and product_price_list.portal_id=\''.PORTAL_ID.'\''))
						{
							$product_description .= 'Quantity'.$record['quantity'].' -  Price: '.$record['price'].' - '
                                                    .'Product Code: '.$product['product_id'].' Product Name: '.$product['name_'.Portal::language()].'<br>';
						}
					}
				}
			}
            
            $warehouse_id = DB::fetch('Select * from portal_department 
                                                inner join warehouse w1 on portal_department.warehouse_id = w1.id 
                                                where portal_department.department_code = \'MINIBAR\' and portal_department.portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
           
            if($warehouse_id)
            {
                DeliveryOrders::get_delivery_orders($id,'MINIBAR',$warehouse_id);
            }
			//$travellers = DB::select_all('reservation_traveller','reservation_room_id=\''.$reservation['id'].'\'');
			$travellers = DB::fetch_all("select * from reservation_traveller where reservation_room_id = '".$reservation['reservation_room_id']."'");
			foreach($travellers as $traveller)
			{
				DB::insert('traveller_comment',
					array(
						'user_id'=>Session::get('user_id'),
						'traveller_id'=>$traveller['traveller_id'],
						'time'=>time(),
						'content'=>$content
					)
				);
			}
			$log_id = System::log('add','Add minibar invoice at room '.DB::fetch('select name from room where id=\''.$reservation['room_id'].'\'','name'),
                        'Code:<a href="?page=minibar_invoice&cmd=edit&id='.$id.'">MN_'.$position.'</a><br>
                        Total money:'.URL::get('total').HOTEL_CURRENCY.'<br>
                        Reservation code: <a href="?page=reservation&cmd=edit&id='.$reservation['reservation_id'].'&r_r_id='.$reservation['reservation_room_id'].'">'.$reservation['reservation_id'].'</a><br>
                        <b>Services:</b><br>
                        '.$product_description);
            System::history_log('RECODE',$reservation['reservation_id'],$log_id);
            System::history_log('MINIBAR',$id,$log_id);
			$location = URL::build('minibar_invoice',array());
            if(Url::get('fast')){
                echo Portal::language('add').' '.Portal::language('minibar').' '.Portal::language('successful'); die();
            }else{
                echo '<script>
                       if(window.opener)
        			   {
        				  window.close();
        			   }
                       var newdiv = document.createElement("div");
                       newdiv.setAttribute("id","progress");
                       newdiv.innerHTML = "<img src = \'packages/core/skins/default/images/updating.gif\'/>Updating room status to server...";
                       ni = document.getElementsByTagName("html")[0];
                       ni.appendChild(newdiv);
       				   window.setTimeout("location=\''.$location.'\'",2000); 
                    </script>';
            }
            exit();
		}
	}
	function draw()
	{
        
		$this->map['unlimited'] = MINIBAR_IMPORT_UNLIMIT;
		if($this->map['unlimited'])
		{
			$field = 'norm_quantity';
		}else{
			$field = 'norm_quantity';
		}
		$this->map['service_charge'] = MINIBAR_SERVICE_CHARGE;
		$this->map['tax_rate'] = MINIBAR_TAX_RATE;
		$this->map['discount'] = 0;
		$minibars = DB::fetch_all('
			select
				minibar.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
			from
				minibar
				inner join reservation_room on reservation_room.room_id = minibar.room_id
				inner join reservation on reservation.id = reservation_room.reservation_id
				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
			where
				reservation.portal_id = \''.PORTAL_ID.'\'
				and reservation_room.status = \'CHECKIN\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
				and minibar.status <> \'NO_USE\'
			order by
				minibar.name
		');
		//$minibars += $this->minibar_overdue();
		sort($minibars);
		if(Url::get('reservation_room_id')){
			$minibar_id = $this->get_minibar_id(Url::iget('reservation_room_id'));
			$_REQUEST['minibar_id'] = $minibar_id;
			
		}
		if(!URL::get('minibar_id'))
		{
			//$current_minibar = current($minibars);
			$minibar_id = '';//$current_minibar['id'];
		}
		else
		{
			$minibar_id = URL::get('minibar_id');
		}
        if($items = DB::fetch_all('
			select
				minibar_product.product_id as id,
				'.$field.' as norm_quantity,
				0 as quantity,
				product.name_'.Portal::language().' as name,
				product_price_list.price
			from
				minibar_product
				inner join product on minibar_product.product_id = product.id
				inner join product_price_list on product_price_list.product_id=product.id and product_price_list.department_code=\'MINIBAR\'
			where
				minibar_product.minibar_id = \''.$minibar_id.'\' and minibar_product.portal_id = \''.PORTAL_ID.'\'
                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                --end giap.ln
			order by 
				UPPER(FN_CONVERT_TO_VN(product.name_'.Portal::language().'))'
		)){} else $items = array();
        
        $i=1;
		foreach($items as $id=>$item)
		{
			if(isset($_REQUEST['items'][$id]))
			{
				//$items[$id]['quantity'] = $_REQUEST['items'][$id]['quantity'];
			}
			$items[$id]['no'] = $i++;
			$items[$id]['price'] = System::display_number($items[$id]['price']);
		}
		$this->parse_layout('add',$this->map+
			array(
			'num_product'=>$i-1,
			'items'=>$items,
			'time'=>date('H:i\' d/m/Y',time()),
			'minibar_id_list'=>array('unavaiable'=>Portal::language('select_minibar'))+String::get_list($minibars),
			'minibar_id'=>1
			)
		);
	}
	function minibar_overdue($cond = '')
	{
		$sql = '
			select 
				minibar.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
			from 
				minibar
				inner join reservation_room on reservation_room.room_id = minibar.room_id 
			where
				reservation_room.status=\'CHECKIN\' and departure_time<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
		';
		return DB::fetch_all($sql);
	}
	function one_minibar_overdue($cond = '')
	{
		$sql = '
			select 
				minibar.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
			from 
				minibar
				inner join reservation_room on reservation_room.room_id = minibar.room_id 
			where
				reservation_room.status=\'CHECKIN\' and departure_time<=\''.Date_Time::to_orc_date(date('d/m/Y',time())).'\'
				'.$cond.'
		';
		return DB::fetch($sql);
	}
	function get_minibar_id($r_r_id){
		return DB::fetch('select minibar.id from minibar inner join room on room.id = minibar.room_id inner join reservation_room on reservation_room.room_id = room.id where reservation_room.id = '.$r_r_id.'','id');
	}
}
?>
