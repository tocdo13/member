<?php
class EditPageAdminForm extends Form
{
	function EditPageAdminForm()
	{
		Form::Form('EditPageAdminForm');
		//$this->add('name',new IDType(true,'invalid_name_id','PAGE')); 
		$this->add('name',new TextType(true,'invalid_name',0,255)); 
		$this->add('cache_param',new TextType(false,'invalid_cache_param',0,255)); 
		$this->add('params',new TextType(false,'invalid_params',0,255)); 
		$this->add('package_id',new IDType(true,'invalid_package_id','PACKAGE')); 
		//$this->add('layout_id',new IDType(true,'invalid_layout_id','layout')); 
		$this->add('condition',new TextType(false,'invalid_condition',0,1000)); 
		$languages = DB::select_all('LANGUAGE');
		foreach($languages as $language)
		{
			$this->add('title_'.$language['id'],new TextType(false,'invalid_title',0,2000)); 
			$this->add('description_'.$language['id'],new TextType(false,'invalid_description',0,200000)); 
		}
		$this->link_css(Portal::template('core').'/css/admin.css');
		$this->link_css(Portal::template('core').'/css/tabs/tabpane.css');
	}
	function on_submit()
	{
		if(URL::get('cmd')=='edit')
		{
			$row = DB::select('PAGE',$_REQUEST['id']);
		}
		if($this->check() and URL::get('confirm_edit'))
		{		
			$extra = array();
			$languages = DB::select_all('LANGUAGE','ID=1');
			foreach($languages as $language)
			{
				$extra=$extra+array('title_'.$language['id']=>Url::get('title_'.$language['id'],1)); 
				$extra=$extra+array('description_'.$language['id']=>Url::get('description_'.$language['id'],1)); 
			}
			$new_row = $extra+
				array(
					'package_id', 'TYPE', 'condition',
					'name', 'cachable', 'cache_param', 'params', 'is_use_sapi', 'layout'=>Url::get('layout')
				);
			if(URL::get('cmd')=='edit')
			{
				$id = $_REQUEST['id'];
				DB::update_id('PAGE', $new_row,$id);
				require_once 'packages/core/includes/portal/update_page.php';
				update_page($id);
			}
			else
			{
				require_once 'packages/core/includes/system/si_database.php';
				/*if(preg_match('/portal=(\w+)/',URL::get('params'),$patterns))
				{
					$new_row['portal_id'] = '#'.$patterns[1];
				}*/
				$id = DB::insert('PAGE', $new_row);
			}
			Url::redirect_current(array('portal_id','package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'',  
	'name'=>isset($_GET['name'])?$_GET['name']:'',    
	  )+array('just_edited_id'=>$id));
		}
	}	
	function draw()
	{	
		require_once 'packages/core/includes/utils/mce_editor.php';
		$languages = DB::select_all('LANGUAGE');
		if(URL::get('cmd')=='edit' and $row=DB::select('PAGE',URL::sget('id')))
		{
			foreach($row as $key=>$value)
			{
				if(is_string($value) and !isset($_POST[$key]))
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
		DB::query(
			'SELECT
				ID, 
				name,
				STRUCTURE_ID
			FROM 
				PACKAGE
			ORDER BY 
				STRUCTURE_ID'
		);
		$package_id_list = String::get_list(DB::fetch_all());		
		$this->parse_layout('edit',
			($edit_mode?$row:array())+
			array(
			'languages'=>$languages,
			'package_id_list'=>$package_id_list, 
			'layout_list'=>$this->get_all_layouts(), 
			'type_list'=>array('SYSTEM'=>'SYSTEM','SERVICE'=>'SERVICE','CLIENT'=>'CLIENT')
			)
		);
	}
	function get_all_layouts()
	{
		require_once 'packages/core/includes/portal/package.php';
		global $packages;
		$layouts = array();
		foreach($packages as $package)
		{
			if(is_dir($package['path'].'layouts'))
			{
				$dir = opendir($package['path'].'layouts');
				while($file = readdir($dir))
				{
					if($file != '.' and $file != '..' and is_file($package['path'].'layouts/'.$file))
					{
						$layouts[$package['path'].'layouts/'.$file] = $package['path'].'/'.substr($file, 0, strrpos($file,'.'));
					}
				}
				closedir($dir);
			}
		}
		return $layouts;
	}
}
?>