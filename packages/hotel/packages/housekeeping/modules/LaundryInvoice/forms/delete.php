<?php
class DeleteLaundryInvoiceForm extends Form
{
	function DeleteLaundryInvoiceForm()
	{
		Form::Form("DeleteLaundryInvoiceForm");
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_invoice'));
	}
	function on_submit()
	{
		if($this->check() and Url::get('confirm_delete'))
		{
			$id = Url::iget('id');
			$row = DB::select('housekeeping_invoice',$id);
			if(!DB::exists('SELECT ID FROM RESERVATION_ROOM WHERE ID = '.$row['reservation_room_id'].' AND STATUS=\'CHECKOUT\'')){
			    if(DB::exists('
                            SELECT
                                mice_invoice_detail.invoice_id as id,
                                housekeeping_invoice.position as hk_id,
                                mice_invoice_detail.mice_invoice_id
                            FROM
                                mice_invoice_detail
                                INNER JOIN housekeeping_invoice on housekeeping_invoice.id = mice_invoice_detail.invoice_id
                            WHERE
                                housekeeping_invoice.id =\''.$id.'\' and mice_invoice_detail.type = \'LAUNDRY\'
                                
                '))
                {
                    $this->error('','Hóa đơn LAUNDRY đã được tạo bill không được xóa!');
                    return false;                 
                }
                if(DB::exists('
                                    SELECT
                                        traveller_folio.invoice_id as id,
                                        housekeeping_invoice.position as hk_id,
                                        traveller_folio.folio_id
                                    FROM
                                        traveller_folio
                                        INNER JOIN housekeeping_invoice on housekeeping_invoice.id = traveller_folio.invoice_id
                                    WHERE
                                        housekeeping_invoice.id =\''.$id.'\' and traveller_folio.type = \'LAUNDRY\'
                                        
                '))
                {
                    $this->error('','Hóa đơn LAUNDRY đã được tạo folio không được xóa!');
                    return false;          
                }
				DB::delete('housekeeping_invoice','id=\''.Url::get('id').'\'');
				DB::delete('housekeeping_invoice_detail','invoice_id=\''.Url::get('id').'\'');
                $log_id = System::log('DELETE','Delete Laundry Invocie LD_'.$row['position'],'Delete Laundry Invocie LD_'.$row['position'],$id);
                $reservation = DB::fetch('select reservation_id from reservation_room where id='.$row['reservation_room_id'],'reservation_id');
                System::history_log('RECODE',$reservation,$log_id);
                System::history_log('LAUNDRY',$id,$log_id);
				Url::redirect_current();
			}else{
				echo  '
					<script>alert("'.Portal::language('you_have_no_right_to_delete').'");window.location="'.Url::build_current().'";</script>;
				';
				exit();
			}
		}
	}
	function draw()
	{
        $row = DB::select('housekeeping_invoice',Url::get('id'));
		$row['date'] = date('d/m/Y',$row['time']);
		$row['hour'] = date('h:i A',$row['time']);
		
		//tao so hoa don
		$code = '';
		for($i=0;$i<4-strlen($row['id']);$i++)
		{
			$code .= '0';
		}
		$code .= $row['id']; $row['invoice_id'] = $code;
		//ten phong
		DB::query('
			select 
				id ,name
			from 
				room
			where 
				id=\''.$row['minibar_id'].'\' and portal_id=\''.PORTAL_ID.'\'
		');
		$room = DB::fetch();
		$row['room_name'] = $room['name'];
		$row['room_id'] = $room['id'];
		//danh sach reservation
		$sql = '
			select
				reservation_room.id
				,CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as customer_name
			from 
				reservation_room 
				left outer join traveller on traveller.id = reservation_room.traveller_id
			where 
				reservation_room.id=\''.$row['reservation_room_id'].'\' and reservation_room.room_id=\''.$row['room_id'].'\'';
		$reservation = DB::fetch($sql);
		$row['customer_name'] = $reservation['customer_name']; 
		//danh sach cac product trong hoa don
		$sql = '
			select 
				product_id as id
				,quantity
				,promotion
				,housekeeping_invoice_detail.price
				,product_category.code
				,product.name_'.Portal::language().' as name
				,product.category_id
			from 
				housekeeping_invoice_detail 
				inner join housekeeping_invoice on housekeeping_invoice.id = invoice_id
				inner JOIN product on product.id = housekeeping_invoice_detail.product_id
				inner JOIN product_category on product.category_id = product_category.id
			where 
				invoice_id = \''.Url::get('id').'\'
		';
		$invoice_products = DB::fetch_all($sql);
		//danh sach tat ca product
		$category = DB::select('product_category','code=\'GL\'');
        
        
        $sql = '
			select 
				product_price_list.product_id as id, 
                product.name_'.Portal::language().' as name, 
                product_price_list.price,
                product_category.code
			from
				product_price_list
                inner join product on product_price_list.product_id = product.id
				inner join product_category on product.category_id = product_category.id
			where
				product.type=\'SERVICE\' 
                and '.IDStructure::child_cond($category['structure_id']).'
				and product_price_list.status <> \'NO_USE\'
                and product_price_list.department_code = \'LAUNDRY\'
                and product_price_list.portal_id=\''.PORTAL_ID.'\'
			order by
				product_price_list.order_number asc,
                product_price_list.product_id
		';
		$products = DB::fetch_all($sql);
		$items = array();
		$charge_unit = HOTEL_CURRENCY;
		$subtotal = 0;
		foreach($products as $key=>$value)
		{
			$newkey = substr($key,0,strlen($key)-1);
			$ncheck = substr($key,strlen($key)-1);
			$items[$newkey]['product_key'] = $newkey;
			$items[$newkey]['product_name'] = $value['name'];
			$items[$newkey]['child'][$value['code']]['product'] = $key;
			if(isset($invoice_products[$key]))
			{
				$items[$newkey]['total_'.$ncheck] = $invoice_products[$key]['price']*($invoice_products[$key]['quantity']-$invoice_products[$key]['promotion']);
				$items[$newkey]['child'][$value['code']]['price'] = System::display_number_report($invoice_products[$key]['price']);
				$items[$newkey]['child'][$value['code']]['quantity'] = $invoice_products[$key]['quantity'];
				$items[$newkey]['child'][$value['code']]['promotion'] = $invoice_products[$key]['promotion'];
			}
			else
			{
				$items[$newkey]['child'][$value['code']]['price'] = System::display_number_report($value['price']);
				$items[$newkey]['child'][$value['code']]['quantity'] = '';
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
			$subtotal += $items[$newkey]['total_'.$ncheck];
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
		$row['total_discount'] = System::display_number_report($total_discount);

		$row['subtotal'] = System::display_number_report($subtotal);
		$subtotal  -= $total_discount;
		
		$express = round($subtotal*$row['express_rate']/100,2);
		$row['express'] = System::display_number_report($express);
		$service_charge = round(($subtotal+$express)*$row['fee_rate']/100,2);
		$row['service_charge'] = System::display_number_report($service_charge);
		$tax = round(($subtotal+$express+$service_charge)*$row['tax_rate']/100,2);
		$row['tax'] = System::display_number_report($tax);
		$row['grant_total'] = System::display_number_report($subtotal+$express+$service_charge+$tax);
		
		$row['REGULAR_SERVICE']=substr_count($row['special_note'],'REGULAR_SERVICE')>0?1:0;
		$row['SHIRTS_ON_HANGER']=substr_count($row['special_note'],'SHIRTS_ON_HANGER')>0?1:0;
		$row['SHIRTS_FOLDED']=substr_count($row['special_note'],'SHIRTS_FOLDED')>0?1:0;
		$row['NO_STARCH']=substr_count($row['special_note'],'NO_STARCH')>0?1:0;
		$row['LIGHT_STARCH']=substr_count($row['special_note'],'LIGHT_STARCH')>0?1:0;
		$this->parse_layout('delete',$row+array(
			'items'=>$items,
			'tax_rate'=>$row['tax_rate'],
			'service_rate'=>$row['fee_rate'],
			'express_rate'=>$row['express_rate'],
			'charge_unit'=>$charge_unit,
			'categories'=>$categories
		));
	}
}
?>