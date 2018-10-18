<?php
class ListGuestLevelForm extends Form
{
	function ListGuestLevelForm()
	{
		Form::Form('ListGuestLevelForm');
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
			'.(Url::get('keyword')?' AND guest_type.name LIKE "%'.Url::sget('keyword').'%"':'').'
			';
		$this->map['title'] = Portal::language('reservation_type_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				guest_type
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
					guest_type.*,
					ROWNUM as rownumber
				FROM
					guest_type
				WHERE
					'.$cond.'
				ORDER BY
					'.(Url::get('order_by')?Url::get('order_by'):'guest_type.name').' '.(Url::get('dir')?Url::get('dir'):'ASC').' 	
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