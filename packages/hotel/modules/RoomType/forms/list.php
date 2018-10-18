<?php
class ListRoomTypeForm extends Form
{
	function ListRoomTypeForm()
	{
		Form::Form('ListRoomTypeForm');
		$this->link_css(Portal::template('hotel').'/css/room_type.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 200;
		$cond = '1=1
			'.(Url::get('keyword')?' AND room_type.name LIKE "%'.Url::sget('keyword').'%"':'').'
			';
		$this->map['title'] = Portal::language('room_type_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				room_type
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
					room_type.*,
					ROWNUM as rownumber
				FROM
					room_type
				WHERE
					'.$cond.'
				ORDER BY
					room_type.name
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
}
?>