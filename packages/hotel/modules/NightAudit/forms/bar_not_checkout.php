<?php
class BarNotCheckoutForm extends Form
{
	function BarNotCheckoutForm()
	{
		Form::Form('BarNotCheckoutForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit(){
		if(Url::get('acction') == 1){	
			if(Url::get('bars')){
				Session::set('bar_id',Url::get('bars'));
				$_SESSION['bar_id'] = Url::get('bars');
			}
			$_REQUEST['bar_id'] = Session::get('bar_id');
			//Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'status'));	
		}	
	}
	function draw()
	{
		$cond = '
				1>0 and bar_reservation.portal_id=\''.PORTAL_ID.'\' and bar_reservation.status =\'CHECKIN\' 
                and bar_reservation.time > \''.(Date_Time::to_time($_SESSION['night_audit_date']) -86400).'\'
                and bar_reservation.time <= \''.Date_Time::to_time($_SESSION['night_audit_date']).'\'
                ';
		
        $item_per_page = 200;
		DB::query('
			select count(*) as acount
			from 
				bar_reservation
				left outer join bar_reservation_product on bar_reservation_product.bar_reservation_id = bar_reservation.id
			where '.$cond.'
		');
        
		$count = DB::fetch();

		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
			select * from(
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
					bar_reservation.user_id,
					bar_reservation.bar_id, 
					row_number() OVER (order by ABS(bar_reservation.arrival_time - '.time().')) AS rownumber
				from 
					bar_reservation
					left outer join bar_reservation_product on bar_reservation_product.bar_reservation_id = bar_reservation.id
					left outer join bar on bar_reservation.bar_id = bar.id
					left outer join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
					left outer join room on reservation_room.room_id = room.id
					left outer join traveller on reservation_room.traveller_id = traveller.id
				where '.$cond.'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by ABS(bar_reservation.arrival_time - '.time().')').' 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
		if($items)
		{
			foreach($items as $k=>$itm)
			{
				$order_id = '';
				for($i=0;$i<6-strlen($itm['id']);$i++)
				{
					$order_id .= '0';
				}
				$order_id .= $itm['id'];
				$items[$k]['order_id'] = $order_id;
				
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
				//--- Debt --
				$items[$k]['total'] = System::display_number($items[$k]['total']);
				
			}
		}
		require_once 'packages/hotel/includes/php/hotel.php';
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$this->parse_layout('bar_not_checkout',array(
				'items'=>$items,
				'paging'=>$paging,
			));
	}
}
?>