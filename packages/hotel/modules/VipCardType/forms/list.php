<?php
class ListVipCardTypeForm extends Form
{
	function ListVipCardTypeForm()
	{
		Form::Form('ListVipCardTypeForm');
		$this->link_css(Portal::template('hotel').'/css/vip_card_type.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 200;
		$cond = '1 = 1
			'.(Url::get('keyword')?' AND vip_card_type.name LIKE "%'.Url::sget('keyword').'%"':'').'
			';
		$this->map['title'] = Portal::language('vip_card_type_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				vip_card_type
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
					vip_card_type.*,
					ROWNUM as rownumber
				FROM
					vip_card_type
				ORDER BY
					'.(Url::get('order_by')?Url::get('order_by'):'vip_card_type.name').' '.(Url::get('dir')?Url::get('dir'):'ASC').' 	
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