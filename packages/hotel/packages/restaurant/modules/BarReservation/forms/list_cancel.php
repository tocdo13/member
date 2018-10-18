<?php
class ListBarReservationCancelForm extends Form
{
	function ListBarReservationCancelForm()
	{
		Form::Form('ListBarReservationCancelForm');
	}
	function draw()
	{
		$cond = '
				1>0 '
				.(URL::get('agent_name')!=''?' and bar_reservation_cancel.agent_name LIKE \'%'.URL::get('agent_name').'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and bar_reservation_cancel.arrival_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and bar_reservation_cancel.arrival_time<='.(Date_Time::to_time(URL::get('to_arrival_time'))+3600*24-1)):'') 
				.(URL::get('bar_id')!=''?' and bar_id=\''.URL::get('bar_id').'\'':'')
		;
		$item_per_page = 50;
		DB::query('
			select count(*) as acount
			from 
				bar_reservation_cancel
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
			select * from(
				select 
					bar_reservation_cancel.id, 
					bar_reservation_cancel.code, 
					bar_reservation_cancel.agent_name, 
					bar_reservation_cancel.arrival_time,
					bar_reservation_cancel.time,
					bar_reservation_cancel.deposit,
					bar.name as restaurant_id,
					party.full_name as user_name,
					ROWNUM as rownumber
				from 
					bar_reservation_cancel 
					left outer join party on party.user_id = bar_reservation_cancel.user_id and party.type=\'USER\'
					left outer join bar on bar.id = bar_reservation_cancel.bar_id
				where '.$cond.'
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
			$items[$k]['arrival_date'] = date('d/m/Y H:i',$itm['arrival_time']);
			$items[$k]['cancel_date'] = date('d/m/Y H:i',$itm['time']);
			
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
				$tbl_names .= ','.$tabl['name'];
			}
			$items[$k]['table_name'] = substr($tbl_names,1);
		}
		DB::query('select id,name from bar');
		$bars = DB::fetch_all();

		$this->parse_layout('list_cancel',array(
			'items'=>$items,
			'paging'=>$paging,
			'bars'=>$bars, 
		));
	}
}
?>