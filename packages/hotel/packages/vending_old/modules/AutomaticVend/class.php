<?php 
class AutomaticVend extends Module
{
    public static $department_id ='';
	
    function AutomaticVend($row)
	{
		Module::Module($row);
		require_once 'db.php';
		define('AFTER_TAX',0);// Mac dinh laf giam gia truoc thue va phi dich vu
		$_REQUEST['after_tax'] = AFTER_TAX;
        AutomaticVend::$department_id = Url::get('department_id');
        if(User::can_view(false,ANY_CATEGORY))
        {
            switch(URL::get('cmd'))
    		{	
    			case 'detail':
    					require_once 'forms/checkio_detail.php';
    					$this->add_form(new DetailBarForm());break;	
    			case 'draw_products':
                        if(Url::get('category_id'))
                        {
                            $this->draw_products(Url::get('type'),Url::iget('category_id'),Url::get('bar_id_other'),Url::get('act'));
                            exit();break;
                        }
                        else
                        {
                            $this->draw_products(Url::get('type'),Url::get('product_name'),Url::get('bar_id_other'),Url::get('act'));
                            exit();break;
                        }					
    			default: 
    				$this->list_cmd();
    				break;
    		}
        }
        else
        {
            Url::access_denied();
        }


	}
    
	function draw_products($type,$value,$bar_id='',$action)
    {
		if($type=='CATEGORY')
        {
			$category_id = $value;
			$cond = ''.IDStructure::child_cond(DB::structure_id('product_category',$category_id)).'';
		}
        else
        {
			$cond = ' ((UPPER(product.name_1) like \'%'.mb_strtoupper($value,'utf-8').'%\' OR UPPER(product.name_2) like \'%'.mb_strtoupper($value,'utf-8').'%\')
			OR (UPPER(product.id) like \'%'.mb_strtoupper($value,'utf-8').'%\'))';
			$check= String::vn_str_check($value);
			if($check==0)
            {
				$value = String::vn_str_filter($value);
				$cond .= ' OR ((LOWER(FN_CONVERT_TO_VN(product.name_2)) like \'%'.mb_strtolower($value,'utf-8').'%\' OR LOWER(FN_CONVERT_TO_VN(product.name_1)) like \'%'.mb_strtolower($value,'utf-8').'%\'))'; 	
			}
		}
        
        $cond2 = '';
        $surcharges = array();
		if($bar_id)
        {
			$surcharges = DB::fetch('select *  from department where id = '.$bar_id.' ');
			$cond2 = ' AND product_price_list.department_code = \''.$surcharges['code'].'\'';		  
		}
        else
        {
            $cond2 = '  AND product_price_list.department_code = \'VENDING\' ';
        }
		$sql = '
			SELECT
				product_price_list.id as id,
                product.id as code,
                product.name_'.Portal::language().' as name,
				product.name_2,
				product_price_list.price, 
                unit.name_'.Portal::language().' as unit,
				pc.structure_id,
                pc.name AS pc_name,
                product.category_id,
                product.unit_id
			FROM 
				product_price_list
				INNER JOIN product ON product_price_list.product_id = product.id
				INNER JOIN product_category pc ON pc.id = product.category_id
				INNER JOIN unit ON unit.id = product.unit_id
			WHERE 
				('.$cond.')
				'.$cond2.'
				AND (product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\'  OR product.type = \'SERVICE\')
				AND product_price_list.portal_id = \''.PORTAL_ID.'\'
			ORDER BY
				product.name_'.Portal::language().' ';
        //System::debug($sql);    
		$items = DB::fetch_all($sql);
		//echo '<div id="bound_product">';
		if(!empty($items))
        {  
			$temp = '';
		echo '<script src="packages/core/includes/js/jquery/jquery.cookie.js"></script>
        <script src="packages/core/includes/js/jquery/paging/easypaginate.js"> </script>';
        
        echo '<script>paging(24);</script>';
        
		if($bar_id)
        {
            //echo 'src="cache/data/'.str_replace("#","",PORTAL_ID).'/'.$surcharges['code'].'_'.str_replace("#","",PORTAL_ID).'.js?v='.time() ;
			echo '<script src="cache/data/'.str_replace("#","",PORTAL_ID).'/'.$surcharges['code'].'_'.str_replace("#","",PORTAL_ID).'.js?v='.time().'"></script>';
			echo '<script>jQuery(\'#restaurant_other\').css(\'display\',\'block\');</script>';	
		}
        else
        {
			echo '<script src="cache/data/'.str_replace("#","",PORTAL_ID).'/VENDING_'.str_replace("#","",PORTAL_ID).'.js?v='.time().'"></script>';
		}
        //echo $action.'<br />';
        //echo $bar_id;
		if($action =='this_bar' or $action =='bar')
        { //$bar_id != Session::get('bar_id') || 
			$food_categories = AutomaticVendDB::select_list_food_category($bar_id);
			$other_categories = AutomaticVendDB::select_list_other_category($bar_id);
			echo '<script>jQuery(\'#div_food_category\').html(\''.$food_categories.'\');jQuery(\'#info_summary\').html(\''.$other_categories.'\');</script>';	
		}
        echo '<ul id="bound_product_list">';
        foreach($items as $id => $itm)
        {
			echo '<li id="product_'.$itm['id'].'" class="product-list" title="'.ucfirst($itm['name']).'" onclick=" SelectedItems(\''.$itm['id'].'\',0);">'.ucfirst($itm['code']).'<br>'.ucfirst($itm['name']).'<br>'.System::display_number($itm['price']).'</li>';	
		}
		echo '</ul>';
		}else echo '<div id="alert" class="notice">'.Portal::language('has_no_item_to_be_found').' !</div>';
		return $items;
        
	}
	function list_cmd()
	{
		if(User::can_view(false,ANY_CATEGORY))
		{
			require_once 'forms/list.php';
			$this->add_form(new TouchBarRestaurantForm());
		}	
		else
		{
			Url::access_denied();
		}
	}	
}
?>