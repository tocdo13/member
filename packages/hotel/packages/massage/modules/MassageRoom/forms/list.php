<?php
class ListMassageRoomForm extends Form
{
	function ListMassageRoomForm()
	{
		Form::Form('ListMassageRoomForm');
		$this->link_css('packages/hotel/'.Portal::template('massage').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 500;
		$cond = '1 = 1 and massage_room.portal_id=\''.PORTAL_ID.'\'
			'.(Url::get('keyword')?' AND UPPER(massage_room.name) LIKE "%'.strtoupper(Url::sget('keyword')).'%"':'').'
			';
		$this->map['title'] = Portal::language('room_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				massage_room
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
                    
                    area_group.name_1 as area,
					massage_room.*,
					ROWNUM as rownumber
				FROM
					massage_room 
                    left join area_group on massage_room.area_id = area_group.id
				WHERE
					'.$cond.'
				ORDER BY
					massage_room.position,
                    massage_room.name
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>