<?php
class GrantPrivilegeForm extends Form
{
	function GrantPrivilegeForm()
	{
		Form::Form('GrantPrivilegeForm');
		$this->add('id',new TextType(true,'invalid_user_id',0,50));
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function on_submit()
	{		
		if(Url::get('save') and $this->check())
		{
			$portal_id = Url::get('portal_id');
			if(Url::get('id') and !DB::exists_id('privilege_group',Url::sget('id')))
			{
				$this->error('account_id','account_id_not_exist');
				return;	
			}
			if($portal_id == 'ALL'){
				$portals = Portal::get_portal_list();
				foreach($portals as $key=>$value){
					$this->update_privilege($key);
				}
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating all portal to server...</div>';
				echo '<script>window.setTimeout("location=\''.URL::build('manage_user').'\'",2000);</script>';
				exit();
			}else{
				$this->update_privilege($portal_id);
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating to server...</div>';
				echo '<script>window.setTimeout("location=\''.URL::build_current(array('cmd','portal_id','account_id')).'\'",2000);</script>';
				exit();
			}
		}else{
			Url::redirect_current(array('cmd','portal_id','id'));
		}
	}
	function draw()
	{		
		if(Url::get('id')){
			$sql = '
				SELECT
					privilege_group.id,privilege_group.name_1 as name
				FROM
					privilege_group 
				WHERE
					privilege_group.id = \''.Url::get('id').'\'
					
			';
			$privilege = DB::fetch($sql);
			/*if(!isset($_REQUEST['portal_id'])){
				$_REQUEST['portal_id'] = $user_portal_id;
			}*/
		}
		$portal_id = Url::get('portal_id');
        if(Url::get('cmd')=='grant' && Url::get('id') && $privileges = PrivilegeDB::get_privilege(Url::sget('id'),$portal_id))
		{
			foreach($privileges as $key=>$value)
			{
				if(!isset($_REQUEST['module_'.$value['id']]))
				{
					$_REQUEST['privilege_group_id_'.$value['id'].'_'] = $value['privilege_group_id'];
					$_REQUEST['module_'.$value['id'].'_'] = $value['module_id'];
					$_REQUEST['show_'.$value['id'].'_'] = $value['can_view'];
					$_REQUEST['view_'.$value['id'].'_'] = $value['can_view_detail'];
					$_REQUEST['add_'.$value['id'].'_'] = $value['can_add'];
					$_REQUEST['edit_'.$value['id'].'_'] = $value['can_edit'];
					$_REQUEST['delete_'.$value['id'].'_'] = $value['can_delete'];
					$_REQUEST['admin_'.$value['id'].'_'] = $value['can_admin'];
					$_REQUEST['reserve_'.$value['id'].'_'] = $value['can_reserve'];
					$_REQUEST['special_'.$value['id'].'_'] = $value['can_special'];
				}
			}
		}
		$categories = PrivilegeDB::get_categories(1);
	//	$tree_categories = String::array2tree($categories,'child');
		require_once 'packages/core/includes/utils/category.php';
		category_indent($categories);
		$portal = DB::fetch('select account.id,party.name_1 as name from account INNER JOIN party on party.user_id=account.id where account.id=\''.addslashes(Url::get('portal_id')).'\' and account.type=\'PORTAL\' and party.type=\'PORTAL\'');
		$this->parse_layout('grant',array(
			'portal_id_list'=>array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
			'users'=>PrivilegeDB::get_users(),
			'portal_name'=>$portal['name'],
			'portal_id'=>$portal['id'],
			'privilege_name'=>$privilege['name'],
			'items'=>$categories
		));
	}
	function update_privilege($portal_id){
		$privileges = array();
		$ids = '0';
		foreach($_REQUEST as $key=>$value)
		{
			if(preg_match('/show\_([0-9]+)/',$key,$match) && isset($match[1]))
			{
				$category_id = $match[1];
				$privilege = array();
				$privilege_module = array(
					'privilege_group_id'=>Url::get('id'),
					'category_id'=>$category_id,
					'module_id'=>Url::get('module_'.$category_id.'_'),
					'can_view'=>isset($_REQUEST['show_'.$category_id.'_'])?1:0,
					'can_view_detail'=>isset($_REQUEST['view_'.$category_id.'_'])?1:0,
					'can_add'=>isset($_REQUEST['add_'.$category_id.'_'])?1:0,
					'can_edit'=>isset($_REQUEST['edit_'.$category_id.'_'])?1:0,
					'can_delete'=>isset($_REQUEST['delete_'.$category_id.'_'])?1:0,
					'can_reserve'=>isset($_REQUEST['reserve_'.$category_id.'_'])?1:0,
					'can_special'=>isset($_REQUEST['special_'.$category_id.'_'])?1:0,
					'can_admin'=>isset($_REQUEST['admin_'.$category_id.'_'])?1:0,
					'portal_id'=>$portal_id
				);
				$privilege_id = Url::iget('id');
				if(Url::get('cmd')=='grant' && Url::get('id') && Url::get('privilege_group_id_'.$category_id.'_') && $row = DB::select('privilege_group_detail','portal_id = \''.$portal_id.'\' and privilege_group_id='.$privilege_id.' AND category_id='.$category_id))
				{
					$privilege_group_detail = $row['id'];
					DB::update('privilege_group_detail',$privilege_module,'portal_id = \''.$portal_id.'\' and privilege_group_id='.$privilege_id.' AND category_id='.$category_id);
				}
				else
				{
					$privilege_module['privilege_group_id'] = Url::iget('id');
					$privilege_group_detail = DB::insert('privilege_group_detail',$privilege_module);
				}	
				//PrivilegeDB::update_moderator(URL::get('account_id'),$privilege_id,$category_id,$portal_id);
				/*if($structure_id = DB::fetch('select structure_id as id from category where id='.$category_id,'id'))
				{
					$privileges[$structure_id] =  $structure_id;
				}	*/
				$ids .= ','.$privilege_group_detail;
				
			}
		}
		if($delete_ids = DB::fetch_all('select id from privilege_group_detail where id not in ('.$ids.') and privilege_group_id='.Url::sget('id').' and category_id!=0'))
		{
			foreach($delete_ids as $id)
			{
				DB::delete('privilege_group_detail','portal_id = \''.$portal_id.'\' and id ='.$id['id']);
			}	
		}
		require_once 'packages/core/includes/system/make_user_privilege_cache.php';
		$account_privilege = DB::fetch_all('select distinct account_id as id,group_privilege_id,portal_id from account_privilege_group where group_privilege_id='.Url::iget('id').' AND portal_id=\''.$portal_id.'\'');
		foreach($account_privilege as $key=>$value)
		{
			make_user_privilege_cache($key,$value['portal_id']);
		}
		Portal::set_setting('privilege',var_export($privileges,true),Url::sget('account_id'));
		$this->export($ids,$portal_id);//exit();
	}
	function export($privilege_ids,$portal_id)
	{
		$cond = 'category.status<>\'HIDE\' and privilege.id in ('.$privilege_ids.')';
		$categogies = $this->get_categories($cond);
		$path = 'cache/portal/'.str_replace('#','',$portal_id).'/category.php';
		$hand = fopen($path,'w+');
		fwrite($hand,'<?php $categories = '.var_export($categogies,true).';?>');
		fclose($hand);
	}
	function get_categories($cond = false)
	{
		return DB::fetch_all('select category.* from category inner join privilege on privilege.category_id = category.id where category.structure_id <>'.ID_ROOT.' '.($cond?' AND '.$cond:'').' order by category.structure_id');
	}	
}
?>