<?php
class EditManageHelpForm extends Form
{
	function EditManageHelpForm()
	{
		Form::Form('EditManageHelpForm');
		if(URL::get('cmd')=='edit')
		{
			$this->add('id',new IDType(true,'object_not_exists','help_content'));
		}
		$languages = DB::fetch_all('select * from language',false);
		foreach($languages as $language)
		{
			$this->add('name_'.$language['id'],new TextType(true,'invalid_name',0,2000)); 
			$this->add('description_'.$language['id'],new TextType(false,'invalid_description',0,200000)); 
		}
		$this->link_css(Portal::template('core').'/css/tabs/tabpane.css');
		$this->link_js('packages/core/includes/js/tinymce/jscripts/tiny_mce/tiny_mce.js');		
		$this->link_js('packages/core/includes/js/tinymce/init_tinyMCE.js');
	}
	function on_submit()
	{
		require_once 'packages/core/includes/utils/upload_file.php';
		update_upload_file('file_attachment_file','default/help_files');
		if($this->check() and URL::get('confirm_edit'))
		{
			if(URL::get('cmd')=='edit')
			{
				$this->old_value = DB::select('help_content','id=\''.addslashes($_REQUEST['id']).'\'');
			}
			$this->save_item();
			Url::redirect_current(Module::$current->redirect_parameters+array('just_edited_id'=>$this->id));
		}
	}	
	function draw()
	{
		$this->map = array();
		$languages = DB::fetch_all('select * from language',false);
		$this->init_edit_mode();
		$this->get_parents();
		$modules = DB::fetch_all('SELECT id,name FROM module ORDER BY name');
		$this->parse_layout('edit',
			($this->edit_mode?$this->init_value:array())+
			$this->map + array(
			'languages'=>$languages,
			'parent_id_list'=>String::get_list(ManageHelpDB::check_categories($this->parents)),
			'parent_id'=>($this->edit_mode?si_parent_id('help_content',$this->init_value['structure_id']):1),
			'type_list'=>array('FUNCTION'=>Portal::language('function'),'MODERATOR'=>Portal::language('use_for_moderation'),'HELP'=>Portal::language('use_for_help')),
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
				$extra=$extra+array('brief_'.$language['id']=>Url::get('brief_'.$language['id'],1)); 
				$extra=$extra+array('description_'.$language['id']=>str_replace('<p>&nbsp;</p>','',Url::get('description_'.$language['id'],1))); 
			}
			$new_row = $extra+
			array(
				'status', 'attachment_file'=>Url::get('file_attachment_file')?Url::get('file_attachment_file'):Url::get('attachment_file'),
				'check_privilege'=>Url::check('check_privilege')?1:0,
				'portal_id'=>'#default'
			);
			require_once 'packages/core/includes/utils/vn_code.php';
			/*$name_id = convert_utf8_to_url_rewrite($new_row['name_1']); 			
			if(!DB::fetch('select name_id from category where name_id=\''.$name_id.'\''))
			{
				$new_row+=array('name_id'=>$name_id);
			}
			else
			{
				$new_row+=array('name_id'=>$name_id.'_'.date('i-h',time()));
			}
			*/
		if(URL::get('cmd')=='edit')
		{
			$this->id = $_REQUEST['id'];
			if(Url::get('delete_file') and $file_url = DB::fetch('select id,attachment_file from help_content where id = '.$this->id,'attachment_file'))			
			{
				@unlink($file_url);	
			}			
			DB::update_id('help_content', $new_row,$this->id);
			DB::update('help_content',array('status'=>Url::get('status')),IDStructure::child_cond(DB::structure_id('help_content',$this->id)));
			if($this->old_value['structure_id']!=ID_ROOT)
			{
				if (Url::check(array('parent_id')))
				{
					$parent = DB::select('help_content','id='.$_REQUEST['parent_id'].'');	
					if($parent['structure_id']==$this->old_value['structure_id'])
					{
						$this->error('id','invalid_parent');
					}
					else
					{
						require_once 'packages/core/includes/system/si_database.php';
						if(!si_move('help_content',$this->old_value['structure_id'],$parent['structure_id']))
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
				$this->id = DB::insert('help_content', $new_row+array('structure_id'=>si_child('help_content',structure_id('help_content',$_REQUEST['parent_id']))));			
			}
			else
			{
				$this->id = DB::insert('help_content', $new_row+array('structure_id'=>ID_ROOT));		
			}				
		}
	}
	function init_edit_mode()
	{
		if(URL::get('cmd')=='edit' and $this->init_value= DB::fetch('select * from help_content where id='.intval(URL::sget('id')).''))
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
			 	help_content
			where 
				1=1
			order by 
				structure_id
		';
		$this->parents = DB::fetch_all($sql,false);		
	}
}
?>