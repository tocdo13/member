<?php
class EditCustomerGroupForm extends Form
{
	function EditCustomerGroupForm()
	{
		Form::Form('EditCustomerGroupForm');
		$this->add('id',new UniqueType(true,'miss_code','customer_group','id'));
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit()
	{
		if($this->check())
		{
			$array = array('id'=>Url::sget('id'),'name'=>Url::get('name'),'show_price'=>Url::get('show_price')?1:0);
			if(URL::get('cmd')=='edit' and Url::get('id') and $row = DB::fetch('select id,structure_id from customer_group where id =\''.Url::sget('id').'\''))
			{
				$id = Url::sget('id');
				DB::update_id('customer_group', $array,$id);
				if($row['structure_id']!=ID_ROOT){
					if (Url::check(array('parent_id')))
					{
						$parent = DB::select('customer_group',Url::sget('parent_id'));
						if($parent['structure_id']==$row['structure_id'])
						{
							$this->error('id','invalid_parent');
						}
						else
						{
							require_once 'packages/core/includes/system/si_database.php';
							if(!si_move('customer_group',$row['structure_id'],$parent['structure_id']))
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
					DB::insert('customer_group',$array+array('structure_id'=>si_child('customer_group',structure_id('customer_group',$_REQUEST['parent_id']),'')));
				}
				else
				{
					DB::insert('customer_group',$array+array('structure_id'=>ID_ROOT));
				}	
			}
			Url::redirect_current(array('just_edited_id'=>$id));
		}
	}	
	function draw()
	{	
		if(URL::get('cmd')=='edit' and $row=DB::select('customer_group',URL::sget('id')))
		{
			foreach($row as $key=>$value)
			{
				if(is_string($value) and !isset($_REQUEST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}
			$edit_mode = true;
		}
		else
		{
			$edit_mode = false;
		}
				DB::query('
			select 
				id,
				structure_id
				,name
                ,show_price
			from 
				customer_group
			order by structure_id
		');
		$parents = DB::fetch_all();
		require_once 'packages/core/includes/system/si_database.php';
		DB::query('
			select 
				id,structure_id,name,show_price
			from 
			 	customer_group
			order by 
				structure_id
		');
		$parents = DB::fetch_all();
		$this->parse_layout('edit',
			($edit_mode?$row:array())+
			array(
			'parent_id_list'=>String::get_list($parents),
				'parent_id'=>($edit_mode?si_parent_id('customer_group',$row['structure_id']):1),
			)
		);
	}
}
?>