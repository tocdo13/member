<?php
class ProductPriceDB
{
    static function get_units()
    {
		$list_units = DB::fetch_all('select id,name_'.Portal::language().' as name from unit order by name');	 
		$units = '<option value="">--Unit--</option>';
		foreach($list_units as $id => $value){
			$units .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		return $units;
	}
    
    static function get_types()
    {
        
        //System::debug(unserialize(LIST_TYPE));
        //$list_type = array('GOODS'=>'GOODS','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL');	 
		require_once 'cache/config/type.php';
        $list_type = unserialize(LIST_TYPE);
        $types = '<option value="">--Type--</option>';
		foreach($list_type as $id => $value){
			$types .= '<option value="'.$id.'">'.$id.'</option>';	
		}
		return $types;
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
    
    static function get_department($portal_id=PORTAL_ID)
    {
        $department_list = '';
        $department = DB::fetch_all('Select 
                                        portal_department.id,
                                        department.id as department_id,
                                        portal_department.department_code,
                                        department.name_'.Portal::language().' as name 
                                    from 
                                        department
                                        INNER JOIN portal_department on department.code = portal_department.department_code
                                    where 
                                        department.parent_id = 0 and portal_department.portal_id = \''.$portal_id.'\'
                                    ');
        foreach($department as $key => $value)
        {
            //echo $value['code'];
            //echo $value['name_'.Portal::language().''].'<br />';
            $department_list.= '<option value="'.$value['department_code'].'"> - '.$value['name'].'</option>';	
            $department[$key]['child'] =  DB::fetch_all('
                                                        Select 
                                                            portal_department.id,
                                                            department.id as department_id,
                                                            portal_department.department_code,
                                                            department.name_'.Portal::language().' as name 
                                                        from 
                                                            department
                                                            INNER JOIN portal_department on department.code = portal_department.department_code
                                                        where 
                                                            department.parent_id != 0 and portal_department.portal_id = \''.$portal_id.'\' and department.parent_id = '.$value['department_id'].'
                                                        '
                                                        );
                                                            
            foreach($department[$key]['child'] as $key1 => $value1)
            {
                $department_list.= '<option value="'.$value1['department_code'].'"> --- '.$value1['name'].'</option>';	
            }
        }
        //System::debug($department);
        return $department_list;
        //System::debug($department_list);
    }
    
    static function export_cache()
	{
        //get portal dc chon de xuat cache
        $portal_id =  Url::get('portal_id');
        //get dept trong bang portal_department, khong lay trong product price list vi neu
        // delete het san pham trong 1 dept thi khong con ma dept => ko export dc cache cua dept do
        $department = DB::fetch_all('Select department_code as id from portal_department where portal_id = \''.$portal_id.'\'');
        //System::debug($department);
        
		require_once 'packages/hotel/includes/php/product.php';
        foreach($department as $key => $value)
        {
            $product_price_list = Product::get_product_price($portal_id,$key);
            //System::debug($product_price_list);
            $str = "product_array={'':''";
			foreach($product_price_list as $id=>$product)
			{
                $str.= ",'".$id."':{
'id':"."'".$id."',
'product_id':"."'".$product['product_id']."',
'name':'".addslashes($product['name'])."',
'price':'".$product['price']."',
'start_date':'".($product['start_date']?Date_Time::convert_orc_date_to_date($product['start_date'],'/'):0)."',
'end_date':'".($product['end_date']?Date_Time::convert_orc_date_to_date($product['end_date'],'/'):0)."',
'category_id':"."'".$product['category_id']."',
'unit':'".$product['unit_name']."',
'unit_id':'".$product['unit_id']."',
'type':'".$product['type']."'}
";
			}
			$str.= '}';
			$f = fopen('cache/data/'.str_replace('#','',$portal_id).'/'.$key.'_'.str_replace('#','',$portal_id).'.js','w+');
			fwrite($f,$str);
			fclose($f); 
        }
	}
    
		/*
        $warehouses = DB::fetch_all('
                        			SELECT
                        				id,code
                        			FROM
                        				warehouse
                        			WHERE
                        				'.IDStructure::child_cond(ID_ROOT).' and structure_id<>'.ID_ROOT.'
                                    ');
		
        foreach($warehouses as $key=>$value)
		{
			$cond = ' and (product.type=\'PRODUCT\' or product.type=\'GOODS\' or product.type=\'SERVICE\' or product.type=\'TOOL\' or product.type=\'EQUIPMENT\')';
			$products = Product::get_product_price($key,$cond);
			$str = "product_array={'':''";
			foreach($products as $id=>$product)
			{
				$str.= ",'".$id."':{
'id':"."'".$id."',
'code':"."'".$product['code']."',
'category_id':"."'".$product['category_id']."',
'name':'".addslashes($product['name'])."',
'unit':'".$product['unit_name']."',
'unit_id':'".$product['unit_id']."',
'quantity':'1',
'quantity_discount':'0',
'type':'".$product['type']."',
'price':'".$product['price']."'}
";
			}
			$str.= '}';
			$f = fopen('cache/data/'.strtolower($value['code']).'.js','w+');
			fwrite($f,$str);
			fclose($f);
		}
        
		/*----------- Nguyen Vat Lieu ---------------*/
		/*
        $materials = Product::get_material('','product.type=\'MATERIAL\'');
		$str = "product_array={'':''";
		foreach($materials as $id=>$material)
		{
			$str.= ",'".$id."':{
'id':"."'".$id."',
'code':'".addslashes($material['code'])."',
'name':'".addslashes($material['name'])."',
'price':'".$material['price']."',
'unit':'".$material['unit_name']."'}
";
		}
		$str.= '}';
		$f = fopen('cache/data/MATERIAL.js','w+');
		fwrite($f,$str);
		fclose($f);
		/*----------- /Nguyen Vat Lieu ---------------*/		
		//Url::redirect_current();
    
      
}

?>