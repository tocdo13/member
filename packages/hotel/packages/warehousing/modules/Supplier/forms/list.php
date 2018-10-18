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
			'.(Url::get('supplier_code')?' AND upper(supplier.code) LIKE \'%'.strtoupper(Url::sget('supplier_code')).'%\'':'').'
			'.(Url::get('supplier_name')?' AND lower(FN_CONVERT_TO_VN(supplier.name)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('supplier_name'),'utf-8')).'%\'':'').'
			';
		
        $this->map['title'] = Portal::language('supplier_list');
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
					row_number() over(ORDER BY supplier.name) AS rownumber
				FROM
					supplier 
				WHERE	
					'.$cond.'
				ORDER BY 
                    supplier.name	
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
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