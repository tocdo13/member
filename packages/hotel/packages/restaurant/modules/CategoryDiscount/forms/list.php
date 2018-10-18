<?php
class CategoryDiscountForm extends Form
{
	function CategoryDiscountForm()
	{
		Form::Form('CategoryDiscountForm');
	}
	function on_submit(){ 
		if(!Url::get('search') && Url::get('confirm')==1 && Url::get('bar_id')!=0){
			$cond=' 1=1';
			if(Url::get('category_name')){
				$cond .= ' AND UPPER(product_category_discount.name) like \'%'.mb_strtoupper(Url::get('category_name'),'utf-8').'%\'';	
			}
			if(Url::get('category_code')){
				$cond .= ' AND UPPER(product_category_discount.code) like \'%'.mb_strtoupper(Url::get('category_code'),'utf-8').'%\'';	
			}
            DB::delete('product_category_discount',' '.$cond.' AND bar_code=\''.Session::get('bar_code').'\'');
			$this->items=$this->get_list_category();
			foreach($this->items as $key => $item){
				if(Url::get('discount_'.$key)){
					unset($item['level']);
					unset($item['discount_percent']);
					$discount=Url::get('discount_'.$key);
					$item += array('discount_percent'=>$discount,'bar_code'=>Session::get('bar_code'));
					DB::insert('product_category_discount',$item);
				}
			}
			//Url::redirect_current();
		}
	}
	function draw()
	{
		//DB::query('delete from product_category_discount');
		$bars = $this->get_total_bars(Session::get('bar_id'));
		$this->map['bars'] = $bars;
		///Lay danh mucj
		$this->items = $this->get_list_category();
		$this->map['categories_js'] = String::array2js($this->items);
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);
		$this->map['items'] = $this->items;
		$this->parse_layout('list',$this->map);
	}	
	function get_list_category(){
                $cond = ' 1=1';
		if(Url::get('category_name')){
			$cond .= ' AND UPPER(product_category.name) like \'%'.mb_strtoupper(Url::get('category_name'),'utf-8').'%\'';	
		}
		if(Url::get('category_code')){
			$cond .= ' AND UPPER(product_category.code) like \'%'.mb_strtoupper(Url::get('category_code'),'utf-8').'%\'';	
		}
		if(Session::get('bar_id')){
			$dp_code = DB::fetch('select department_id as code from bar where id='.Session::get('bar_id').' and portal_id=\''.PORTAL_ID.'\'','code');	
			$cond .= ' AND product_price_list.department_code=\''.$dp_code.'\'';
		}
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
					'.$cond.' AND ((product.type = \'GOODS\' OR product.type = \'PRODUCT\')
					AND product_category.code <>\'ROOT\') or (product_category.code=\'DA\' or product_category.code =\'DU\')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
				ORDER BY structure_id';	
        //System::debug($sql);
		$items = DB::fetch_all($sql);
		$items_discount = DB::fetch_all('select pcd.code as id, 
												pcd.bar_code,pcd.discount_percent from product_category_discount pcd  where bar_code=\''.Session::get('bar_code').'\'');
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
	function get_total_bars($bar_id = false){
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' ORDER BY ID ASC');
		//System::Debug($bars);
		$items = '<select name="bar_id" onchange="ChangeBar();" id="bar_id">';
		$items .='<option value="">--Select Bar--</option>';	
		foreach($bars as $id=>$bar){
			if($bar['id'] == $bar_id){
				$items .= '<option value="'.$bar['id'].'" selected="selected">'.$bar['name'].'</option>';	
			}else $items .= '<option value="'.$bar['id'].'">'.$bar['name'].'</option>';	
		}
		$items .='</select>';
		return $items;
	}
}
?>