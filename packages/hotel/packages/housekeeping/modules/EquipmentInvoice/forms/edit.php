<?php
class EditEquipmentInvoiceForm extends Form
{
	function EditEquipmentInvoiceForm()
	{
		Form::Form('EditEquipmentInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000')); 
		$this->add('room_id',new IDType(false,'invalid_minibar_id','room'));
		$this->link_js('packages/core/includes/js/calendar.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function on_submit()
	{
		if($this->check())
		{  
          
          
			$sql = '
				select
					room.*, reservation_room.id as reservation_room_id, reservation_room.reservation_id as reservation_id
				from
					room
					inner join reservation_room on reservation_room.room_id = room.id
					inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
				where
					reservation_room.status=\'CHECKIN\'
					and room_status.status = \'OCCUPIED\'
					and room_status.IN_DATE = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
					and room.id=\''.URL::get('room_id').'\'
			';
			if(!($reservation = DB::fetch($sql)))
			{
				$this->error('room_id','Phòng đã checkout không thể chỉnh sửa!');
				return;
			}
			$row = array(
					'code'=>Url::get('code')?Url::get('code'):'',
					'note'=>Url::get('note')?Url::get('note'):'',
					'minibar_id'=>Url::get('room_id'),
					'reservation_room_id'=>$reservation['reservation_room_id'],  
					'last_modifier_id'=>Session::get('user_id'),
					'lastest_edited_time'=>time(),
					'total'=>System::calculate_number(URL::get('total')),
					'total_before_tax'=>System::calculate_number(URL::get('total_before_tax')),
					'tax_rate'=>System::calculate_number(URL::get('tax_rate')),
					'group_payment'=>Url::check('group_payment')?1:0,
                    'last_time'=>time(),
                    'lastest_user_id'=>User::id()
				);
			$id = URL::get('id');
            $position = DB::fetch('select position from housekeeping_invoice where id='.$id);
			DB::update('housekeeping_invoice',$row,'id=\''.URL::get('id').'\'');
			$product_description = '';
			if(isset($_REQUEST['equipment']))
			{
				$update_product = '\'0\'';
				$invoice_id = URL::get('id');
				foreach($_REQUEST['equipment'] as $key=>$record)
				{
					unset($record['amount']);
					$update_product .= ',\''.$record['product_id'].'\'';
					$record['price'] = System::calculate_number($record['price']);
					$record['quantity'] = $record['quantity'] + $record['change_quantity'] ;
					$record['invoice_id'] = URL::get('id');
					//Neu co ban ghi cu
                    if($old = DB::select('housekeeping_invoice_detail','invoice_id=\''.$invoice_id.'\' and product_id=\''.$record['product_id'].'\''))
					{
						DB::update('housekeeping_invoice_detail',$record,'invoice_id=\''.$invoice_id.'\' and product_id=\''.$record['product_id'].'\'');
						DB::query('
							update housekeeping_equipment 
							set damaged_quantity = damaged_quantity - ('.$old['quantity'].'-'.intval($record['quantity']).')
							where 
								room_id=\''.Url::get('room_id').'\' and
								product_id=\''.$record['product_id'].'\'
						');
						$sql = '
							update housekeeping_equipment_damaged 
							set quantity='.intval($record['quantity']).'
							where 
								room_id=\''.Url::get('room_id').'\' and
								product_id=\''.$record['product_id'].'\'
								and housekeeping_invoice_id = \''.$invoice_id.'\'
						';
						DB::query($sql);
					}
                    else
                    {
						$detail_id = DB::insert('housekeeping_invoice_detail',$record);
						DB::query('
							update housekeeping_equipment 
							set damaged_quantity = damaged_quantity+'.intval($record['quantity']).'+'.intval($record['change_quantity']).'
							where 
								room_id=\''.Url::get('room_id').'\' and
								product_id=\''.$record['product_id'].'\'
						');
						DB::insert('housekeeping_equipment_damaged',array(
							'room_id',
							'product_id'=>$record['product_id'],
							'quantity'=>$record['quantity'] + $record['change_quantity'],
							'note'=>Portal::language('from_equipment_invoice'),
							'type'=>'DAMAGED',
							'time'=>time(),
							'housekeeping_invoice_id'=>$invoice_id,
                            'portal_id'=>PORTAL_ID
						));
					}
					$product_description .= '
						product_id: '.$record['product_id'].', 
						quantity: '.$record['quantity'].', 
						type: DAMAGED,
						housekeeping_invoice_id: '.$invoice_id.'<br>
					';				
				}
				//Update cho nhung ban ghi bi xoa hoan toan
				//Update housekeeping_equipment_damaged 
				$sql = 'select 
							* 
						from 
							housekeeping_equipment_damaged 
						where 
							housekeeping_invoice_id ='.$invoice_id.'
							and housekeeping_equipment_damaged.product_id not in ('.$update_product.') 
							
					';
				$deletes = DB::fetch_all($sql);
				if($deletes){
					// Tra lai so luong ban dau
					foreach($deletes as $key=>$value){
						$sql = '
							update 
								housekeeping_equipment
							set
								quantity = quantity+'.$value['quantity'].'
							where
								housekeeping_equipment.room_id = \''.$value['room_id'].'\'
								and product_id = \''.$value['product_id'].'\'
						';
						DB::query($sql);
						DB::delete_id('housekeeping_equipment_damaged',$key);
					}
				}
				//Update housekeeping_invoice_detail
				$cond = '	invoice_id ='.$invoice_id.'
							and housekeeping_invoice_detail.product_id not in ('.$update_product.') 
					';
				DB::delete('housekeeping_invoice_detail',$cond);
			} 
			$log_id = System::log('edit','Edit compensation invoice #EQ_'.$position['position'].' at room '.DB::fetch('select name from room where id=\''.Url::get('room_id').'\'','name'),
						'Code: <a href="?page=equipment_invoice&cmd=edit&id='.$id.'">'.$id.'</a><br>
                        reservation code: '.$reservation['reservation_id'].'
						Total money: '.URL::get('total').HOTEL_CURRENCY.'<br>
						<b>Services: </b><br>
						'.$product_description,$position['position']);
            System::history_log('RECODE',$reservation['reservation_id'],$log_id);
            System::history_log('EQUIPMENT',$id,$log_id);
			URL::redirect_current();
		}
	}
	function draw()
	{
		//FROM_UNIXTIME(time) as date_, ????
		$row = DB::fetch('
			select
				id,
				minibar_id,				
				time as time_,lastest_edited_time,
				tax_rate, fee_rate,note
			from
				housekeeping_invoice
			where 
				id=\''.URL::get('id').'\''
		);
		$this->map['date'] = date('H:i\' d/m/Y',$row['time_']);
		if($row['lastest_edited_time'])
		{
			$this->map['lastest_edited_time'] = date('H:i\' d/m/Y',$row['lastest_edited_time']);
		}
		else
		{
			$this->map['lastest_edited_time'] = 0;
		}
		$invoice_id = URL::get('id');
		$room_id = DB::fetch('
			select
				room_id as minibar_id
			from
				housekeeping_invoice
				inner join reservation_room on housekeeping_invoice.reservation_room_id = reservation_room.id
			where
				housekeeping_invoice.id = '.$invoice_id,'minibar_id');
		$sql = '
			select
				housekeeping_equipment.product_id as id,
				product.name_'.Portal::language().' as name,
                housekeeping_equipment.quantity as in_room_quantity,
				COALESCE(housekeeping_invoice_detail.price,product_price_list.price) as price,
				COALESCE(housekeeping_invoice_detail.quantity,0) as quantity, -- housekeeping_invoice_detail.change_quantity as quantity,
				COALESCE(housekeeping_invoice_detail.change_quantity,0) as change_quantity,
                COALESCE(housekeeping_invoice_detail.amount,0) as amount
			from
				housekeeping_equipment
				inner join product on product.id = housekeeping_equipment.product_id
                -- KimTan comment dong duoi lay minibar_product ko co tac dung di ca len ko ra
                --left join minibar_product on product.id = minibar_product.product_id
                inner join product_price_list on product_price_list.product_id = product.id and product_price_list.portal_id=\''.PORTAL_ID.'\'
				--end
                left outer join 
				(	
					select
						housekeeping_invoice_detail.product_id,
						housekeeping_invoice_detail.price,
						housekeeping_invoice_detail.quantity - COALESCE(housekeeping_invoice_detail.change_quantity,0) as quantity,
                        COALESCE(housekeeping_invoice_detail.change_quantity,0) as change_quantity ,
						housekeeping_invoice_detail.quantity*housekeeping_invoice_detail.price as amount
					from
						housekeeping_invoice_detail
						inner join housekeeping_invoice on housekeeping_invoice_detail.invoice_id = housekeeping_invoice.id
					where
						housekeeping_invoice.id = '.$invoice_id.' and housekeeping_invoice.portal_id=\''.PORTAL_ID.'\'
				) housekeeping_invoice_detail on housekeeping_invoice_detail.product_id = product.id
			where
				housekeeping_equipment.room_id = '.$room_id.'
                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                --end giap.ln
				';
		
        if($items = DB::fetch_all($sql)){
			$this->map['equipment'] = $items;
		}else{
			$_REQUEST['room_id'] = $row['minibar_id'];
		}
		$row['room_id'] = $row['minibar_id'];
		$_REQUEST['room_id'] = $row['room_id'];
        $_REQUEST['code'] = DB::fetch('select code from housekeeping_invoice where id = '.$invoice_id,'code');
		$row['room_name'] = DB::fetch('select room.name as room_name from room where id='.$row['room_id'].'','room_name');
		$this->map['last_time'] = time();
        $this->parse_layout('edit',$this->map+$row);
	}
}
?>