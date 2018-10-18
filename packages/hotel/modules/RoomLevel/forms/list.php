<?php
class ListRoomLevelForm extends Form
{
	function ListRoomLevelForm()
	{
		Form::Form('ListRoomLevelForm');
		$this->link_css(Portal::template('hotel').'/css/room_level.css');
		if(Url::get('update_to_lock')){
			$this->update_to_lock();
		}
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 200;
		$cond = 'room_level.portal_id = \''.PORTAL_ID.'\'
			'.(Url::get('keyword')?' AND room_level.name LIKE "%'.Url::sget('keyword').'%"':'').'
			';
		$this->map['title'] = Portal::language('room_level_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				room_level
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
					room_level.*,party.name_1 as portal_name,
					row_number() over(ORDER BY room_level.portal_id,room_level.is_virtual DESC,room_level.name) as rownumber
				FROM
					room_level
					inner join account on account.id = room_level.portal_id
					inner join party on party.user_id = account.id
				WHERE
					'.$cond.'
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['price'] = System::display_number($value['price']);
			$items[$key]['i'] = $i++;
			/*DB::insert('room_level',array(
				'name'=>$value['name'],
				'brief_name'=>$value['brief_name'],
				'price'=>$value['price'],
				'portal_id'=>'#hongngoc4'
			));*/
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
		$items = DB::fetch_all('select * from room_level');
		foreach($items as $value){
			$adb->insert('setroomtype',array(
				'listId'=>$value['id'],
				'RoomLevel'=>$value['name'],
				'RoomValue'=>$value['price'],
				'MaxMakeCardS'=>10
			));
			$rooms = DB::select_all('room','room_level_id='.$value['id']);
			foreach($rooms as $v){
				$name = str_pad($v['name'],5,"0",STR_PAD_LEFT);
				$floor = str_pad($v['floor'],2,"0",STR_PAD_LEFT);
				$adb->insert('setroom',array(
					'RoomId'=>'01'.$floor.$name,
					'BuildingId'=>'01',
					'FloorId'=>$floor,
					'guestRoomId'=>$name,
					'RoomLevel'=>$value['name'],
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