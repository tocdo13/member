<?php
class ListWarehouseForm extends Form
{
	function ListWarehouseForm()
	{
		Form::Form('ListWarehouseForm');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');

        
	}
	function draw()
	{
        //System::debug($sql);
        //$arr = DB::fetch_all('Select * from warehouse where '.$sql);
        //System::debug($arr);
        //$sql = DB::fetch('select * from warehouse where id = 29','structure_id');
        //$cha =  ListWarehouseForm::get_parent_root($sql);
        //echo $cha.'aaaaaa';
        //System::debug(DB::fetch_all('select * from warehouse where structure_id = '.$cha));
		$this->get_items();
		$items=WarehouseDB::check_categories($this->items);
		$this->parse_layout('list',array('items'=>$items,));
	}
	function get_items()
	{
		//$this->get_select_condition($portal_id);
		require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
		$this->items = get_warehouse();
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);		
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
            if(DB::fetch('Select * from warehouse where structure_id = '.$wh_structure_parent.' and structure_id != '.ID_ROOT))
            {
                ListWarehouseForm::get_parent_root($wh_structure_parent);
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