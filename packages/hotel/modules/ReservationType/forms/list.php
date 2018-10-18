<?php
class ListReservationTypeForm extends Form
{
	function ListReservationTypeForm()
	{
		Form::Form('ListReservationTypeForm');
		$this->link_css(Portal::template('hotel').'/css/reservation_type.css');
		if(Url::get('update_to_lock')){
			$this->update_to_lock();
		}
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 1000;
		$cond = '1=1
			'.(Url::get('keyword')?' AND reservation_type.name LIKE "%'.Url::sget('keyword').'%"':'').'
			';
		$this->map['title'] = Portal::language('reservation_type_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				reservation_type
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
					reservation_type.*,
					ROWNUM as rownumber
				FROM
					reservation_type
				WHERE
					'.$cond.'
				ORDER BY
					'.(Url::get('order_by')?Url::get('order_by'):'reservation_type.name').' '.(Url::get('dir')?Url::get('dir'):'ASC').' 	
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
	function update_to_lock(){ //update loai phong cho the khoa tu
		require_once 'packages/core/includes/system/access_database.php';
		$db_file = LOCK_DB_FILE;
		$adb = new ADB("Driver={Microsoft Access Driver (*.mdb)};Dbq=".$db_file."",'','yhd');
		$adb->query('delete * from setroom');
		$adb->query('delete * from setroomtype');
		$items = DB::fetch_all('select * from reservation_type');
		foreach($items as $value){
			$adb->insert('setroomtype',array(
				'listId'=>$value['id'],
				'ReservationType'=>$value['name'],
				'RoomValue'=>$value['price'],
				'MaxMakeCardS'=>10
			));
			$rooms = DB::select_all('room','reservation_type_id='.$value['id']);
			foreach($rooms as $v){
				$name = str_pad($v['name'],5,"0",STR_PAD_LEFT);
				$floor = str_pad($v['floor'],2,"0",STR_PAD_LEFT);
				$adb->insert('setroom',array(
					'RoomId'=>'01'.$floor.$name,
					'BuildingId'=>'01',
					'FloorId'=>$floor,
					'guestRoomId'=>$name,
					'ReservationType'=>$value['name'],
					'RoomState'=>'Empty and Clean Room',
					'MaxMakeCardS'=>10,
					'RoomValue'=>$value['price'],
					'tooMakeCardS'=>0,
					'FlatRoomBZ'=>0,
					'JoinTime'=>date('Y-m-d')
				));
			}
		}
	}
}
?>