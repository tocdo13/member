<?php
class EditHelpListForm extends Form
{
	function EditHelpListForm()
	{
		Form::Form('EditHelpListForm');
		if(URL::get('cmd')=='edit')
		{
			$this->add('id',new IDType(true,'object_not_exists','help_list'));
		}
		$this->add('type',new IDType(true,'invalid_type','type')); 
		$languages = DB::fetch_all('select * from language',false);
		foreach($languages as $language)
		{
			$this->add('name_'.$language['id'],new TextType(true,'invalid_name',0,2000)); 
			$this->add('description_'.$language['id'],new TextType(false,'invalid_description',0,200000)); 
		}
		$this->link_css(Portal::template('core').'/css/tabs/tabpane.css');
		$this->link_js('packages/core/includes/js/init_tinyMCE.js');		
		$this->link_js('packages/core/includes/js/tinymce/jscripts/tiny_mce/tiny_mce.js');
	}
	function on_submit()
	{
		//System::debug($_REQUEST);
		require_once 'packages/core/includes/utils/upload_file.php';
		update_upload_file('icon_url',str_replace('#','',PORTAL_ID).'/');
		if($this->check() and URL::get('confirm_edit'))
		{
				if(URL::get('cmd')=='edit')
				{
					$this->old_value = DB::select('help_list','id=\''.addslashes($_REQUEST['id']).'\'');
				}
				$this->save_item();
				Url::redirect_current(Module::$current->redirect_parameters+array('just_edited_id'=>$this->id));
		}
	}	
	function draw()
	{
		$languages = DB::fetch_all('select * from language',false);
		$this->init_edit_mode();
		$this->get_parents();
		$this->init_database_field_select();
		$this->parse_layout('edit',
			($this->edit_mode?$this->init_value:array())+
			array(
			'languages'=>$languages,
			'parent_id_list'=>String::get_list(HelpListDB::check_categories($this->parents)),
			'parent_id'=>($this->edit_mode?si_parent_id('help_list',$this->init_value['structure_id']):1),
			'type_list'=>$this->type_list, 
			'status_list'=>array('SHOW'=>'SHOW','HIDE'=>'HIDE','HOME'=>'HOME')
			)
		);
	}
	function save_item()
	{
			$extra = array();
			$languages = DB::fetch_all('select * from language',false);;
			foreach($languages as $language)
			{
				$extra=$extra+array('name_'.$language['id']=>Url::get('name_'.$language['id'],1)); 
				$extra=$extra+array('description_'.$language['id']=>htmlentities(Url::get('description_'.$language['id'],1))); 
			}
			$new_row = $extra+
			array(
			'type', 
				'status', 'icon_url');
			require_once 'packages/core/includes/utils/vn_code.php';
			$name_id = convert_utf8_to_url_rewrite($new_row['name_1']); 			
			if(!DB::fetch('select name_id from help_list where name_id=\''.$name_id.'\''))
			{
				$new_row+=array('name_id'=>$name_id);
			}
			else
			{
				$new_row+=array('name_id'=>$name_id.'_'.date('i-h',time()));
			}
		//System::debug($new_row);
		//exit();	
		if(URL::get('cmd')=='edit')
		{
			$this->id = $_REQUEST['id'];
			DB::update_id('help_list', $new_row,$this->id);
			if($this->old_value['structure_id']!=ID_ROOT)
			{
				if (Url::check(array('parent_id')))
				{
					$parent = DB::select('help_list',$_REQUEST['parent_id']);					
					if($parent['structure_id']==$this->old_value['structure_id'])
					{
						$this->error('id','invalid_parent');
					}
					else
					{
						require_once 'packages/core/includes/system/si_database.php';
						if(!si_move('help_list',$this->old_value['structure_id'],$parent['structure_id']))
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
				$this->id = DB::insert('help_list', $new_row+array('structure_id'=>si_child('help_list',structure_id('help_list',$_REQUEST['parent_id']))));			
			}
			else
			{
				$this->id = DB::insert('help_list', $new_row+array('structure_id'=>ID_ROOT));		
			}				
		}
	}
	function init_edit_mode()
	{
		if(URL::get('cmd')=='edit' and $this->init_value= DB::fetch('select * from help_list where id='.intval(URL::sget('id')).''))
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
		$sql = '
			select 
				id,
				structure_id
				,name_'.Portal::language().' as name  
			from 
			 	help_list
			where 
				help_list.type!=\'PORTAL\'
			order by 
				structure_id
		';
		$this->parents = DB::fetch_all($sql,false);		
	}
	function init_database_field_select()
	{
		$sql = 'select
					type.id,
					type.title_'.Portal::language().' as name
				from 
					portal_type,type
				where
					 type.typeoftype=\'ITEM\' 
					 and  portal_type.portal_id=\''.PORTAL_ID.'\'
					 and type.id(+)=portal_type.type
				'
			;
			$this->type_list = String::get_list(DB::fetch_all($sql,false)); 
	}	
}
?>