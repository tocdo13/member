<?php
class EditLaundryInvoiceForm extends Form
{
	function EditLaundryInvoiceForm()
	{
		Form::Form('EditLaundryInvoiceForm');
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_invoice'));
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
		if($this->check())
		{
		  
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
            
			if(isset($_REQUEST['instruction']))
			{
				$special_note = implode(',',$_REQUEST['instruction']);
			}
			else
			{
				$special_note = '';
			}
			$total_before_tax = (System::calculate_number(Url::get('subtotal'))-System::calculate_number(Url::get('total_discount'))+System::calculate_number(Url::get('express')));
			//cap nhat lai thong tin hoa don GIAT LA
			DB::update('housekeeping_invoice',array(
				'last_modifier_id'=>Session::get('user_id'),
				'lastest_edited_time'=>time(),
				'discount',
				'tax_rate',
				'fee_rate'=>Url::get('service_rate'),
				'express_rate',
				'total'=>System::calculate_number(Url::get('grant_total')),
				'total_before_tax'=>$total_before_tax,
				'note',
				'code'=>Url::get('code')?Url::get('code'):'',
				'group_payment'=>Url::check('group_payment')?1:0,
				'special_note'=>$special_note,
                'is_express_rate'=>(Url::get('is_express_rate')?1:0),
                'last_time'=>time(),
                'lastest_user_id'=>User::id()
				),'id=\''.Url::get('id').'\''
			);
			
            
            //cha hieu cho nay de lam j`
            //LaundryInvoiceDB::UpdateProductStatus($time);
			
            $product_description = '';
            //xoa cac ban ghi cu trong housekeeping_invoice_detail
			DB::delete('housekeeping_invoice_detail','invoice_id=\''.Url::get('id').'\'');
			$product_description = '<strong>List Service</strong>:';
			//cap nhat cac product_id vao bang housekeeping_invoice_detail
			foreach($_REQUEST['services'] as $key=>$record)
			{
				if(isset($record['quantity']) and $record['quantity']!='' and $record['quantity']!=0)
				{
				    $record['change_quantity'] =  $record['change_quantity']?System::calculate_number($record['change_quantity']):0;
					$record['invoice_id'] = Url::get('id');
					$record['product_id'] = $key;
					$record['quantity'] = System::calculate_number($record['quantity']+$record['change_quantity']);
                    $record['change_quantity'] = System::calculate_number($record['change_quantity']);
					$record['promotion'] = System::calculate_number($record['promotion']);
					$record['price'] = System::calculate_number($record['price']);
					DB::insert('housekeeping_invoice_detail',$record);
					if($product = DB::fetch('select product_price_list.*,product.name_1,product.name_2 from product_price_list INNER JOIN product ON product.id=\''.$record['product_id'].'\' and product_price_list.product_id=product.id where product_price_list.product_id=\''.$record['product_id'].'\' and product_price_list.department_code = \'LAUNDRY\' and product_price_list.portal_id=\''.PORTAL_ID.'\''))
					{
						$product_description .= ''.$record['product_id'].': quantity: '.$record['quantity'].' change_quantity: '.$record['change_quantity'].' - price: '.$record['price'].' - '
						.' <a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a> promotion: '.$record['promotion'].'<br>';
					}
				}
			}
            $position = DB::fetch('select position from housekeeping_invoice where id='.Url::get('id'),'position');
			$reservation_room = DB::select('reservation_room',DB::fetch('select housekeeping_invoice .reservation_room_id FROM housekeeping_invoice where id='.Url::get('id'),'reservation_room_id'));
            $log_id = System::log('edit','Edit laundry invoice at room '.DB::fetch('select name from room where id=\''.$reservation_room['room_id'].'\'','name'),
            'Code:<a href="?page=laundry_invoice&cmd=edit&id='.$id.'">LD_'.$position.'</a><br>
            Total money:'.System::calculate_number(Url::get('grant_total')).HOTEL_CURRENCY.'<br>
            Reservation code: <a href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'&r_r_id='.$reservation_room['id'].'">'.$reservation_room['reservation_id'].'</a><br>
            <b>Services:</b><br>
            '.$product_description);
            System::history_log('RECODE',$reservation_room['reservation_id'],$log_id);
            System::history_log('LAUNDRY',$id,$log_id);
			Url::redirect_current();
		}
	}	
	function draw()
	{
	    $this -> map=array();
		$row = DB::select('housekeeping_invoice',Url::iget('id'));
		$row['show_time'] = date('H:i\' d/m/Y',$row['time']);
		if($row['lastest_edited_time']){
			$row['lastest_edited_time'] = date('H:i\' d/m/Y',$row['lastest_edited_time']);
		}
		$code = '';
		for($i=0;$i<4-strlen($row['id']);$i++)
		{
			$code .= '0';
		}
		$code .= $row['id'];
		$row['invoice_id'] = $code;
		
		//ten phong
		$sql = '
			select 
				id,name
			from 
				room
			where 
				id=\''.$row['minibar_id'].'\' and room.portal_id=\''.PORTAL_ID.'\'
			order by
				name
		';
		$room = DB::fetch($sql);
		$row['room_name'] = $room['name'];
		$row['room_id'] = $room['id'];
		$reservation_room_id = DB::fetch('select id from reservation_room where id=\''.$row['reservation_room_id'].'\' and room_id=\''.$row['room_id'].'\'','id');
		
		//get tat ca room cho form sua - update ngay 31/12/2009 - updated by ducnm
		require_once 'packages/hotel/includes/php/hotel.php';
		$reservations = Hotel::get_reservation_room();
		$reservation_id_list = array('0'=>'----')+String::get_list($reservations); 
		//danh sach cac product trong hoa don
        
    	$sql = '
			SELECT 
				housekeeping_invoice_detail.product_id as id,
                housekeeping_invoice_detail.quantity - housekeeping_invoice_detail.change_quantity as quantity,
                housekeeping_invoice_detail.change_quantity,
                housekeeping_invoice_detail.price,
                housekeeping_invoice_detail.promotion,
                housekeeping_invoice.net_price
			FROM 
				housekeeping_invoice_detail 
				inner join housekeeping_invoice on housekeeping_invoice.id = invoice_id
			WHERE 
				invoice_id = \''.Url::get('id').'\'
		';
		$invoice_products = DB::fetch_all($sql);
        //System::debug($invoice_products);
        $this->map['net_price_laundry'] ='';
        foreach($invoice_products as $in_id=>$in_value){
            $this->map['net_price_laundry'] = $in_value['net_price'];
        }
		$category = DB::select('product_category','code=\'GL\'');
        
        $sql = '
			SELECT
				product_price_list.product_id as id, 
                product.name_'.Portal::language().' as name, 
                product_price_list.price,
                product_category.code
			FROM
				product_price_list
                INNER JOIN product on product_price_list.product_id = product.id
				INNER JOIN product_category on product_category.id = product.category_id
			WHERE
				product.type=\'SERVICE\'
                and product_price_list.portal_id=\''.PORTAL_ID.'\'
				AND '.IDStructure::direct_child_cond($category['structure_id']).'
                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                --end giap.ln
			ORDER BY
                product_price_list.order_number,
				product_price_list.id asc,
                product_price_list.product_id
		';
		$products = DB::fetch_all($sql);
		$items = array();
		$subtotal = 0;
        $tax1 = 0;
        $fee1 = 0;
		foreach($products as $key=>$value)
		{
			$newkey = substr($key,0,strlen($key)-1);
			$ncheck = substr($key,strlen($key)-1);
			$items[$newkey]['product_key'] = $newkey;
			$items[$newkey]['product_name'] = $value['name'];
			$items[$newkey]['child'][$value['code']]['product'] = $key;
			if(isset($invoice_products[$key]))
			{
				$items[$newkey]['total_'.$ncheck] = $invoice_products[$key]['price']*($invoice_products[$key]['quantity'] + $invoice_products[$key]['change_quantity']-$invoice_products[$key]['promotion']);
				$items[$newkey]['child'][$value['code']]['price'] = System::display_number($invoice_products[$key]['price']);
				$items[$newkey]['child'][$value['code']]['quantity'] = $invoice_products[$key]['quantity'];
                $items[$newkey]['child'][$value['code']]['change_quantity'] = $invoice_products[$key]['change_quantity'];
				$items[$newkey]['child'][$value['code']]['promotion'] = $invoice_products[$key]['promotion'];
			}
			else
			{
				$items[$newkey]['child'][$value['code']]['price'] = System::display_number($value['price']);
				$items[$newkey]['child'][$value['code']]['quantity'] = '';
                $items[$newkey]['child'][$value['code']]['change_quantity'] = '';
				$items[$newkey]['child'][$value['code']]['promotion'] = '';
				$items[$newkey]['total_'.$ncheck] = 0;
			}
			if(isset($items[$newkey]['total']))
			{
				$items[$newkey]['total'] += $items[$newkey]['total_'.$ncheck];
			}else
			{
				$items[$newkey]['total'] = $items[$newkey]['total_'.$ncheck];
			}
            if($this->map['net_price_laundry'] == 1)
            {
                $subtotal += $items[$newkey]['total_'.$ncheck];
                $subtotal += $items[$newkey]['total_'.$ncheck]/(1 + $row['tax_rate']*0.01)/(1 + $row['fee_rate']*0.01);                    
            }
            else
            {
                $subtotal += $items[$newkey]['total_'.$ncheck];
            }
		
            
		}
        
		$sql = '
					select 
						code as id, code, id as category, structure_id
                        ,case
                            when '.Portal::language().' = 1
                            then name
                            when '.Portal::language().' = 2
                            then name_en
                            else name
                         end name
					from
						product_category
					where
						'.IDStructure::direct_child_cond($category['structure_id']).'
					order by
						product_category.structure_id
				';
		if(!($categories = DB::fetch_all($sql)))
		{
			$categories = array();
		}
		foreach($categories as $c=>$k)
		{
			foreach($items as $key=>$value)
			{
				if(!isset($value['child'][$k['code']]))
				{
					$items[$key]['child'][$k['code']] = array();
				}else
				{
					unset($items[$key]['child'][$k['code']]);
					$items[$key]['child'][$k['code']] = $value['child'][$k['code']];
				}
			}
			
		}
		$total_discount = round($subtotal*$row['discount']/100,2);
		$row['total_discount'] = System::display_number($total_discount);
		
		$row['subtotal'] = System::display_number($subtotal);
        //System::debug($row['subtotal'] );
		$subtotal  -= $total_discount;
		
		$express = round($subtotal*$row['express_rate']/100,2);
		$row['express'] = System::display_number($express);
		
		$service_charge = round(($subtotal+$express)*$row['fee_rate']/100,2);
		$row['service_charge'] = System::display_number($service_charge);
		
		$tax = round(($subtotal+$express+$service_charge)*$row['tax_rate']/100,2);
		$row['tax'] = System::display_number($tax);

		$row['grant_total'] = System::display_number($subtotal+$express+$service_charge+$tax);
		
		$row['REGULAR_SERVICE']=substr_count($row['special_note'],'REGULAR_SERVICE')>0?1:0;
		$row['SHIRTS_ON_HANGER']=substr_count($row['special_note'],'SHIRTS_ON_HANGER')>0?1:0;
		$row['SHIRTS_FOLDED']=substr_count($row['special_note'],'SHIRTS_FOLDED')>0?1:0;
		$row['NO_STARCH']=substr_count($row['special_note'],'NO_STARCH')>0?1:0;
		$row['LIGHT_STARCH']=substr_count($row['special_note'],'LIGHT_STARCH')>0?1:0;
		$express_rate = $row['express_rate']?$row['express_rate']:0;
        
        $_REQUEST['is_express_rate'] = $row['is_express_rate'];
        $this->map['last_time'] = time();
		$this->parse_layout('edit',$this->map+$row+array(
			'reservations'=>$reservations,
			'reservation_room_id_list'=>$reservation_id_list,
			'reservation_room_id'=>$reservation_room_id,
			'items'=>$items,
			'tax_rate'=>$row['tax_rate'],
			'service_rate'=>$row['fee_rate'],
			'express_rate'=>$row['express_rate'],//LAUNDRY_EXPRESS_RATE
			'categories'=>$categories
		));
	}
}
?>