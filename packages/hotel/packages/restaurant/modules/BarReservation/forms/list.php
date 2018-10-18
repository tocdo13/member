<?php
class ListBarReservationForm extends Form
{
	function ListBarReservationForm()
	{
		Form::Form('ListBarReservationForm');
		$this->link_css('skins/default/restaurant.css');		
	}
	function draw()
	{
		$cond = '
				1>0 '
				.(URL::get('agent_name')!=''?' and lower(bar_reservation.agent_name) LIKE \'%'.strtolower(addslashes(URL::get('agent_name'))).'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and bar_reservation.arrival_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and bar_reservation.arrival_time<='.(Date_Time::to_time(URL::get('to_arrival_time'))+3600*24-1)):'') 
				.(URL::get('bar_id')!=''?' and bar_id=\''.URL::get('bar_id').'\'':'')
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
		;
		$item_per_page = 50;
		$status = array(
			''=>Portal::language('All_status'),
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT',
			'RESERVATION'=>'RESERVATION'
			);
		if(Url::get('cmd')=='list_debt')
		{
			DB::query('
				select count(*) as acount
				from 
					bar_reservation
				where '.$cond.'
					and status=\'CHECKOUT\' and payment_result=\'DEBT\'
			');
			$count = DB::fetch();
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging($count['acount'],$item_per_page);
			DB::query('
			select * from (
				select 
					bar_reservation.id, 
					bar_reservation.code, 
					bar_reservation.agent_name, 
					bar_reservation.total,
					bar_reservation.prepaid,
					bar_reservation.arrival_time,
					bar_reservation.departure_time,
					bar_reservation.time_in,
					bar_reservation.time_out,
					ROWNUM as rownumber
				from 
					bar_reservation
				where '.$cond.' 
					and status=\'CHECKOUT\' and payment_result=\'DEBT\' 
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by id desc').' 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'				
			');
			$items = DB::fetch_all();
			foreach($items as $k=>$itm)
			{
				$order_id = '';
				for($i=0;$i<6-strlen($itm['id']);$i++)
				{
					$order_id .= '0';
				}
				$order_id .= $itm['id'];
				$items[$k]['order_id'] = $order_id;
				$items[$k]['debt_total'] = System::display_number_report($itm['total']-$itm['prepaid']);
				
				$time_in = $itm['time_in']!=0?$itm['time_in']:$itm['arrival_time'];
				$time_out = $itm['time_out']!=0?$itm['time_out']:$itm['departure_time'];
				$items[$k]['arrival_date'] = date('d/m/Y H:i',$time_in);
				if($time_out>$time_in)
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
		}
		else if(Url::get('cmd')=='list_cancel')
		{
			DB::query('
				select count(*) as acount
				from 
					bar_reservation
				where '.$cond.'
					and status=\'CANCEL\'
			');
			$count = DB::fetch();
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging($count['acount'],$item_per_page);
			DB::query('
			select * from (
				select 
					bar_reservation.id, 
					bar_reservation.code, 
					bar_reservation.agent_name, 
					bar_reservation.total,
					bar_reservation.prepaid,
					bar_reservation.arrival_time,
					bar_reservation.departure_time,
					bar_reservation.time_in,
					bar_reservation.time_out,
					bar_reservation.cancel_time,
					bar_reservation.receptionist_id as user_name,
					ROWNUM as rownumber
				from 
					bar_reservation
				where '.$cond.' 
					and status=\'CANCEL\'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by id desc').' 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'				
			');
			$items = DB::fetch_all();
			foreach($items as $k=>$itm)
			{
				$order_id = '';
				for($i=0;$i<6-strlen($itm['id']);$i++)
				{
					$order_id .= '0';
				}
				$order_id .= $itm['id'];
				$items[$k]['order_id'] = $order_id;
				$items[$k]['debt_total'] = System::display_number_report($itm['total']-$itm['prepaid']);
				$time_in = $itm['time_in']!=0?$itm['time_in']:$itm['arrival_time'];
				$time_out = $itm['time_out']!=0?$itm['time_out']:$itm['departure_time'];
				$items[$k]['arrival_date'] = date('d/m/Y H:i',$time_in);
				if($time_out>$time_in)
				{
					$items[$k]['time_length'] = date('H\h i',strtotime('1/1/2000')+$time_out-$time_in);
				}
				else
				{
					$items[$k]['time_length'] = '';
				}
				$items[$k]['cancel_date'] = date('H:i d/m/Y',$itm['cancel_time']);
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
		}
		
		else
		if(Url::get('cmd')=='list_free')
		{
			DB::query('
				select count(*) as acount
				from 
					bar_reservation
				where '.$cond.'
					and status=\'CHECKOUT\' and payment_result=\'FREE\'
			');
			$count = DB::fetch();
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging($count['acount'],$item_per_page);
			DB::query('
			select * from(
				select 
					bar_reservation.id, 
					bar_reservation.code, 
					bar_reservation.agent_name, 
					bar_reservation.total,
					bar_reservation.prepaid,
					bar_reservation.arrival_time,
					bar_reservation.departure_time,
					bar_reservation.time_in,
					bar_reservation.time_out,
					ROWNUM as rownumber
				from 
					bar_reservation
				where '.$cond.' 
					and status=\'CHECKOUT\' and payment_result=\'FREE\'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by id desc').' 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
			');
			$items = DB::fetch_all();
			
			foreach($items as $k=>$itm)
			{
				$order_id = '';
				for($i=0;$i<6-strlen($itm['id']);$i++)
				{
					$order_id .= '0';
				}
				$order_id .= $itm['id'];
				$items[$k]['order_id'] = $order_id;
				echo $itm['total'].'-'.$itm['prepaid'];
				$items[$k]['free_total'] = System::display_number_report($itm['total']-$itm['prepaid']);
				
				$time_in = $itm['time_in']!=0?$itm['time_in']:$itm['arrival_time'];
				$time_out = $itm['time_out']!=0?$itm['time_out']:$itm['departure_time'];
				$items[$k]['arrival_date'] = date('d/m/Y H:i',$time_in);
				if($time_out>$time_in)
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
		}
		else
		{
			if(Url::get('status'))
			{
				$cond.=' and bar_reservation.status=\''.URL::get('status').'\'';
			}
			DB::query('
				select count(*) as acount
				from 
					bar_reservation
				where '.$cond.'
			');
			$count = DB::fetch();
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging($count['acount'],$item_per_page);
			DB::query('
				select * from(
					select 
						bar_reservation.id, 
						bar_reservation.code, bar_reservation.status, 
						bar_reservation.agent_name,
						bar_reservation.arrival_time,
						bar_reservation.departure_time,
						bar_reservation.time_in,
						bar_reservation.time_out,
						bar_reservation.num_table,
						bar_reservation.total,
						bar.name as bar_name,
						room.name as room_name,
						ROWNUM as rownumber
					from 
						bar_reservation
						left outer join bar on bar_id=bar.id
						left outer join reservation_room on reservation_room_id = reservation_room.id
						left outer join room on reservation_room.room_id = room.id
						left outer join traveller on reservation_room.traveller_id = traveller.id
					where '.$cond.'
					'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by bar_reservation.status, time desc').' 
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
					$items[$k]['total'] = System::display_number($items[$k]['total']);
				}
			}
		}
		require_once 'packages/hotel/includes/php/hotel.php';
		$bars = Hotel::get_bar();
		if(Url::get('bar_id'))
		{
			$bar = Hotel::get_new_bar(Url::get('bar_id'));
		}
		if(Url::get('cmd')=='list_debt')
		{
			$this->parse_layout('list_debt',array(
				'items'=>$items,
				'paging'=>$paging,
				'bar_name'=>isset($bar['name'])?$bar['name']:'',
				'bar_id_list'=>array(''=>'------')+String::get_list($bars),
			));
		}
		else
		if(Url::get('cmd')=='list_free')
		{
			$this->parse_layout('list_free',array(
				'items'=>$items,
				'paging'=>$paging,
				'bar_name'=>isset($bar['name'])?$bar['name']:'',				
				'bar_id_list'=>array(''=>'------')+String::get_list($bars), 
			));
		}
		else
		if(Url::get('cmd')=='list_cancel')
		{
			$this->parse_layout('list_cancel',array(
				'items'=>$items,
				'paging'=>$paging,
				'bar_name'=>isset($bar['name'])?$bar['name']:'',				
				'bar_id_list'=>array(''=>'------')+String::get_list($bars), 
			));
		}		
		else
		{
			$this->parse_layout('list',array(
				'items'=>$items,
				'paging'=>$paging,
				'bar_name'=>isset($bar['name'])?$bar['name']:'',				
				'bar_id_list'=>array(''=>'------')+String::get_list($bars), 
				'status_list'=>$status
			));
		}
	}
}
?>