<?php
class VendingCategoryDiscountForm extends Form
{
	function VendingCategoryDiscountForm()
	{
		Form::Form('VendingCategoryDiscountForm');
	}
	function on_submit()
    {
        DB::delete('ve_product_category_discount',' portal_id=\''.PORTAL_ID.'\' ');
		$this->items=$this->get_list_category();
		//System::debug($this->items);
        
        foreach($this->items as $key => $item){
			if(Url::get('discount_'.$key)){
				unset($item['level']);
				unset($item['discount_percent']);
				$discount=Url::get('discount_'.$key);
				$item += array('discount_percent'=>$discount,'portal_id'=>PORTAL_ID);
				DB::insert('ve_product_category_discount',$item);
			}
		}
        //System::debug($this->items);
        //exit();
	}
	function draw()
	{
		///Lay danh mucj
		$this->items = $this->get_list_category();
		$this->map['categories_js'] = String::array2js($this->items);
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);
		$this->map['items'] = $this->items;
		$this->parse_layout('list',$this->map);
	}	
	function get_list_category(){
        $cond = ' 1=1 and product_price_list.department_code = \'VENDING\' ';
		$sql = 'SELECT
					product_category.id
					,product_category.name
					,product_category.structure_id
					,product_category.code
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN department ON department.code = product_price_list.department_code
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					'.$cond.' 
                    AND 
                    (
                        (
                            product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\' OR product.type = \'SERVICE\'
                        )
                        AND 
                        product_category.code <>\'ROOT\'
                    )
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
				ORDER BY structure_id';	
        //System::debug($sql);
		$items = DB::fetch_all($sql);
		$items_discount = DB::fetch_all('select 
                                            ve_product_category_discount.code as id, 
                                            ve_product_category_discount.discount_percent 
                                        from 
                                            ve_product_category_discount  
                                        where 
                                            portal_id=\''.PORTAL_ID.'\''
                                        );
		require_once 'packages/core/includes/system/id_structure.php';
		foreach($items as $key => $itm){
			$items[$key]['level'] = IDStructure::level($itm['structure_id']);
			if(isset($items_discount[$itm['code']])){
				$items[$key]['discount_percent'] = $items_discount[$itm['code']]['discount_percent'];
			}else{
				$items[$key]['discount_percent'] = 0;	
			}
		}			
		return $items;
	}
}
?>