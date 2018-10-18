<?php
class EditCustomerGroupForm extends Form
{
	function EditCustomerGroupForm()
	{
		Form::Form('EditCustomerGroupForm');
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
				$this->old_value = DB::select('vending_customer_group','id='.Url::iget('id').'');
			}
			else
			{
				if(Url::get('code') and DB::select('vending_customer_group','code=\''.addslashes(trim(strtoupper(Url::get('code')))).'\''))
				{
					$this->error('code','duplicate_vending_customer_group_code');	
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
			'parent_id_list'=>String::get_list(VendingCustomerGroupDB::check_categories($this->parents)),
			'parent_id'=>($this->edit_mode?si_parent_id('vending_customer_group',$this->init_value['structure_id']):1),
			'title'=>Url::get('cmd')=='edit'?Portal::language('vending_customer_group_edit'):Portal::language('vending_customer_group_add')
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
		$module_name = VendingCustomerGroupDB::update_module('VCG',strtoupper(Url::get('code')),Url::get('name'),'CustomerGroup','CustomerGroup list');
		$new_row = array('name','code'=>trim(strtoupper(Url::get('code'))),'module_name'=>$module_name,'portal_id'=>PORTAL_ID);
		if(URL::get('cmd')=='edit')
		{
			$this->id = $_REQUEST['id'];
			DB::update_id('vending_customer_group', $new_row,$this->id);
			if($this->old_value['structure_id']!=ID_ROOT)
			{
				if(Url::check(array('parent_id')))
				{
					$parent = DB::select('vending_customer_group',$_REQUEST['parent_id']);					
					if($parent['structure_id']==$this->old_value['structure_id'])
					{
						$this->error('id','invalid_parent');
					}
					else
					{
						require_once 'packages/core/includes/system/si_database.php';
						if(!si_move('vending_customer_group',$this->old_value['structure_id'],$parent['structure_id']))
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
				$this->id = DB::insert('vending_customer_group', $new_row+array('structure_id'=>si_child('vending_customer_group',structure_id('vending_customer_group',$_REQUEST['parent_id']))));			
			}
			else
			{
				$this->id = DB::insert('vending_customer_group', $new_row+array('structure_id'=>ID_ROOT));		
			}				
		}
	}
	function init_edit_mode()
	{
		if(URL::get('cmd')=='edit' and $this->init_value= DB::fetch('select * from vending_customer_group where id='.URL::iget('id').''))
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
		$this->parents = $this->get_vending_customer_group();		
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
}
?>