<?php
class EditTableMapForm extends Form
{
	function EditTableMapForm()
	{
		Form::Form('EditTableMapForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
		//$this->link_css('style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/cookie.js');

	}
	function on_submit(){
		if(Url::get('bar_id'))
		{
			Session::set('bar_id',intval(Url::get('bar_id')));
		}
		else if(!Session::is_set('bar_id'))
		{   
			require_once 'packages/hotel/includes/php/hotel.php';
			$bar = Hotel::get_new_bar();
			if($bar){
				Session::set('bar_id',$bar['id']);
			}
			else{
				Session::set('bar_id','');
			}
		}
		$_REQUEST['bar_id'] = Session::get('bar_id');
		Url::redirect_current(array('bar_id','in_date','table_group'));	
	}
	function draw()
	{  
		$bar_id = Session::get('bar_id');
		//$_REQUEST['bar_id'] = $bar_id;
		$sql_group='SELECT bar_table.table_group as id, bar_table.table_group as name FROM bar_table INNER JOIN bar on bar.id=bar_table.bar_id  GROUP BY bar_table.table_group';		
		//WHERE bar_table.bar_id = '.$bar_id.'
		$groups = DB::fetch_all($sql_group);
		$table_group[0] = '--Group--';
		$table_group = $table_group +String::get_list($groups);
		$cond = '';
		/*if($groups && !empty($groups)){
			foreach($groups as $id=> $group){
				if($group['name'] == Url::get('table_group')){
					$cond = ' and bar_table.table_group = \''.Url::get('table_group').'\'';		
				}
			}
		}*/
		$bars = $this->get_total_bars(false);
		$_REQUEST['in_date'] =  Url::get('in_date')?Url::get('in_date'):date('d/m/Y',time());
		$portals = Portal::get_portal_list();
		$sql = '
			SELECT
				bar_table.*,
				bar.name as bar_name,
				\'AVAILABLE\' AS status
			FROM
				bar_table
				inner join bar on bar.id=bar_table.bar_id
			WHERE
				bar_table.portal_id=\''.PORTAL_ID.'\'
				 '.$cond.'
			ORDER BY
				bar.name,bar_table.name
		';//bar_table.bar_id = '.$bar_id.'
		$bar_tables = DB::fetch_all($sql);	
		//System::Debug($bar_tables);
		$floors = array();
		$last_floor = false;			
		$i = 1;
		$status_tables_others = DB::fetch_all('
			SELECT
				bar_reservation.id,bar_table.id table_id,bar_reservation.status
				,bar_reservation.arrival_time,bar_reservation.total
			FROM
				bar_reservation
				inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
				inner join bar_table on bar_table.id = bar_reservation_table.table_id
				inner join bar on bar.id=bar_table.bar_id
			WHERE
				bar_reservation.portal_id=\''.PORTAL_ID.'\'
				AND ((bar_reservation.status = \'CHECKOUT\'
				AND bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.arrival_time <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).') 
				OR (bar_reservation.status = \'BOOKED\' AND bar_reservation.arrival_time >='.(time()+15*60).'))
				 '.$cond.'
			ORDER BY
				ABS(bar_reservation.arrival_time - '.(Date_Time::to_time(Url::get('in_date'))+(intval(date('H'))*3600+intval(date('i'))*60)).')	
		');
		$sql = '
			SELECT
				bar_table.id,
				bar_reservation.id as bar_reservation_id,bar_reservation.agent_name,
				bar_reservation.time_in,bar_reservation.time_out,
				bar_reservation.arrival_time,bar_reservation.departure_time,
				bar_reservation.total,
				bar_reservation.code,
				bar_table.name as bar_table_name,
				bar.name as bar_name,
				bar_reservation_table.num_people,
				DECODE(bar_reservation.status,\'CHECKIN\',\'OCCUPIED\',DECODE(bar_reservation.status,\'RESERVATION\',\'BOOKED\',bar_reservation.status)) AS status
			FROM
				bar_reservation
				inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
				inner join bar_table on bar_table.id = bar_reservation_table.table_id
				inner join bar on bar.id=bar_table.bar_id
			WHERE
				bar_reservation.portal_id=\''.PORTAL_ID.'\' AND
				((bar_reservation.status = \'CHECKIN\'
				AND bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.time_in <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).' '.$cond.') OR (bar_reservation.status = \'BOOKED\'
				AND bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.arrival_time <'.(time()+15*60).' '.$cond.'))
			ORDER BY
				ABS(bar_reservation.arrival_time - '.(Date_Time::to_time(Url::get('in_date'))+(intval(date('H'))*3600+intval(date('i'))*60)).')
		';
		$busy_tables = DB::fetch_all($sql);
		$table_out_list = DB::fetch_all('
			select
				bar_table.id,
				bar_reservation.id as bar_reservation_id
			from
				bar_table
				inner join bar_reservation_table on bar_reservation_table.table_id = bar_table.id
				INNER JOIN bar_reservation on bar_reservation.id = bar_reservation_table.bar_reservation_id
			where
			bar_reservation.portal_id=\''.PORTAL_ID.'\'
			AND ((bar_reservation.status = \'CHECKOUT\'
			AND bar_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND bar_reservation.arrival_time <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).') 
			OR (bar_reservation.status = \'BOOKED\' AND bar_reservation.arrival_time >='.(time()+15*60).' AND bar_reservation.departure_time<='.(Date_Time::to_time(Url::get('in_date'))+24*3600).'))
			 '.$cond.'
		'
		);
		foreach($bar_tables as $key=>$value)
		{
			$floors[$value['table_group']]['id']= $value['bar_id'];
			$floors[$value['table_group']]['name']= $value['table_group'];
			$value['class'] = 'AVAILABLE';
			$value['agent_name'] = '';
			$value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'add','arrival_time'=>$_REQUEST['in_date']));
			$value['arrival_time'] = '';
			$value['departure_time'] = '';
			$value['num_people'] = '';
			if(isset($busy_tables[$key])){
				$value['total'] = $busy_tables[$key]['total'];
				$value['code'] = $busy_tables[$key]['code'];
				$value['arrival_time'] = $busy_tables[$key]['time_in']?date('H:i',$busy_tables[$key]['time_in']):date('H:i',$busy_tables[$key]['arrival_time']);
				$value['departure_time'] = $busy_tables[$key]['time_out']?date('H:i',$busy_tables[$key]['time_out']):date('H:i',$busy_tables[$key]['departure_time']);
				$value['status'] = $busy_tables[$key]['status'];
				$value['agent_name'] = $busy_tables[$key]['agent_name'];
				if($busy_tables[$key]['status']=='BOOKED')
				{
					$value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$busy_tables[$key]['bar_reservation_id']));
				}
				else
				{
					$value['href'] = URL::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$busy_tables[$key]['bar_reservation_id']));
				}
				$value['num_people'] = $busy_tables[$key]['num_people'].' '.Portal::language('people');
			}
			else
			{
				$value['total'] = '';
			}
			if($value['status']){
				$value['class'] = $value['status'];	
			}
			$value['status_tables_others'] = array();
			foreach($status_tables_others as $k=>$v){
				foreach($table_out_list as $tbl_id=>$tbl)
				{
					if($tbl_id==$key and $k==$tbl['bar_reservation_id']){
						$value['status_tables_others'][$k]['id'] = $k;
						$value['status_tables_others'][$k]['status'] = $v['status'];
					}
				}
			}
			$floors[$value['table_group']]['bar_tables'][$i]= $value;
			$floors[$value['table_group']]['bar_id'] = $value['bar_id'];
			$i++;
		}
		$this->map['list_bar'] = $bars;
		foreach($floors as $j => $floor){
			if(isset($bars[$floor['bar_id']])){
				$bars[$floor['bar_id']]['floors'][$j] = $floor;	
			}
		}
		//System::Debug($bars);
		$this->map['bars'] = $bars;
		$this->parse_layout('list',$this->map+array('table_group_list'=>$table_group));
	}
	function get_total_bars($bar_id = false){
		//-------- Phan quyen Bar-------------//
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = array();
		if(User::is_admin() || $cond_admin){
			$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		return $bars;
	}
	function get_total_bars_select($bar_id = false){
		//-------- Phan quyen Bar-------------//
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		if(User::is_admin() || $cond_admin){
			$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		$items = '<select name="bar_id" onchange="ChangeBar();" id="bar_id">';
		$items .='<option value="">--Select Bar--</option>';	
		foreach($bars as $id=>$bar){
			if($bar['id'] == $bar_id){
				$items .= '<option value="'.$bar['id'].'" selected="selected">'.$bar['name'].'</option>';	
			}else $items .= '<option value="'.$bar['id'].'">'.$bar['name'].'</option>';	
		}
		$items .='</select>';
		return $items;
	}
}
?>