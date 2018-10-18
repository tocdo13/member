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
		$this->link_css('packages/hotel/skins/default/css/jcarosel.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.jcarousel.min.js');
	}
	function on_submit(){
		if(Url::get('karaoke_id'))
		{
			Session::set('karaoke_id',intval(Url::get('karaoke_id')));
		}
		else if(!Session::is_set('karaoke_id'))
		{   
			require_once 'packages/hotel/includes/php/hotel.php';
			$karaoke = Hotel::get_new_karaoke();
			if($karaoke){
				Session::set('karaoke_id',$karaoke['id']);
			}
			else{
				Session::set('karaoke_id','');
			}
		}
		$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
		Url::redirect_current(array('karaoke_id','in_date','table_group'));	
	}
	function draw()
	{  
		$this->cancel_book_expried();
		$karaoke_id = Session::get('karaoke_id');
		$no_of_page = 500;
		$line_per_page = 36;
		$start_page = 1;
		$no_of_page = Url::get('page_no')?Url::get('page_no'):1;
		$sql_group='SELECT 
                        REPLACE(LOWER(FN_CONVERT_TO_VN(karaoke_table.table_group)),\' \',\'_\') as id
                        ,karaoke_table.table_group as name 
                    FROM karaoke_table 
                        INNER JOIN karaoke on karaoke.id=karaoke_table.karaoke_id 
                    WHERE karaoke_table.karaoke_id = '.$karaoke_id.' GROUP BY karaoke_table.table_group ORDER BY karaoke_table.table_group';		
		$groups = DB::fetch_all($sql_group);
        $name_group = '';
        foreach($groups as $k => $gr){
            $name_group = $gr['id'];
            break;
        }
		$table_group[0] = '--Group--';
		$table_group = $table_group +String::get_list($groups);
		$cond = '';
		/*if($groups && !empty($groups)){
			foreach($groups as $id=> $group){
				if($group['name'] == Url::get('table_group')){
					$cond = ' and karaoke_table.table_group = \''.Url::get('table_group').'\'';		
				}
			}
		}*/
        if(Url::get('group')){
            $cond .= ' AND REPLACE(LOWER(FN_CONVERT_TO_VN(karaoke_table.table_group)),\' \',\'_\') = \''.Url::get('group').'\'';
            $name_group = Url::get('group');    
			Session::set('group',Url::get('group'));
        }else{
            $cond .= ' AND REPLACE(LOWER(FN_CONVERT_TO_VN(karaoke_table.table_group)),\' \',\'_\')=\''.$name_group.'\'';
			if(!Session::is_set('group')){
				Session::set('group',$name_group);
			}
        }
        $_REQUEST['name_group'] = $name_group;
		$karaokes = $this->get_total_karaokes(false);
		$_REQUEST['in_date'] =  Url::get('in_date')?Url::get('in_date'):date('d/m/Y',time());
		$portals = Portal::get_portal_list();
		$count_table = DB::fetch('select count(karaoke_table.id) as total
					FROM
						karaoke_table
						inner join karaoke on karaoke.id=karaoke_table.karaoke_id
					WHERE
						karaoke_table.portal_id=\''.PORTAL_ID.'\'
						'.$cond.' AND karaoke_table.karaoke_id = '.$karaoke_id.'','total');
		$sql = '
				SELECT
					karaoke_table.id
					,karaoke_table.code
					,karaoke_table.name as name
					,karaoke_table.table_group
					,karaoke_table.karaoke_id
					,karaoke.name as karaoke_name
					,\'AVAILABLE\' AS status
					,ROWNUM as rownumber
				FROM
					karaoke_table
					inner join karaoke on karaoke.id=karaoke_table.karaoke_id
				WHERE
					karaoke_table.portal_id=\''.PORTAL_ID.'\'
					 '.$cond.' AND karaoke_table.karaoke_id = '.$karaoke_id.'
				ORDER BY
					karaoke_table.name
			';
        /*SELECT * FROM
			(
        )
			WHERE
			 rownumber > '.(($no_of_page-1)*$line_per_page).' and rownumber<='.($no_of_page*$line_per_page);
        */
		$karaoke_tables = DB::fetch_all($sql);	
		//System::Debug($karaoke_tables);
		$floors = array();
		$last_floor = false;			
		$i = 1;
		$status_tables_others = DB::fetch_all('
			SELECT
				karaoke_reservation.id,karaoke_table.id table_id,karaoke_reservation.status
				,karaoke_reservation.arrival_time,karaoke_reservation.total
			FROM
				karaoke_reservation
				inner join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
				inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id
				inner join karaoke on karaoke.id=karaoke_table.karaoke_id
			WHERE
				karaoke_reservation.portal_id=\''.PORTAL_ID.'\'
				AND ((karaoke_reservation.status = \'CHECKOUT\'
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND karaoke_reservation.arrival_time <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).') 
				OR (karaoke_reservation.status = \'BOOKED\' AND karaoke_reservation.departure_time<='.(Date_Time::to_time(Url::get('in_date'))+24*3600).'))
				 '.$cond.'
			ORDER BY
				karaoke_reservation.id ASC
		');//ABS(karaoke_reservation.arrival_time - '.(Date_Time::to_time(Url::get('in_date'))+(intval(date('H'))*3600+intval(date('i'))*60)).')	
		$sql = '
			SELECT
				karaoke_table.id,
				karaoke_reservation.id as karaoke_reservation_id,karaoke_reservation.agent_name,
				karaoke_reservation.time_in,karaoke_reservation.time_out,
				karaoke_reservation.arrival_time,karaoke_reservation.departure_time,
				karaoke_reservation.total,
				karaoke_reservation.code,
				karaoke_table.name as karaoke_table_name,
				karaoke.name as karaoke_name,
				karaoke_reservation_table.num_people,
				DECODE(karaoke_reservation.status,\'CHECKIN\',\'OCCUPIED\',DECODE(karaoke_reservation.status,\'RESERVATION\',\'BOOKED\',karaoke_reservation.status)) AS status
			FROM
				karaoke_reservation
				inner join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
				inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id
				inner join karaoke on karaoke.id=karaoke_table.karaoke_id
			WHERE
				karaoke_reservation.portal_id=\''.PORTAL_ID.'\' AND
				((karaoke_reservation.status = \'CHECKIN\'
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND karaoke_reservation.time_in <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).' '.$cond.') OR (karaoke_reservation.status = \'BOOKED\'
				AND karaoke_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND karaoke_reservation.arrival_time <'.(time()+15*60).' '.$cond.'))
			ORDER BY
				ABS(karaoke_reservation.arrival_time - '.(Date_Time::to_time(Url::get('in_date'))+(intval(date('H'))*3600+intval(date('i'))*60)).')
		';
		$busy_tables = DB::fetch_all($sql);
        //System::debug($busy_tables);
        foreach($busy_tables as $id=>$content)
        {
            $sing_room = DB::fetch_all("select karaoke_reservation_table.*, karaoke_table.name
                from karaoke_reservation_table 
                    inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id                                
                where karaoke_reservation_id = ".$content['karaoke_reservation_id']);
            foreach($sing_room as $id1=>$content1)
            {
                if($sing_room[$id1]['sing_start_time']!='' AND $sing_room[$id1]['sing_end_time']!='')
                {    
                    $busy_tables[$id]['total'] += (($content1['price']/3600)*($content1['sing_end_time']-$content1['sing_start_time']));
                }
            }
        }
		$table_out_list = DB::fetch_all('
			select
				karaoke_table.id as table_id,
				karaoke_reservation.id as id
			from
				karaoke_table
				inner join karaoke_reservation_table on karaoke_reservation_table.table_id = karaoke_table.id
				INNER JOIN karaoke_reservation on karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id
			where
			karaoke_reservation.portal_id=\''.PORTAL_ID.'\'
			AND ((karaoke_reservation.status = \'CHECKOUT\'
			AND karaoke_reservation.arrival_time >='.Date_Time::to_time(Url::get('in_date')).' AND karaoke_reservation.arrival_time <'.(Date_Time::to_time(Url::get('in_date'))+24*3600).') 
			OR (karaoke_reservation.status = \'BOOKED\' AND karaoke_reservation.departure_time<='.(Date_Time::to_time(Url::get('in_date'))+24*3600).'))
			 '.$cond.'
		'
		); //AND karaoke_reservation.arrival_time >='.(time()+15*60).'
		foreach($karaoke_tables as $key=>$value)
		{
			$id_booked = '';
			$floors[$value['table_group']]['id']= $value['karaoke_id'];
			$floors[$value['table_group']]['name']= $value['table_group'];
			$value['class'] = 'AVAILABLE';
			$value['agent_name'] = '';
			$value['href'] = URL::build('karaoke_touch',array('cmd'=>'add','arrival_time'=>$_REQUEST['in_date']));
			$value['arrival_time'] = '';
			$value['departure_time'] = '';
			$value['num_people'] = '';
			if(isset($busy_tables[$key])){
				$value['total'] = $busy_tables[$key]['total'];
				$value['code'] = $busy_tables[$key]['code'];
				$value['date_in'] = date('d/m/Y',$busy_tables[$key]['time_in']?$busy_tables[$key]['time_in']:$busy_tables[$key]['arrival_time']);
				$value['arrival_time'] = $busy_tables[$key]['time_in']?date('H:i',$busy_tables[$key]['time_in']):date('H:i',$busy_tables[$key]['arrival_time']);
				$value['departure_time'] = $busy_tables[$key]['time_out']?date('H:i',$busy_tables[$key]['time_out']):date('H:i',$busy_tables[$key]['departure_time']);
				$value['status'] = $busy_tables[$key]['status'];
				$value['agent_name'] = $busy_tables[$key]['agent_name'];
				if($busy_tables[$key]['status']=='BOOKED')
				{
					$value['href'] = URL::build('karaoke_touch',array('cmd'=>'edit','id'=>$busy_tables[$key]['karaoke_reservation_id']));
					$id_booked = $busy_tables[$key]['karaoke_reservation_id'];
				}
				else
				{  
					$value['href'] = URL::build('karaoke_touch',array('cmd'=>'edit','id'=>$busy_tables[$key]['karaoke_reservation_id']));
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
					if($tbl['table_id']==$key and $k==$tbl_id && $tbl_id!=$id_booked){
						$value['status_tables_others'][$k]['id'] = $k;
						$value['status_tables_others'][$k]['status'] = $v['status'];
					}
				}
			}
			$floors[$value['table_group']]['karaoke_tables'][$i]= $value;
			$floors[$value['table_group']]['karaoke_id'] = $value['karaoke_id'];
			$i++;
		}
		$this->map['list_karaoke'] = $karaokes;
		foreach($floors as $j => $floor){
			if(isset($karaokes[$floor['karaoke_id']])){
				$karaokes[$floor['karaoke_id']]['floors'][$j] = $floor;	
			}
		}  
		//System::Debug($karaokes);
		$this->map['total_page'] = ceil($count_table/$line_per_page);
		$this->map['karaokes'] = $karaokes;
		$this->parse_layout('list',$this->map+array('table_group_list'=>$table_group,'groups'=>$groups,'name_group'=>$name_group));
	}
	function get_total_karaokes($karaoke_id = false){
		//-------- Phan quyen Karaoke-------------//
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$cond_admin = Table::get_privilege_karaoke();
		$karaokes = array();
		if(User::is_admin() || $cond_admin){
			$karaokes = DB::fetch_all('SELECT * FROM karaoke where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		return $karaokes;
	}
	function get_total_karaokes_select($karaoke_id = false){
		//-------- Phan quyen Karaoke-------------//
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$cond_admin = Table::get_privilege_karaoke();
		if(User::is_admin() || $cond_admin){
			$karaokes = DB::fetch_all('SELECT * FROM karaoke where 1=1 '.$cond_admin.' and portal_id = \''.PORTAL_ID.'\' ORDER BY ID ASC');
		}
		$items = '<select name="karaoke_id" onchange="ChangeKaraoke();" id="karaoke_id">';
		$items .='<option value="">--Select Karaoke--</option>';	
		foreach($karaokes as $id=>$karaoke){
			if($karaoke['id'] == $karaoke_id){
				$items .= '<option value="'.$karaoke['id'].'" selected="selected">'.$karaoke['name'].'</option>';	
			}else $items .= '<option value="'.$karaoke['id'].'">'.$karaoke['name'].'</option>';	
		}
		$items .='</select>';
		return $items;
	}
function cancel_book_expried(){
		DB::update('karaoke_reservation',array('status'=>'CANCEL'),' karaoke_reservation.departure_time < '.time().' AND karaoke_reservation.status=\'BOOKED\'');	
	}
}
?>