<?php
class ListStartTermRemainForm extends Form
{
	function ListStartTermRemainForm()
	{
		Form::Form('ListStartTermRemainForm');
		$this->link_css(Portal::template('warehousing').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        $warehouses = get_warehouse(true);
		$item_per_page = 1000;
		$cond = '1=1 and wh_start_term_remain.portal_id=\''.PORTAL_ID.'\'
			'.(Url::get('warehouse_id')?' AND wh_start_term_remain.warehouse_id = '.Url::iget('warehouse_id').'':'').'
            '.(Url::get('product_id')?' AND upper(product.id) like \'%'.strtoupper(Url::sget('product_id')).'%\'':'').'
			';
        
        //Show ra cac san pham trong kho ma ho duoc phan quyen
        $cond .=' and wh_start_term_remain.warehouse_id in (';    
        foreach($warehouses as $k=>$v)
        {
            $cond.=$k.',';
        }
        $cond = trim($cond,',');
        $cond.= ')';
        
		$this->map['title'] = Portal::language('start_term_remain');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				wh_start_term_remain
                LEFT JOIN product ON product.id = wh_start_term_remain.product_id
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
					wh_start_term_remain.*,
                    warehouse.name as warehouse_name,
                    product.name_'.Portal::language().' as product_name,
					ROWNUM as rownumber
				FROM
					wh_start_term_remain
					LEFT JOIN warehouse ON warehouse.id = wh_start_term_remain.warehouse_id
					LEFT JOIN product ON product.id = wh_start_term_remain.product_id
                    LEFT JOIN unit on unit.id = product.unit_id
                    LEFT JOIN product_category on product_category.id = product.category_id
				WHERE
					'.$cond.'
				ORDER BY
					warehouse.structure_id,
                    wh_start_term_remain.product_id
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			//Lam tron 2 chu so sau dau phay
            $items[$key]['quantity'] = round($value['quantity'],2);
            $items[$key]['total_start_term_price'] = System::display_number($value['total_start_term_price']);
		}
		$this->map['items'] = $items;
		$this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($warehouses);
		$this->parse_layout('list',$this->map);
	}	
}
?>