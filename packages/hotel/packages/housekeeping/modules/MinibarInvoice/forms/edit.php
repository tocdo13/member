<?php
class EditMinibarInvoiceForm extends Form
{
	function EditMinibarInvoiceForm()
	{
		Form::Form('EditMinibarInvoiceForm');
		$this->add('total',new FloatType(true,'invalid_total','0','100000000000')); 
		$this->add('minibar_id',new IDType(false,'invalid_minibar_id','minibar'));
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function on_submit()
	{
        require_once 'packages/hotel/includes/php/product.php';
		if($this->check())
		{   
			$id = URL::get('id');
            
            $sql = "SELECT id,reservation_room_id FROM  housekeeping_invoice WHERE id=".Url::get('id');
            $result = DB::fetch($sql);
            $reservation_room_id = $result['reservation_room_id'];
            
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
    				reservation_room.id =\''.$reservation_room_id.'\'
                    AND reservation_room.status=\'CHECKOUT\'
    			order by 
    				room.name
    			'; 
            $hotel_reservation_room_id = DB::fetch($sql);
            //System::debug($hotel_reservation_room_id) ;  exit();
            if(!empty($hotel_reservation_room_id))
            {
                $this->error('','Phòng đã checkout không thể chỉnh sửa!');
                return false;                    
            }
            
            DB::update('housekeeping_invoice', 
				array(
					'code'=>Url::get('code')?Url::get('code'):'',
					'note'=>Url::get('note')?Url::get('note'):'',   
					'minibar_id', 
					'last_modifier_id'=>Session::get('user_id'),
					'lastest_edited_time'=>time(),
					'group_payment'=>Url::check('group_payment')?1:0,
					'total'=>System::calculate_number(URL::get('total')),
					'total_before_tax'=>(System::calculate_number(URL::get('total_before_tax')) - System::calculate_number(URL::get('total_discount'))),
					'tax_rate'=>System::calculate_number(URL::get('tax_rate')),
					'fee_rate'=>System::calculate_number(URL::get('fee_rate')),
					'discount'=>Url::get('discount')?System::calculate_number(URL::get('discount')):0,
                    'last_time'=>time(),
                    'lastest_user_id'=>User::id()
				),'id=\''.$id.'\''
			);
            $product_description = '';
			if(isset($_REQUEST['items']))
			{
				foreach($_REQUEST['items'] as $key=>$record)
				{
					$record['quantity'] = $record['quantity']+$record['change_quantity']?$record['quantity']+$record['change_quantity']:0;
                    $record['change_quantity'] = $record['change_quantity']?$record['change_quantity']:0;
                    // $record['reason_for_change'] = $record['reason_for_change']?$record['reason_for_change']:'';
					//unset($record['total_consumed']);
                    $record['price'] = str_replace(',','',($record['price']));
					$record['invoice_id'] = $id;
					$record['product_id'] = $key;
					if($old = DB::select('housekeeping_invoice_detail','invoice_id=\''.$id.'\' and product_id=\''.$key.'\''))
					{
						DB::update('housekeeping_invoice_detail',$record,'invoice_id=\''.$id.'\' and product_id=\''.$key.'\'');
						if(DB::select('minibar_product','minibar_id=\''.URL::get('minibar_id').'\' and product_id=\''.$key.'\''))
						{
							DB::query('update minibar_product set quantity=quantity+('.($old['quantity']-$record['quantity']).') where minibar_id=\''.URL::get('minibar_id').'\' and product_id=\''.$key.'\'');
						}
						else
						{
							DB::insert('minibar_product',
								array(
									'minibar_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>$record['price']
								)
							);
						}
					}
					else
					{
						DB::insert('housekeeping_invoice_detail',$record);
						if(DB::select('minibar_product','product_id=\''.$key.'\''))
						{
							DB::query('update minibar_product set quantity=quantity-'.$record['quantity'].' where minibar_id=\''.URL::get('minibar_id').'\' and product_id=\''.$key.'\'');
						}
						else
						{
							DB::insert('minibar_product',
								array(
									'minibar_id',
									'product_id'=>$key,
									'quantity'=>-$record['quantity'],
									'price'=>$record['price']
								)
							);
						}
					}
                    if($product=DB::fetch('select product_price_list.*,product.name_1,product.name_2 from product_price_list INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id where product_price_list.product_id=\''.$record['product_id'].'\' and product_price_list.department_code = \'MINIBAR\' and product_price_list.portal_id=\''.PORTAL_ID.'\'')){
                        $product_description .= 'Quantity'.$record['quantity'].' -  Price: '.$record['price'].' - '
                                                    .'Product Code: '.$product['product_id'].' Product Name: '.$product['name_'.Portal::language()].'<br>';
                    }
                    
				}
			}
            $warehouse_id = DB::fetch('Select * from portal_department 
                                                inner join warehouse w1 on portal_department.warehouse_id = w1.id 
                                                where portal_department.department_code = \'MINIBAR\' and portal_department.portal_id = \''.PORTAL_ID.'\' ','warehouse_id');
            if($warehouse_id)
            {
                DeliveryOrders::get_delivery_orders(Url::iget('id'),'MINIBAR',$warehouse_id);
            }
            $reservation = DB::fetch('select 
                                        reservation_room.id as reservation_room_id
                                        ,reservation_room.room_id
                                        ,reservation_room.reservation_id 
                                    from 
                                        reservation_room
                                        inner join housekeeping_invoice on housekeeping_invoice.reservation_room_id=reservation_room.id
                                    WHERE
                                        housekeeping_invoice.id='.$id.'
                                    ');
            $position = DB::fetch('select position from housekeeping_invoice where id='.$id,'position');
            $log_id = System::log('EDIT','Edit minibar invoice at room '.DB::fetch('select name from room where id=\''.$reservation['room_id'].'\'','name'),
                        'Code:<a href="?page=minibar_invoice&cmd=edit&id='.$id.'"> MN_'.$position.'</a><br>
                        Total money:'.URL::get('total').HOTEL_CURRENCY.'<br>
                        Reservation code: <a href="?page=reservation&cmd=edit&id='.$reservation['reservation_id'].'&r_r_id='.$reservation['reservation_room_id'].'">'.$reservation['reservation_id'].'</a><br>
                        <b>Services:</b><br>
                        '.$product_description);
            System::history_log('RECODE',$reservation['reservation_id'],$log_id);
            System::history_log('MINIBAR',$id,$log_id);  
			URL::redirect_current();
		}
	}
	function draw()
	{
		$this->map['unlimited'] = MINIBAR_IMPORT_UNLIMIT;
		if(!URL::get('minibar_id'))
		{
			$minibar_id = DB::fetch('select id from minibar ','id');
		}
		else
		{
			$minibar_id = URL::get('minibar_id');
		}
		$row = DB::fetch('
			select
				id,code,note,
				minibar_id,
				time,lastest_edited_time,net_price,
				tax_rate, fee_rate,reservation_room_id as reservation_room_id,discount,position
			from
				housekeeping_invoice
			where 
				id=\''.URL::get('id').'\''
		);
        $this->map['net_price_minibar'] = $row['net_price'];
		$row['time'] = date('H:i\' d/m/Y',$row['time']);
		if($row['lastest_edited_time']){
			$row['lastest_edited_time'] = date('H:i\' d/m/Y',$row['lastest_edited_time']);
		}
		$_REQUEST['code'] = $row['code']?$row['code']:'';
		$_REQUEST['note'] = $row['note']?$row['note']:'';
		$row['total_discount'] = 0;
		$row['total_discount'] = System::display_number($row['total_discount']);
        //System::debug($row);
		$row['room_name'] = DB::fetch('select room.name from room inner join reservation_room on reservation_room.room_id = room.id where reservation_room.id = '.$row['reservation_room_id'].'','name');
		$sql = '
			select
				minibar_product.product_id as id,
				norm_quantity,
				(housekeeping_invoice_detail.quantity - housekeeping_invoice_detail.change_quantity) as quantity,
                housekeeping_invoice_detail.change_quantity,
				product.name_'.Portal::language().' as name,
                
				CASE WHEN housekeeping_invoice_detail.price >0 THEN housekeeping_invoice_detail.price ELSE product_price_list.price END AS price 
			from
				minibar_product
				INNER JOIN product on minibar_product.product_id = product.id
				inner join product_price_list on product_price_list.product_id=product.id and product_price_list.department_code=\'MINIBAR\'
				LEFT OUTER JOIN housekeeping_invoice_detail on housekeeping_invoice_detail.product_id = minibar_product.product_id  and housekeeping_invoice_detail.invoice_id = '.URL::get('id').'
				LEFT OUTER JOIN housekeeping_invoice on housekeeping_invoice.id = housekeeping_invoice_detail.invoice_id
			where
				minibar_product.minibar_id = \''.$row['minibar_id'].'\'	
                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                --end giap.ln
			order by minibar_product.position';
		$i=1;
		if(!($items = DB::fetch_all($sql))) $items = array();
		foreach($items as $id=>$item)
		{
			if(isset($_REQUEST['items'][$id]))
			{
				$items[$id]['quantity'] = $_REQUEST['items'][$id]['quantity'];
			}
			$items[$id]['no'] = $i++;
			$items[$id]['price'] = ($items[$id]['price']);
		}
		$this->map['items'] = $items;
		$this->map['num_product'] = $i-1;
        $this->map['last_time'] = time();
        //System::debug($this->map);
		$this->parse_layout('edit',$this->map+$row);
	}
}
?>
