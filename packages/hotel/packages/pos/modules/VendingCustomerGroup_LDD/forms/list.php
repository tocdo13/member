<?php
class ListCustomerGroupForm extends Form
{
	function ListCustomerGroupForm()
	{
		Form::Form('ListCustomerGroupForm');
        $this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
	}
	function draw()
	{
        $this->get_items();
		$items=VendingCustomerGroupDB::check_categories($this->items);
		$this->parse_layout('list',array('items'=>$items,));
	}
    function get_items()
	{
		//$this->get_select_condition($portal_id);
		$this->get_vending_customer_group();
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);		
	}
    function get_vending_customer_group($function = false,$cond='1=1')
    {
        if(!$function)
        {
        	return DB::fetch_all('
        		select 
        			vending_customer_group.*
        		from 
        		 	vending_customer_group
        		where
        			 '.$cond.'
                      and portal_id = \''.PORTAL_ID.'\'
                      or id = 1
        		order by 
        			vending_customer_group.structure_id
        	',false); 
        }
        else
        {
        	return DB::fetch_all('
        			select 
        				vending_customer_group.*
        			from 
        			 	vending_customer_group
        			where
        				 '.$cond.'
                          and portal_id = \''.PORTAL_ID.'\'
                          and structure_id !='.ID_ROOT.'
        			order by 
        				vending_customer_group.structure_id
        		',false);
        }
    	
    }
    static function get_parent_root($wh_structure)
    {
        echo 4;
        echo $wh_structure.'con<br />';
        //$wh_structure = DB::fetch('select * from warehouse where id = '.$warehouse_id.'','structure_id');
        if($wh_structure != ID_ROOT)
        {
            $wh_structure_parent = IDStructure::parent($wh_structure);
            echo $wh_structure_parent.'cha<br />';
            if(DB::fetch('Select * from vending_customer_group where structure_id = '.$wh_structure_parent.' and structure_id != '.ID_ROOT))
            {
                ListCustomerGroupForm::get_parent_root($wh_structure_parent);
            }
            else
            {
                echo $wh_structure.'final<br />';
                return $wh_structure;
            }
        }
        else
        {
             return $wh_structure;
        }
        
    }
}
?>