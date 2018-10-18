<?php
class GrantModeratorForm extends Form
{
	function GrantModeratorForm()
	{
		Form::Form('GrantModeratorForm');
		$this->add('account_id',new TextType(true,'invalid_user_id',0,50));
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function on_submit()
	{
		if(Url::get('save') and $this->check())
		{
			$portal_id = Url::get('portal_id');
			if(Url::get('account_id') and !DB::exists_id('account',Url::sget('account_id')))
			{
				$this->error('account_id','account_id_not_exist');
				return;	
			}
			if($portal_id == 'ALL')
            {
				$portals = DB::fetch_all('
					SELECT 
						account_related.parent_id as id
					FROM 
						account_related 
					WHERE 
						account_related.child_id=\''.Url::get('account_id').'\'
					');
				foreach($portals as $key=>$value)
                {
					if(file_exists('cache/portal/'.str_replace('#','',$key).'/user/'.Url::sget('account_id').'.php'))
                    {
						unlink('cache/portal/'.str_replace('#','',$key).'/user/'.Url::sget('account_id').'.php');
					}
					$this->update_privilege($key);
				}
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating all portal to server...</div>';
				echo '<script>window.setTimeout("location=\''.URL::build('manage_user').'\'",2000);</script>';
				exit();
			}
            else
            {
				if(file_exists('cache/portal/'.str_replace('#','',$portal_id).'/user/'.Url::sget('account_id').'.php'))
                {
					unlink('cache/portal/'.str_replace('#','',$portal_id).'/user/'.Url::sget('account_id').'.php');
				}	
				$this->update_privilege($portal_id);
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating to server...</div>';
				echo '<script>window.setTimeout("location=\''.URL::build_current(array('cmd','portal_id','account_id')).'\'",2000);</script>';
				exit();
			}
		}
        else
        {
			Url::redirect_current(array('cmd','portal_id','account_id'));
		}
	}
	function draw()
	{
	   	if(Url::get('account_id'))
        {
			$sql = '
				SELECT
					account.id,
                    party.name_1 as name
				FROM
					account 
					INNER JOIN account_related ON account_related.parent_id = account.id
					INNER JOIN party ON party.user_id = account.id
				WHERE
					account_related.child_id = \''.Url::get('account_id').'\'
					
			';
			$user_portal_id = DB::fetch($sql,'id');
			if(!isset($_REQUEST['portal_id']))
            {
				$_REQUEST['portal_id'] = $user_portal_id;
			}
			$user_portals = array();
			$user_portals = DB::fetch_all('
			SELECT 
				ACCOUNT_PRIVILEGE_GROUP.portal_id as id
			FROM 
				ACCOUNT_PRIVILEGE_GROUP 
			WHERE 
				ACCOUNT_PRIVILEGE_GROUP.account_id=\''.Url::get('account_id').'\'
			');
		}
		$portal_id = Url::get('portal_id');
		$privileges = ModeratorDB::get_privilege(Url::sget('account_id'),$portal_id);
        //System::debug($privileges);
        if(Url::get('account_id') && $privileges)
		{ 
			foreach($privileges as $key=>$value)
			{
				if(!isset($_REQUEST['module_'.$value['id']]))
				{
					$_REQUEST['privilege_id_'.$value['id'].'_'] = $value['privilege_id'];
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
		$categories = ModeratorDB::get_categories(1);
	//	$tree_categories = String::array2tree($categories,'child');
		require_once 'packages/core/includes/utils/category.php';
		category_indent($categories);
        //System::debug($_REQUEST);
		$this->parse_layout('select_portal',array(
			'portal_id_list'=>array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list($user_portals)),
			'users'=>ModeratorDB::get_users(),													  
			'items'=>$categories
		));
	}
	function update_privilege($portal_id)
    {
        $privileges = array();
		$ids = '0';
        //System::debug($_REQUEST);
        //exit();
		foreach($_REQUEST as $key=>$value)
		{
			if(preg_match('/show\_([0-9]+)/',$key,$match) && isset($match[1]))
			{
				$category_id = $match[1];
                if($category_id != '')
                {
                    //echo $category_id;
                    $current_structure_id_arr = DB::fetch('select STRUCTURE_ID from category where id = '.$category_id);
                    //System::debug($current_structure_id_arr);
                    //exit();
                    $current_structure_id = $current_structure_id_arr['structure_id'];
                    $parent_structure_id = IDStructure::parent($current_structure_id);
                    //echo $parent_structure_id.'aat<br>';
                    while($parent_structure_id != ID_ROOT)
                    {
                        //echo $parent_structure_id.'<br>';
                        $parent_category = DB::fetch('select * from category where structure_id = '. $parent_structure_id);
                        $parent_category_id = $parent_category['id'];
                        $check_exist = DB::fetch('select * from privilege where category_id = '.$parent_category_id.' and account_id = \''.Url::get('account_id').'\'');
                        if(empty($check_exist))
                        {
                            //echo $parent_category_id.'<br>';
                            $privilege = array('account_id'=>Url::get('account_id'),'status'=>1,'category_id'=>$parent_category_id,'portal_id'=>$portal_id);
                            $privilege_module = array(
        					'module_id'=>Url::get('module_'.$parent_category_id.'_'),
        					'can_view'=>1,
        					'portal_id'=>$portal_id
        				    );
                            $parent_privilege_id = DB::insert('privilege',$privilege);
                            $privilege_module['privilege_id'] = $parent_privilege_id;
                            DB::insert('privilege_module',$privilege_module);  
                            $ids .= ','.$parent_privilege_id;    
                        }
                        else 
                        {
                            $privilege = array('account_id'=>Url::get('account_id'),'status'=>1,'category_id'=>$parent_category_id,'portal_id'=>$portal_id);
            				$privilege_module = array(
            					'module_id'=>Url::get('module_'.$parent_category_id.'_'),
            					'can_view'=>1,
            					'can_view_detail'=>isset($_REQUEST['view_'.$parent_category_id.'_'])?1:0,
            					'can_add'=>isset($_REQUEST['add_'.$parent_category_id.'_'])?1:0,
            					'can_edit'=>isset($_REQUEST['edit_'.$parent_category_id.'_'])?1:0,
            					'can_delete'=>isset($_REQUEST['delete_'.$parent_category_id.'_'])?1:0,
            					'can_reserve'=>isset($_REQUEST['reserve_'.$parent_category_id.'_'])?1:0,
            					'can_special'=>isset($_REQUEST['special_'.$parent_category_id.'_'])?1:0,
            					'can_admin'=>isset($_REQUEST['admin_'.$parent_category_id.'_'])?1:0,
            					'portal_id'=>$portal_id
            				); 
                            DB::update('privilege',$privilege,'portal_id = \''.$portal_id.'\' and id = '.$check_exist['id'].'');
        					DB::update('privilege_module',$privilege_module,'portal_id = \''.$portal_id.'\' and privilege_id='.$check_exist['id']);
                            $ids .= ','.$check_exist['id']; 
                        }
                        $parent_structure_id = IDStructure::parent($parent_structure_id);
                    }
                    //exit();
                }
				$privilege = array('account_id'=>Url::get('account_id'),'status'=>1,'category_id'=>$category_id,'portal_id'=>$portal_id);
				$privilege_module = array(
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
				if((Url::get('cmd')=='edit') and  Url::get('account_id') and Url::get('privilege_id_'.$category_id.'_'))
				{
                    $privilege_id = Url::iget('privilege_id_'.$category_id.'_');
                    DB::update('privilege',$privilege,'portal_id = \''.$portal_id.'\' and id = '.$privilege_id.'');
					DB::update('privilege_module',$privilege_module,'portal_id = \''.$portal_id.'\' and privilege_id='.$privilege_id);
				}
				else
				{
					if($privilege_id = DB::insert('privilege',$privilege))
					{
					    //echo $category_id.'at<br>';
						$privilege_module['privilege_id'] = $privilege_id;
						DB::insert('privilege_module',$privilege_module);
					}	
				}	
				ModeratorDB::update_moderator(URL::get('account_id'),$privilege_id,$category_id,$portal_id);
				if($structure_id = DB::fetch('select structure_id as id from category where id='.$category_id,'id'))
				{
					$privileges[$structure_id] =  $structure_id;
				}	
				$ids .= ','.$privilege_id;
			}
		}
        //exit();
		if($delete_ids = DB::fetch_all('select * from privilege where id not in ('.$ids.') and account_id=\''.Url::sget('account_id').'\' and category_id!=0'))
		{
		    //echo $ids;
		    //System::debug($delete_ids);
            //exit();
			foreach($delete_ids as $id)
			{
				DB::delete('privilege','portal_id = \''.$portal_id.'\' and id = '.$id['id']);
				DB::delete('account_privilege','portal_id = \''.$portal_id.'\' and privilege_id ='.$id['id']);
				DB::delete('privilege_module','portal_id = \''.$portal_id.'\' and privilege_id ='.$id['id']);
			}	
		}	
		DB::update_id('account',array('cache_setting'=>''),Url::sget('account_id'));
		Portal::set_setting('privilege',var_export($privileges,true),Url::sget('account_id'));
		$this->export($ids,$portal_id);//exit();
	}
	function export($privilege_ids,$portal_id)
	{
		$cond = 'category.status<>\'HIDE\' and privilege.id in ('.$privilege_ids.')';
		$categogies = $this->get_categories($cond);
        if(User::is_admin())
        {
            //System::debug($categogies);
            //exit();
        }
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