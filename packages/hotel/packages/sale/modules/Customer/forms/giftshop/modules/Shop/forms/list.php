<?php
class ListShopForm extends Form
{
	function ListShopForm()
	{
		Form::Form('ListShopForm');
		$this->link_css('packages/hotel/'.Portal::template('shop').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 500;
		$cond = '1>0
			'.(Url::get('keyword')?' AND UPPER(shop.name) LIKE "%'.strtoupper(Url::sget('keyword')).'%"':'').'
			';
		$this->map['title'] = Portal::language('shop_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				shop
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
					shop.*,
					ROWNUM as rownumber
				FROM
					shop
				ORDER BY
					shop.code
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