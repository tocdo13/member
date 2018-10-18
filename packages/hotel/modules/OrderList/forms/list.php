<?php
class OrderListForm extends Form
{
	function OrderListForm()
	{
		Form::Form('OrderListForm');
	}
	function draw()
	{
		$this->map = array();
		if(Url::get('date')){// and preg_match('/^[0-9]{2}/[0-9]{2}/[0-9]{4}/i',Url::get('date'))
			$date = Url::get('date');
			$this->map['date'] = $date;
            $this->map['res_room_items'] = $this->get_res_room_invoice($date);
			$this->map['bar_items'] = $this->get_bar_invoice($date);
			$this->map['minibar_items'] = $this->get_minibar_invoice($date);
			$this->map['laundry_items'] = $this->get_laundry_invoice($date);
			$this->map['compensative_items'] = $this->get_compensative_invoice($date);
			$this->map['extra_service_items'] = $this->get_extra_service_invoice($date);
            $this->map['spa_items'] = $this->get_spa_invoice($date);
			$this->parse_layout('list',$this->map);
		}else{
			$dates = DB::fetch_all('SELECT to_char(in_date,\'DD/MM/YYYY\') AS id,to_char(in_date,\'DD/MM/YYYY\') AS name FROM night_audit WHERE status=\'CHECKED\' AND portal_id = \''.PORTAL_ID.'\' ORDER BY IN_DATE');
			$this->map['date_list'] = String::get_list($dates);
			$this->parse_layout('search',$this->map);
		}
	}
    
    function get_res_room_invoice($date)
    {
        DB::query('
			SELECT
						folio.id as id
						,folio.total
						,folio.reservation_id as r_id
						,folio.reservation_room_id as rr_id
						,folio.reservation_traveller_id as rt_id
						,folio.customer_id
						,0 as foc
						,0 as deposit  
						,(customer.code || \' : \'|| customer.name) as customer_name
						,reservation_traveller.id as traveller_id
						,(\'KHLE: \' || traveller.first_name || \' \' || traveller.last_name) as full_name
						,room.name as room_name
                        ,folio.lastest_edit_time
						,folio.create_time as time,
						CASE WHEN (reservation_room.checked_out_user_id is null)
							THEN \'Khách chua out\'
							ELSE reservation_room.checked_out_user_id
						END	as user_id
					FROM
						folio
						INNER JOIN reservation ON reservation.id = folio.reservation_id 
						LEFT OUTER JOIN reservation_room ON reservation_room.id = folio.reservation_room_id
						LEFT OUTER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
						LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
						LEFT OUTER JOIN room ON room.id = reservation_room.room_id
						LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
					WHERE 
					    folio.portal_id=\''.PORTAL_ID.'\'
				        AND reservation_room.status <> \'CANCEL\'
				        AND folio.create_time >= '.Date_Time::to_time($date).' AND folio.create_time < '.(Date_Time::to_time($date)+24*3600).'
					ORDER BY
						folio.id DESC
		');
        $items = DB::fetch_all();
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['creat_time'] = date('d/m/Y',$value['time']);
			$items[$key]['lastest_edit_time'] = $value['lastest_edit_time']?date('d/m/Y',$value['lastest_edit_time']):'';
            $items[$key]['group']=0;
						if($value['rr_id']==''){
							$items[$key]['group']=1;
							//$items[$key]['guest_name'] = $value['customer_name'];	
						}else{
							//$items[$key]['guest_name'] = $value['full_name'];
						}
        }
		return $items;
    }
    
	function get_bar_invoice($date){
		DB::query('
			select 
				bar_reservation.id, 
				bar_reservation.code,
				bar_reservation.status, 
				bar_reservation.agent_name,
				bar_reservation.arrival_time,
				bar_reservation.departure_time,
				bar_reservation.prepaid,
				bar_reservation.time_in,
				bar_reservation.time_out,
				bar_reservation.cancel_time,
				bar_reservation.num_table,
				bar_reservation.total,
				bar.name as bar_name,
				room.name as room_name,
				bar_reservation.user_id
			from 
				bar_reservation
				left outer join bar on bar_id = bar.id
				left outer join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
				left outer join room on reservation_room.room_id = room.id
				left outer join traveller on reservation_room.traveller_id = traveller.id
			where	
				bar_reservation.portal_id=\''.PORTAL_ID.'\'
				AND bar_reservation.status <> \'CANCEL\'
				AND bar_reservation.arrival_time >= '.Date_Time::to_time($date).' AND bar_reservation.arrival_time < '.(Date_Time::to_time($date)+24*3600).'
			ORDER BY
				bar_reservation.time_in
		');
		$items = DB::fetch_all();
		foreach($items as $k=>$itm){
			$items[$k]['arrival_date'] = $itm['time_in']!=0?date('d/m/Y H:i',$itm['time_in']):date('d/m/Y H:i',$itm['arrival_time']);
			$time_in = $itm['time_in']!=0?$itm['time_in']:$itm['arrival_time'];
			$time_out = $itm['time_out']!=0?$itm['time_out']:$itm['departure_time'];
			if($itm['departure_time']>$itm['arrival_time'])
			{
				$items[$k]['time_length'] = date('H\h i',strtotime('1/1/2000')+$time_out-$time_in);
			}
			else
			{
				$items[$k]['time_length'] = '';
			}
			DB::query('
				select 
					table_id as id, bar_table.name as name
				from 
					bar_reservation_table inner join bar_table on bar_table.id = bar_reservation_table.table_id 
				where 
					bar_reservation_table.bar_reservation_id = \''.$itm['id'].'\'
			');
			$ttbls = DB::fetch_all();
			$tbl_names = '';
			foreach($ttbls as $tky=>$tabl)
			{
				$tbl_names .= ', '.$tabl['name'];
			}
			$items[$k]['table_name'] = substr($tbl_names,1);
		}
		return $items;
	}
	function get_minibar_invoice($date){
		$sql = '
			SELECT 
				housekeeping_invoice.id
				,housekeeping_invoice.time
				,housekeeping_invoice.total
				,housekeeping_invoice.tax_rate
				,housekeeping_invoice.fee_rate
				,housekeeping_invoice.discount
				,housekeeping_invoice.prepaid
				,concat(concat(traveller.first_name,\' \'),traveller.last_name) as guest_name 
				,P1.name_'.Portal::language().' as user_name
				,P1.user_id
				,last_modifier_id
				,minibar.name as minibar						 
				,P2.name_1 as last_modifier_name
				,room.name as room_name
				,reservation_room.status
				,to_char(reservation_room.arrival_time,\'DD/MM/YY\') as arrival_time
				,to_char(reservation_room.departure_time,\'DD/MM/YY\') as departure_time
				,row_number() over (order by housekeeping_invoice.time desc) as rownumber
			FROM 
				housekeeping_invoice
				left outer join minibar on minibar.id=housekeeping_invoice.minibar_id 
				left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=minibar.room_id					
				left outer join room on room.id = reservation_room.room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id
				left outer join party P1 on P1.user_id = housekeeping_invoice.user_id
				left outer join party P2 on P2.user_id=housekeeping_invoice.last_modifier_id
			WHERE 
				housekeeping_invoice.type=\'MINIBAR\' and housekeeping_invoice.portal_id=\''.PORTAL_ID.'\'
				AND (housekeeping_invoice.time >= \''.Date_Time::to_time($date).'\'
				AND housekeeping_invoice.time <=\''.(Date_Time::to_time($date)+86400).'\')
			ORDER BY
				housekeeping_invoice.time
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['date'] = date('d/m/Y',$item['time']);
			$items[$key]['remain']=System::display_number(($item['total']-$item['prepaid']));
			$act_total = DB::fetch('select sum(price*quantity) as total from housekeeping_invoice_detail where invoice_id = '.$item['id'].' group by invoice_id','total');
			$act_total -= round($act_total*$item['discount']/100,ROUND_PRECISION);
			$act_total += round($act_total*$item['fee_rate']/100,ROUND_PRECISION);
			$act_total += round($act_total*$item['tax_rate']/100,ROUND_PRECISION);
			$items[$key]['act_total'] = System::display_number($act_total); 
			if($act_total!=$items[$key]['total']){
				$items[$key]['fix']	 = ' <span style="color:#FF0000;">'.System::display_number($act_total).' - (Fix...?) </span>';
			}else{
				$items[$key]['fix']	 = '';
			}
			$items[$key]['fix']	 = '';
			$items[$key]['total']=System::display_number($item['total']); 
			$items[$key]['prepaid']=System::display_number($item['prepaid']); 
		}
		return $items;
	}
	function get_laundry_invoice($date){
		$sql = 'SELECT 
					housekeeping_invoice.id
					,CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name) as name
					,room.name as room_name
					,housekeeping_invoice.total
					,housekeeping_invoice.user_id
					,P1.name_'.Portal::language().' as user_name
					,last_modifier_id
					,housekeeping_invoice.time
					,P2.name_1 as last_modifier_name
					,reservation_room.status
					,to_char(reservation_room.arrival_time,\'DD/MM/YY\') as arrival_time
					,to_char(reservation_room.departure_time,\'DD/MM/YY\') as departure_time
					,concat(concat(traveller.first_name,\' \'),traveller.last_name) as guest_name
				FROM 
					housekeeping_invoice
					INNER JOIN reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
					LEFT OUTER JOIN traveller on traveller.id = reservation_room.traveller_id
					LEFT OUTER JOIN party P1 on P1.user_id = housekeeping_invoice.user_id
					INNER JOIN room on room.id = reservation_room.room_id
					LEFT OUTER JOIN party P2 on housekeeping_invoice.last_modifier_id = P2.user_id
				where 
					housekeeping_invoice.type=\'LAUNDRY\' and housekeeping_invoice.portal_id=\''.PORTAL_ID.'\'
					AND (housekeeping_invoice.time >= \''.Date_Time::to_time($date).'\'
					AND housekeeping_invoice.time <=\''.(Date_Time::to_time($date)+86400).'\')';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			 $items[$key]['total']=System::display_number($item['total']);
			 $items[$key]['date'] = date('d/m/Y',$item['time']);
		}
		return $items;
	}
	function get_compensative_invoice($date){
		$sql = 'SELECT 
					housekeeping_invoice.id
					,CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name) as name
					,room.name as room_name
					,housekeeping_invoice.total
					,housekeeping_invoice.user_id
					,P1.name_'.Portal::language().' as user_name
					,last_modifier_id
					,housekeeping_invoice.time
					,P2.name_1 as last_modifier_name
					,reservation_room.status
					,to_char(reservation_room.arrival_time,\'DD/MM/YY\') as arrival_time
					,to_char(reservation_room.departure_time,\'DD/MM/YY\') as departure_time
					,concat(concat(traveller.first_name,\' \'),traveller.last_name) as guest_name
				FROM 
					housekeeping_invoice
					INNER JOIN reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
					LEFT OUTER JOIN traveller on traveller.id = reservation_room.traveller_id
					LEFT OUTER JOIN party P1 on P1.user_id = housekeeping_invoice.user_id
					INNER JOIN room on room.id = reservation_room.room_id
					LEFT OUTER JOIN party P2 on housekeeping_invoice.last_modifier_id = P2.user_id
				where 
					housekeeping_invoice.type=\'EQUIP\' and housekeeping_invoice.portal_id=\''.PORTAL_ID.'\'
					AND (housekeeping_invoice.time >= \''.Date_Time::to_time($date).'\'
					AND housekeeping_invoice.time <=\''.(Date_Time::to_time($date)+86400).'\')';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			 $items[$key]['total']=System::display_number($item['total']);
			 $items[$key]['date'] = date('d/m/Y',$item['time']);
		}
		return $items;
	}
	function get_extra_service_invoice($date){
		$sql = '
			SELECT
					extra_service_invoice.*,room.name as room_name,
					to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_date,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_date,reservation_room.status,
					row_number() over (order by '.(Url::get('order_by')?Url::get('order_by'):'extra_service_invoice.time').' '.(Url::get('dir')?Url::get('dir'):'DESC').') as rownumber
				FROM
					extra_service_invoice
					LEFT OUTER JOIN reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
					LEFT OUTER JOIN room on room.id = reservation_room.room_id
				WHERE
					extra_service_invoice.portal_id=\''.PORTAL_ID.'\'
					 AND (extra_service_invoice.time >= '.Date_Time::to_time($date).' and extra_service_invoice.time < '.(Date_Time::to_time($date)+24*3600).')';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['time'] = date('d/m/Y',$value['time']);
			$items[$key]['lastest_edited_time'] = $value['lastest_edited_time']?date('d/m/Y',$value['lastest_edited_time']):'';
		}
		return $items;
	}
    function get_spa_invoice($date)
    {
        $sql = '
			SELECT 
					massage_product_consumed.*,massage_guest.full_name as guest_name,
					massage_room.name as room_name,
					(massage_product_consumed.price*massage_product_consumed.quantity) as total_amount,
                    room.name as hotel_room_name
				FROM
					massage_product_consumed
					LEFT OUTER JOIN massage_reservation_room ON massage_reservation_room.id = massage_product_consumed.reservation_room_id
					LEFT OUTER JOIN massage_room ON massage_room.id = massage_product_consumed.room_id
					LEFT OUTER JOIN massage_guest ON massage_guest.id = massage_reservation_room.guest_id
					LEFT OUTER JOIN massage_staff_room ON massage_staff_room.reservation_room_id = massage_reservation_room.id
                    LEFT OUTER JOIN reservation_room ON massage_reservation_room.hotel_reservation_room_id = reservation_room.id
                    LEFT OUTER JOIN room ON reservation_room.room_id = room.id
				WHERE
					massage_reservation_room.portal_id=\''.PORTAL_ID.'\'
                    AND massage_product_consumed.status <> \'CANCEL\'
					 AND (massage_product_consumed.time >= '.Date_Time::to_time($date).' and massage_product_consumed.time < '.(Date_Time::to_time($date)+24*3600).')';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['time'] = date('d/m/Y',$value['time']);
			$items[$key]['lastest_edited_time'] = $value['lastest_edited_time']?date('d/m/Y',$value['lastest_edited_time']):'';
		}
		return $items;
    }
}
?>