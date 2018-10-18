<?php
class ListKaraokeReservationNewCancelForm extends Form
{
	function ListKaraokeReservationNewCancelForm()
	{
		Form::Form('ListKaraokeReservationNewCancelForm');
	}
	function on_submit(){
		if(Url::get('acction') == 1){	
			if(Url::get('karaokes')){
				Session::set('karaoke_id',Url::get('karaokes'));
				$_SESSION['karaoke_id'] = Url::get('karaokes');
			}
			$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
			//Url::redirect_current(array('karaoke_id'=>Session::get('karaoke_id'),'status'));	
		}	
	}
	function draw()
	{
		$cond = '
				1>0 '
				.(URL::get('agent_name')!=''?' and karaoke_reservation_cancel.agent_name LIKE \'%'.URL::get('agent_name').'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and karaoke_reservation_cancel.arrival_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and karaoke_reservation_cancel.arrival_time<='.(Date_Time::to_time(URL::get('to_arrival_time'))+3600*24-1)):'') 
				.(URL::get('karaoke_id')!=''?' and karaoke_id=\''.URL::get('karaoke_id').'\'':'')
		;
		$item_per_page = 50;
		DB::query('
			select count(*) as acount
			from 
				karaoke_reservation_cancel
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
			select * from(
				select 
					karaoke_reservation_cancel.id, 
					karaoke_reservation_cancel.code, 
					karaoke_reservation_cancel.agent_name, 
					karaoke_reservation_cancel.arrival_time,
					karaoke_reservation_cancel.time,
					karaoke_reservation_cancel.deposit,
					karaoke.name as karaoke_id,
					party.full_name as user_name,
					karaoke_reservation.karaoke_id,
					ROWNUM as rownumber
				from 
					karaoke_reservation_cancel 
					left outer join party on party.user_id = karaoke_reservation_cancel.user_id and party.type=\'USER\'
					left outer join karaoke on karaoke.id = karaoke_reservation_cancel.karaoke_id
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
					table_id as id, karaoke_table.name as name 
				from 
					karaoke_reservation_table inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id 
				where 
					karaoke_reservation_table.karaoke_reservation_id = \''.$itm['id'].'\'
			');
			$ttbls = DB::fetch_all();
			$tbl_names = '';
			foreach($ttbls as $tky=>$tabl)
			{
				$tbl_names .= ','.$tabl['name'];
			}
			$items[$k]['table_name'] = substr($tbl_names,1);
		}
		//DB::query('select id,name from karaoke');
		//$karaokes = DB::fetch_all();
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$cond_admin = Table::get_privilege_karaoke();
		$karaokes = DB::fetch_all('SELECT * FROM karaoke where 1=1 '.$cond_admin.' ORDER BY ID ASC');
		$list_karaokes[0] = '----Select_Karaoke----';
		$list_karaokes = $list_karaokes + String::get_list($karaokes,'name');
		$this->parse_layout('list_cancel',array(
			'items'=>$items,
			'paging'=>$paging,
			'karaokes'=>$karaokes, 
			'karaokes_list'=>$list_karaokes
		));
	}
}
?>