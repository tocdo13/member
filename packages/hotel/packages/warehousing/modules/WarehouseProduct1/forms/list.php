<?php
class ListWarehouseProductForm extends Form
{
	function ListWarehouseProductForm()
	{
		Form::Form('ListWarehouseProductForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 50;
		$cond = '1 = 1
			'.(Url::get('keyword')?' AND (UPPER(wh_product.name_'.Portal::language().') LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'
			';
		$this->map['title'] = Portal::language('product_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				wh_product
				LEFT OUTER JOIN wh_product_category on wh_product_category.id = wh_product.category_id
				LEFT OUTER JOIN unit on unit.id = wh_product.unit_id
			WHERE
				'.$cond.'
		';
		require_once '../../WarehouseProduct/forms/packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));
		$sql = '
			SELECT * FROM
			(
				SELECT
					wh_product.id,wh_product.name_'.Portal::language().' as name,wh_product.type,
					wh_product_category.name as category_name,
					unit.name_'.Portal::language().' as unit_name,
					wh_product.start_term_quantity,
					ROWNUM AS rownumber
				FROM
					wh_product
					LEFT OUTER JOIN wh_product_category on wh_product_category.id = wh_product.category_id
					LEFT OUTER JOIN unit on unit.id = wh_product.unit_id
				WHERE
					'.$cond.'	
				ORDER BY
					wh_product.id DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['type'] = Portal::language($value['type']);
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>