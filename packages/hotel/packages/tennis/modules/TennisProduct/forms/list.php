<?php
class ListTennisProductForm extends Form
{
	function ListTennisProductForm()
	{
		Form::Form('ListTennisProductForm');
		$this->link_css('packages/hotel/'.Portal::template('tennis').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 500;
		$cond = '1=1
			'.(Url::get('keyword')?' AND UPPER(tennis_product.name) LIKE "%'.strtoupper(Url::sget('keyword')).'%"':'').'
			';
		$this->map['title'] = Portal::language('tennis_product_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				tennis_product
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
					tennis_product.*,
					ROWNUM as rownumber
				FROM
					tennis_product
				WHERE
					1 = 1
					'.(Url::get('keyword')?' AND (upper(tennis_product.code) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_product.name) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_product.price) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_product.category) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'	
				ORDER BY
					tennis_product.category,tennis_product.name
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