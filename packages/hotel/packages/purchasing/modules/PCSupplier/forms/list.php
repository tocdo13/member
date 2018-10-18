<?php
class ListSupplierForm extends Form
{
	function ListSupplierForm()
	{
		Form::Form('ListSupplierForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
	    require_once 'packages/core/includes/utils/vn_code.php';   
		$this->map = array();
		$item_per_page = 50;
		$cond = '1=1
			'.(Url::get('pc_supplier_code')?' AND upper(supplier.code) LIKE \'%'.strtoupper(Url::sget('pc_supplier_code')).'%\'':'').'
			'.(Url::get('pc_supplier_name')?' AND lower(FN_CONVERT_TO_VN(supplier.name)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('pc_supplier_name'),'utf-8')).'%\'':'').'
			';
		//echo $cond;
        $this->map['title'] = Portal::language('pc_supplier_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				supplier
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));
		$sql = '
			SELECT * FROM
			(
				SELECT
					supplier.*,
                    pc_order.id as pc_order_id,
					row_number() over(ORDER BY supplier.name) AS rownumber
				FROM
					supplier 
                    left join pc_order on supplier.id=pc_order.pc_supplier_id
				WHERE	
					'.$cond.'
				ORDER BY 
                    -- UPPER(supplier.name)
                    supplier.code ASC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
        //System::debug($items);
		$i = 1;
		foreach($items as $key=>$value)
        {
			$items[$key]['i'] = $i++;			
		}
		$this->map['items'] = $items;
	
		$this->parse_layout('list',$this->map);
	}	
}
?>
