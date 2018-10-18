<?php
class AddEquipmentInvoiceForm extends Form
{
	function AddEquipmentInvoiceForm()
	{
		Form::Form('AddEquipmentInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0.00000000001','100000000000'));
		$this->add('room_id',new IDType(false,'invalid_room_id','room'));
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
					and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
					and room.id=\''.URL::get('room_id').'\'
			';
			if(!($reservation = DB::fetch($sql)))
			{
				$this->error('room_id','no_reservation');
				return;
			}			
			$row = array(
					'code'=>Url::get('code')?Url::get('code'):'',
					'note'=>Url::get('note')?Url::get('note'):'',
					'type'=>'EQUIP',
					'reservation_room_id'=>$reservation['reservation_room_id'], 
					'minibar_id'=>Url::get('room_id'), 
					'user_id'=>Session::get('user_id'),
					'last_modifier_id'=>Session::get('user_id'),
					'total'=>System::calculate_number(URL::get('total')),
					'fee_rate'=>System::calculate_number(URL::get('fee_rate',0)),
					'tax_rate'=>System::calculate_number(URL::get('tax_rate',0)),
					'total_before_tax'=>System::calculate_number(URL::get('total_before_tax')),
					'time'=>time(),
					'group_payment'=>Url::check('group_payment')?1:0,
					'portal_id'=>PORTAL_ID,
                    'last_time'=>time(),
                    'lastest_user_id'=>User::id()
				);
			$invoice_id = DB::insert('housekeeping_invoice',$row);
            $pos = DB::fetch('SELECT max(position) as position FROM housekeeping_invoice WHERE housekeeping_invoice.portal_id=\''.PORTAL_ID.'\' and type =\'EQUIP\'');
            if(($pos['position']!=''))
            {
                $position = $pos['position'] + 1;
            }
			else
            {
                $position = 1 ;
            }
			DB::update('housekeeping_invoice',array('position'=>$position),'id='.$invoice_id);
			$content = 'Use services of minibar <a href="?page=minibar_invoice&id='.$invoice_id.'">#'.$invoice_id.'</a> total='.URL::get('total').'$';
			$product_description = '';
			//Chi tiet hoa don
			if(isset($_REQUEST['equipment']) and is_array($_REQUEST['equipment']))
			{				
				foreach($_REQUEST['equipment'] as $key=>$record)
				{
				    unset($record['in_room_quantity']);
					if(($record['quantity']>0))
					{
						$content.='<br>'.$record['quantity'].' '.$key.' '.$record['price'].'$';
						$record['price'] = System::calculate_number($record['price']);
						$record['invoice_id'] = $invoice_id;
						unset($record['amount']);
						unset($record['vnd_amount']);
						$detail_id = DB::insert('housekeeping_invoice_detail',$record);
						//Update lai so luong trong thiet bi phong
						
						DB::query('
							update housekeeping_equipment 
							set damaged_quantity = damaged_quantity+'.intval($record['quantity']).'
							where 
								room_id=\''.Url::get('room_id').'\' and
								product_id=\''.$record['product_id'].'\'
						');
						$id = DB::insert('housekeeping_equipment_damaged',array(
							'room_id',
							'product_id'=>$record['product_id'],
							'quantity'=>$record['quantity'],
							'note'=>Portal::language('from_equipment_invoice'),
							'type'=>'DAMAGED',
							'time'=>time(),
							'housekeeping_invoice_id'=>$invoice_id,
                            'portal_id'=>PORTAL_ID
						));
						$product_description .= '
							product_id: '.$record['product_id'].', 
							quantity: '.$record['quantity'].', 
							type: DAMAGED,
							housekeeping_invoice_id: '.$invoice_id.'<br>
						';
						
                        /*
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
									and product_id = \''.$record['product_id'].'\' 
									and from_table = \'hk_product\'';
	
						if(!DB::exists($sql)){
							$status['time'] = $date;
							$status['product_id'] = $record['product_id'];
							$status['from_table'] = 'hk_product';
							$status['status'] = 'avaiable';
							DB::insert('hk_product_status',$status);
						}					
						// end
                        */
					}
				}
			}
           
			$log_id = System::log('add','Add compensation invoice #EQ_'.$position.' at room '.DB::fetch('select name from room where id=\''.Url::get('room_id').'\'','name'),
						'Code: <a href="?page=equipment_invoice&cmd=edit&id='.$id.'">'.$id.'</a><br>
                         reservation code: '.$reservation['reservation_id'].'
						Total money: '.URL::get('total').HOTEL_CURRENCY.'<br>
						<b>Services: </b><br>
						'.$product_description,$position);
            System::history_log('RECODE',$reservation['reservation_id'],$log_id);
            System::history_log('EQUIPMENT',$id,$log_id);
            if(Url::get('fast')){
                echo Portal::language('add').' '.Portal::language('equipment').' '.Portal::language('successful'); die();
            }else{
                echo '<script>
                       if(window.opener)
                       {
                          window.opener.history.go(0);
						  window.close();
                       }
                       window.setTimeout("location=\''.URL::build_current(array('just_edited_id'=>$id)).'\'",0);
                    </script>';
            }
             
            exit();
		}
        URL::redirect_current();
	}
	function draw()
	{
		$rooms = DB::fetch_all('
			select
				room.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
			from
				room
				inner join reservation_room on reservation_room.room_id = room.id
				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
			where
				reservation_room.status = \'CHECKIN\'
				and room_status.status = \'OCCUPIED\' and room.portal_id=\''.PORTAL_ID.'\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by
				room.name
		');
		$this->map = array();
		if($room_id = Url::iget('room_id') or (Url::get('reservation_room_id') and $room_id = DB::fetch('select room_id from reservation_room where id = '.Url::iget('reservation_room_id').'','room_id')))
		{
			if(Url::get('reservation_room_id') and !isset($_REQUEST['room_id'])){
				$_REQUEST['room_id'] = $room_id;
			}
			$sql = 'select 
						product.*, 
                        product.id as code, 
                        product_price_list.price, 
                        product.name_'.Portal::language().' as name,
                        housekeeping_equipment.quantity - housekeeping_equipment.damaged_quantity as quantity  
					from 	
                        product_price_list
						INNER JOIN product ON product_price_list.product_id = product.id
						inner join housekeeping_equipment on housekeeping_equipment.product_id = product.id AND housekeeping_equipment.portal_id=\''.PORTAL_ID.'\'
                        INNER JOIN unit ON unit.id = product.unit_id
                        inner join product_category on product.category_id = product_category.id
					where
						housekeeping_equipment.room_id = \''.$room_id.'\'
                        AND product_price_list.portal_id = \''.PORTAL_ID.'\'
                        --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                        AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                        AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                        --end giap.ln
                    ORDER BY     
                        product.name_'.Portal::language().' ASC
				';
			$this->map['equipment'] = DB::fetch_all($sql);
		}
		$this->parse_layout('add',$this->map+
			array(
			'tax_rate'=>0,
			'time'=>date('H:i\' d/m/Y',time()),
			'room_id_list'=>array('0'=>Portal::language('select_room'))+String::get_list($rooms)
			)
		);
	}
}
?>
