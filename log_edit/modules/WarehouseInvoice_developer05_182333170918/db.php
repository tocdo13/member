<?php 
class WarehouseInvoiceDB
{
	static function get_invoice($id)
	{
		return DB::fetch('
			SELECT
				wh_invoice.*,
				customer.code as supplier_id,
				customer.name as supplier_name
			FROM
				wh_invoice
				LEFT OUTER JOIN customer ON customer.id = wh_invoice.supplier_id AND customer.group_id = \'SUPPLIER\'
			WHERE
				wh_invoice.id = '.$id.'				
		');	
	}
	static function get_units(){
		$list_units = DB::fetch_all('select id,name_'.Portal::language().' as name from unit order by name');	 
		$units = '<option value="">--Unit--</option>';
		foreach($list_units as $id => $value){
			$units .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		return $units;
	}
    
    static function get_category()
    {
        $category_id_list = String::get_list(DB::fetch_all('Select * from product_category order by structure_id'));	 
		$category = '';
		foreach($category_id_list as $id => $value){
			$category .= '<option value="'.$id.'">'.$value.'</option>';	
		}
		return $category;
	}
    /*
	static function get_types(){
		$list_type = DB::fetch_all('select type as id,type as name from product where type<>\'PRODUCT\' AND type<>\'SERVICE\' group by type');	 
		$types = '<option value="">--Type--</option>';
		foreach($list_type as $id => $value){
			$types .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		return $types;
	}
    */
    static function get_types()
    {
        //require_once 'cache/config/type.php';
        //$list_type = unserialize(LIST_TYPE);
        $list_type = array('GOODS'=>'GOODS','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','TOOL'=>'TOOL','DRINK'=>'DRINK');	 
		$types = '<option value="">--Type--</option>';
		foreach($list_type as $id => $value){
			$types .= '<option value="'.$id.'">'.$id.'</option>';	
		}
		return $types;
	}
}
?>