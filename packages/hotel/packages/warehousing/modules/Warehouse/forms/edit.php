<?php
class EditWarehouseForm extends Form
{
	function EditWarehouseForm()
	{
		Form::Form('EditWarehouseForm');
		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
		if(URL::get('cmd')=='edit')
		{
			//$this->add('id',new IDType(true,'object_not_exists','warehouse'));
		}
		else
		{
			$this->add('code',new TextType(true,'invalid_code',0,255));
		}
		$this->add('name',new TextType(true,'invalid_name',0,255));
	}
	function on_submit()
	{
		if($this->check())
		{
			if(URL::get('cmd')=='edit')
			{
				$this->old_value = DB::select('warehouse','id='.Url::iget('id').'');
			}
			else
			{
				if(Url::get('code') and DB::select('warehouse','code=\''.addslashes(trim(strtoupper(Url::get('code')))).'\' AND portal_id=\''.PORTAL_ID.'\''))
				{
					$this->error('code','duplicate_warehouse_code');	
					return;
				}				
			}
			$this->save_item();
			Url::redirect_current();
		}
	}	
	function draw()
	{
        //echo $this->get_parent_root( DB::fetch('select structure_id from warehouse where id = '.Url::get('id'),'structure_id') );
		$this->init_edit_mode();
		$this->get_parents();
		$this->parse_layout('edit',
			($this->edit_mode?$this->init_value:array())+
			array(
			'parent_id_list'=>String::get_list(WarehouseDB::check_categories($this->parents)),
			'parent_id'=>($this->edit_mode?si_parent_id('warehouse',$this->init_value['structure_id']):1),
			'title'=>Url::get('cmd')=='edit'?Portal::language('warehouse_edit'):Portal::language('warehouse_add')
			)
		);
	}
    
    function get_parent_root($structure_id)
    {
        if($structure_id == ID_ROOT)
        {
            return $structure_id;
        }
        else
        {
            $leng = strlen(ID_ROOT);
            $root = substr($structure_id,0,4);
            for($i=1; $i <= ( $leng -4 ); $i++)
            {
                $root .= "0";
            }
            return $root;
            
        }
    }
    
	function save_item()
	{
        //Su dung code cua warehouse de generate module name, name de tao ra category
		$module_name = WarehouseDB::update_module('WH',strtoupper(Url::get('code')),Url::get('name'),'warehousing','Warehouse list');
		$new_row = array('name','code'=>trim(strtoupper(Url::get('code'))),'module_name'=>$module_name,'portal_id'=>PORTAL_ID);
		if(URL::get('cmd')=='edit')
		{
			$this->id = $_REQUEST['id'];
			DB::update_id('warehouse', $new_row,$this->id);
			if($this->old_value['structure_id']!=ID_ROOT)
			{
				if(Url::check(array('parent_id')))
				{
					$parent = DB::select('warehouse',$_REQUEST['parent_id']);					
					if($parent['structure_id']==$this->old_value['structure_id'])
					{
						$this->error('id','invalid_parent');
					}
					else
					{
						require_once 'packages/core/includes/system/si_database.php';
						if(!si_move('warehouse',$this->old_value['structure_id'],$parent['structure_id']))
						{
							$this->error('id','invalid_parent');
						}
					}
				}
			}
		}
		else
		{
			require_once 'packages/core/includes/system/si_database.php';
			if(isset($_REQUEST['parent_id']))
			{	
				$this->id = DB::insert('warehouse', $new_row+array('structure_id'=>si_child('warehouse',structure_id('warehouse',$_REQUEST['parent_id']))));			
			}
			else
			{
				$this->id = DB::insert('warehouse', $new_row+array('structure_id'=>ID_ROOT));		
			}				
		}
	}
	function init_edit_mode()
	{
		if(URL::get('cmd')=='edit' and $this->init_value= DB::fetch('select * from warehouse where id='.URL::iget('id').''))
		{		
			foreach($this->init_value as $key=>$value)
			{
				if(is_string($value) and !isset($_REQUEST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}
			$this->edit_mode = true;
		}
		else
		{
			$this->edit_mode = false;
		}
	}
	function get_parents()
	{
		require_once 'packages/core/includes/system/si_database.php';
		require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
		$this->parents = get_warehouse();		
	}
}
?>