<?php
class ListLockReportForm extends Form
{
	function ListLockReportForm()
	{
		Form::Form('ListLockReportForm');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 1000;
		require_once 'packages/core/includes/system/access_database.php';
		
		$db_file = LOCK_DB_FILE;
		$adb = new ADB("Driver={Microsoft Access Driver (*.mdb)};Dbq=".$db_file."",'','yhd');
		$sql = '
			SELECT 
				openroomregister.listId AS id,
				openroomregister.*
			FROM
				openroomregister
			WHERE
				1=1
				'.(Url::get('arrival_time')?' AND (openroomregister.joinDate >= #'.Date_Time::to_orc_date(Url::get('arrival_time')).'# and  openroomregister.joinDate <= #'.Date_Time::to_orc_date(Url::get('arrival_time')).' 23:59:59 PM#)':'').'
				'.(Url::get('departure_time')?' AND (openroomregister.quitRoomDate >= #'.Date_Time::to_orc_date(Url::get('departure_time')).'# and openroomregister.quitRoomDate <= #'.Date_Time::to_orc_date(Url::get('departure_time')).' 23:59:59 PM#)':'').'
			ORDER BY
				openroomregister.joinDate DESC
		';
		$lock_items = $adb->fetch_all($sql);
		foreach($lock_items as $key=>$value){
			$lock_items[$key]['timeIn'] = substr($value['joinDate'],0,10);
			$lock_items[$key]['timeOut'] = substr($value['quitRoomDate'],0,10);
			
			//$lock_items[$key]['guestRoomId'] = $value['guestRoomId'];
		}
		$cond = '(reservation_room.status = \'CHECKIN\' OR reservation_room.status = \'CHECKOUT\')
			'.(Url::get('status')?' AND reservation_room.status LIKE \'%'.Url::sget('status').'%\'':'').'
			'.(Url::get('room_name')?' AND room.name LIKE \'%'.Url::sget('room_name').'%\'':'').'
			'.(Url::get('arrival_time')?' AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(Url::get('arrival_time')).'\'':'').'
			'.(Url::get('departure_time')?' AND reservation_room.departure_time = \''.Date_Time::to_orc_date(Url::get('departure_time')).'\'':'').'
			';
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				reservation_room
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10);
		$sql = '
			SELECT * FROM
			(
				SELECT
					reservation_room.*,room.name as room_name,
					ROWNUM as rownumber
				FROM
					reservation_room
					inner join room on room.id = reservation_room.room_id
				WHERE
					'.$cond.'
				ORDER BY
					room.name,reservation_room.time_in DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['color'] = '#FFFF8A';
			if($value['status']=='CHECKOUT'){
				$items[$key]['color'] = '#FBD7FB';
			}
			$items[$key]['time_in'] = date('d/m/Y H:i\'',$value['time_in']);
			$items[$key]['time_out'] = date('d/m/Y H:i\'',$value['time_out']);
			$items[$key]['lock_time_in'] = '';
			$items[$key]['lock_time_out'] = '';
			foreach($lock_items as $k=>$v){
				if(date('Y-m-d',$value['time_in'])==$v['timeIn'] and $value['room_name']==intval($v['guestRoomId'])){
					$items[$key]['lock_time_in'] = date('d/m/Y',$value['time_in']).' '.substr($v['joinDate'],11,(strlen($v['joinDate'])-3));
				}
				if(date('Y-m-d',$value['time_out'])==$v['timeOut'] and $value['room_name']==intval($v['guestRoomId'])){
					$items[$key]['lock_time_out'] = date('d/m/Y',$value['time_out']).' '.substr($v['quitRoomDate'],11,(strlen($v['quitRoomDate'])-3));
				}
			}
		}
		$this->map['items'] = $items;
		$this->map['status_list'] = array(
			''=>Portal::language('all'),
			'CHECKIN'=>Portal::language('check_in'),
			'CHECKOUT'=>Portal::language('check_out')			
		);
		$this->parse_layout('list',$this->map);
	}	
}
?>