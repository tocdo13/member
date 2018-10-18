<?php
class ListSwimmingPoolProductForm extends Form
{
	function ListSwimmingPoolProductForm()
	{
		Form::Form('ListSwimmingPoolProductForm');
		$this->link_css('packages/hotel/'.Portal::template('swimming_pool').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 500;
		$cond = '1=1
			'.(Url::get('keyword')?' AND UPPER(swimming_pool_product.name) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\'':'').'
			';
		$this->map['title'] = Portal::language('swimming_pool_product_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				swimming_pool_product
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
					swimming_pool_product.*,
					ROWNUM as rownumber
				FROM
					swimming_pool_product
				WHERE
					1 = 1
					'.(Url::get('keyword')?' AND (upper(swimming_pool_product.code) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(swimming_pool_product.name) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(swimming_pool_product.price) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(swimming_pool_product.category) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'	
				ORDER BY
					swimming_pool_product.category,swimming_pool_product.name
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