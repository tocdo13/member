<?php
class EditZoneForm extends Form
{
	function EditZoneForm()
	{
		Form::Form('EditZoneForm');
		$this->add('name_1',new TextType(true,'miss_name',0,255));
		$this->add('name_2',new TextType(true,'miss_name',0,255));
		$this->add('brief_name_1',new TextType(true,'miss_code',0,255));
		$this->add('brief_name_2',new TextType(true,'miss_code',0,255));
	}
	function on_submit()
	{
		if($this->check())
		{
			$array = array('name_1','name_2','brief_name_1','brief_name_2');
			if(URL::get('cmd')=='edit' and Url::get('id') and $row = DB::fetch('select id,structure_id from zone where id =\''.Url::sget('id').'\''))
			{
				$id = Url::sget('id');
				DB::update_id('zone', $array,$id);
				if($row['structure_id']!=ID_ROOT){
					if (Url::check(array('parent_id')))
					{
						$parent = DB::select('zone',Url::sget('parent_id'));
						if($parent['structure_id']==$row['structure_id'])
						{
							$this->error('id','invalid_parent');
						}
						else
						{
							require_once 'packages/core/includes/system/si_database.php';
							if(!si_move('zone',$row['structure_id'],$parent['structure_id']))
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
					DB::insert('zone',$array+array('structure_id'=>si_child('zone',structure_id('zone',$_REQUEST['parent_id']),'')));
				}
				else
				{
					DB::insert('zone',$array+array('structure_id'=>ID_ROOT));
				}	
			}
			Url::redirect_current(array('just_edited_id'=>$id));
		}
	}	
	function draw()
	{	
		if(URL::get('cmd')=='edit' and $row=DB::select('zone',URL::sget('id')))
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
		require_once 'packages/core/includes/system/si_database.php';
		DB::query('
			select 
				id,structure_id,name_'.Portal::language().' as name
			from 
			 	zone
			where
				structure_id = '.ID_ROOT.'
			order by 
				structure_id
		');
		$parents = DB::fetch_all();
		$this->parse_layout('edit',
			($edit_mode?$row:array())+
			array(
			'parent_id_list'=>String::get_list($parents)
			)
		);
	}
}
?>