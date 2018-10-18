<?php
class AddMinibarInvoiceForm extends Form
{
	function AddMinibarInvoiceForm()
	{
		Form::Form('AddMinibarInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000'));
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit()
	{
		if($this->check())
		{
			$sql = '
				select
					minibar.*, reservation_room.id as reservation_room_id
				from
					minibar
					inner join reservation_room on reservation_room.room_id = minibar.room_id
					inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
				where
					reservation_room.status=\'CHECKIN\'
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
			$id = DB::insert('housekeeping_invoice', 
				((URL::get('id')=='(auto)')?array():array('id'))+
				array(
					'type'=>'MINIBAR',
					'reservation_room_id'=>$reservation['reservation_room_id'], 
					'minibar_id'=>Url::get('minibar_id'), 
					'user_id'=>Session::get('user_id'),
					'last_modifier_id'=>Session::get('user_id'),
					'total'=>System::calculate_number(URL::get('total')),
					'fee_rate'=>System::calculate_number(URL::get('fee_rate',0)),
					'tax_rate'=>System::calculate_number(URL::get('tax_rate',0)),
					'total_before_tax'=>System::calculate_number(URL::get('total_before_tax',0)),
					'discount'=>System::calculate_number(URL::get('discount')),
					'time'=>time()
				)
			);
			$content = 'Use services of minibar <a href="?page=minibar_invoice&id='.$id.'">#'.$id.'</a> total='.URL::get('total').'$';
			$product_description = '';
			//Chi tiet hoa don
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
						//luu status product
						//Neu san pham hang hoa khong con duoc su dung nua hoac tam khong su dung
						//Cac bao cao sau nay se lay du lieu tu day
						$date = Date_Time::convert_time_to_ora_date(time());
						$sql = 'select
									id
								from
									hk_product_status
								where
									time = \''.$date.'\' 
									and product_id = \''.$key.'\' 
									and from_table = \'hk_product\'';
	
						if(!DB::exists($sql)){
							$status['time'] = $date;
							$status['product_id'] = $key;
							$status['from_table'] = 'hk_product';
							$status['status'] = 'avaiable';
							DB::insert('hk_product_status',$status);
						}					
						// end
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
						if($product=DB::select('hk_product','id=\''.$record['product_id'].'\''))
						{
							$product_description .= $record['quantity'].' <a href="?page=housekeeping_product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a><br>';
						}
					}
				}
			} 
			//$travellers = DB::select_all('reservation_traveller','reservation_room_id=\''.$reservation['id'].'\'');
			$travellers = DB::fetch_all("select * from reservation_traveller where reservation_room_id = '".$reservation['RESERVATION_ROOM_ID']."'");
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
			System::log('add','Add minibar invoice at room '.DB::fetch('select name from room where id=\''.$reservation['room_id'].'\'','name'),
'Code:<a href="?page=minibar_invoice&id='.$id.'">'.$id.'</a><br>
Total money:'.URL::get('total').HOTEL_CURRENCY.'<br>
Reservation code: <a href="?page=reservation&id='.$reservation['reservation_room_id'].'">'.$reservation['reservation_room_id'].'</a><br>
<b>Services:</b><br>
'.$product_description);
			URL::redirect_current();
		}
	}
	function draw()
	{
		//variable
		
		$this->map['unlimited'] = MINIBAR_IMPORT_UNLIMIT;
		if($this->map['unlimited'])
		{
			$field = 'norm_quantity';
		}else{
			$field = 'quantity';
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
				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
			where
				reservation_room.status = \'CHECKIN\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
				and minibar.status <> \'NO_USE\'
			order by
				minibar.name
		');
		//$minibars += $this->minibar_overdue();
		
		sort($minibars);
		if(!URL::get('minibar_id'))
		{
			//$current_minibar = current($minibars);
			$minibar_id = '';//$current_minibar['id'];
		}
		else
		{
			$minibar_id = URL::get('minibar_id');
		}
		//edit below code in hanoi imperial
		if($items = DB::fetch_all('
			select
				hk_product.id,
				'.$field.' norm_quantity,
				0 as quantity,
				hk_product.name_'.Portal::language().' as name,
				hk_product.price
			from
				minibar_product
				inner join hk_product on product_id=hk_product.code
			where
				minibar_id = \''.$minibar_id.'\'
			order by 
				minibar_product.position'
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
			'date'=>date('d/m/Y',time()),
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
}
?>