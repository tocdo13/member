<?php
class EditManageUserForm extends Form
{
	function EditManageUserForm()
	{
		Form::Form('EditManageUserForm');
		$this->add('id',new NameType(true,'invalid_user_id',0,255));
		if(URL::get('cmd')=='edit')
		{
			$this->add('id',new IDType(true,'object_not_exists','account'));
		}
		else
		{
			$this->add('id',new UniqueType(true,'duplicate_user_id','account','id'));
		}
		if(Url::get('cmd')=='add')
		{
			$this->add('password',new TextType(true,'password_is_required',0,255)); 
		}
		$this->add('email',new EmailType(false,'invalid_email')); 
		$this->add('full_name',new TextType(true,'full_name_is_required',0,255)); 
		$this->add('birth_day',new DateType(false,'invalid_birth_date')); 
		$this->add('address',new TextType(false,'invalid_address',0,255)); 
		$this->add('home_page',new TextType(true,'miss_home_page',0,255)); 
		$this->add('description_1',new TextType(true,'miss_department',0,255)); 
		$this->add('join_date',new DateType(false,'invalid_join_date')); 
		$this->add('phone_number',new TextType(false,'invalid_phone_number',0,255)); 
		$this->add('zone_id',new IDType(true,'invalid_zone_id','zone')); 
		$this->add('account_related.join_date',new DateType(false,'invalid_join_date')); 
 		$this->add('account_related.group_id',new IDType(false,'invalid_group_id','group')); 
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/core/skins/default/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function on_submit()
	{
		if(URL::get('cmd')=='edit' and Url::get('id'))
		{
			$row = DB::select('account','id=\''.Url::sget('id').'\'');
		}
		if($this->check() and URL::get('confirm_edit'))
		{
			require_once 'packages/core/includes/system/make_user_privilege_cache.php';
			$id = str_replace(array('.',',','\\','#'),'',strtolower(Url::sget('id')));
			$account_new_row = array(
				'id'=>$id,
				'create_date'=>Date_Time::to_orc_date((URL::get('join_date'))),
				'is_active'=>URL::get('active'), 
				'is_block'=>URL::get('block'),
				'home_page'=>URL::get('home_page'),
				'type'=>'USER',
                'change_room_status'=>(URL::get('change_room_status')?URL::get('change_room_status'):0),
                'change_checkin_book'=>(URL::get('change_checkin_book')?URL::get('change_checkin_book'):0),
				'cache_privilege'=>'',
				'language_id'=>Url::get('language_id'),
				'restricted_access_ip'=>trim(Url::sget('restricted_access_ip')),
                'portal_department_id'=>Url::get('description_1')
			)+(URL::get('password')?array('password'=>User::encode_password($_REQUEST['password'])):array());
			$party_new_row = 
				array(
					'zone_id', 
					'email',
					'birth_day'=>Date_Time::to_orc_date((URL::get('birth_day'))), 
					'address', 
					'gender', 
					'phone'=>URL::get('phone_number'),
					'type'=>'USER',
					'status'=>'SHOW',
					'description_1'=>(Url::get('description_1')==1001)?'DEVELOPMENT':((Url::get('description_1')==1002)?'DEPLOYMENT':Url::get('description_1')),
					'full_name'=>Url::get('full_name')
				);
			$languages = DB::select_all('language');
			foreach($languages as $language)
			{
				$party_new_row['name_'.$language['id']] = URL::get('full_name');
			}
			if(URL::get('cmd')=='edit')
			{
				DB::update('party', $party_new_row,'user_id=\''.$id.'\' and type=\'USER\'');
				DB::update('account', $account_new_row,'id=\''.$id.'\'');
			}
			else
			{
				require_once 'packages/core/includes/system/si_database.php';
				DB::insert('account', $account_new_row);
				DB::insert('party', $party_new_row+array('user_id'=>$id));
			}
			$user_id = $id;
			if(User::can_edit(false,ANY_CATEGORY)){
				if(URl::get('group_deleted_ids'))
				{
					$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
					foreach($group_deleted_ids as $delete_id)
					{
						DB::delete_id('account_privilege_group',$delete_id);
					}
				}				
				if(isset($_REQUEST['mi_portal_group']))
				{
                   
					foreach($_REQUEST['mi_portal_group'] as $key=>$record)
					{
						$record['join_date'] = Date_Time::to_orc_date($record['join_date']);  
						$empty = true;
						foreach($record as $record_value)
						{
							if($record_value)
							{
								$empty = false;
							}
						}
						if(!$empty)
						{
							$group_privilege = array(
								'account_id'=>$user_id,
								'portal_id'=>$record['parent_id'],
								'group_privilege_id'=>$record['group_privilege_id']
							);
							$is_active = $record['is_active'];
							unset($record['is_active']);
							$portal_id = $record['parent_id'];
							unset($record['parent_id']);
							if($record['id'] and $row = DB::select('account_privilege_group','id = '.$record['id']))
							{
								DB::update('account_privilege_group',$group_privilege,'id='.$row['id']);
							}
							else
							{
								unset($record['id']);
								DB::insert('account_privilege_group',$group_privilege);
							}
							$account_replateds = DB::fetch_all('select id, account_id,portal_id from account_privilege_group where account_id=\''.$user_id.'\'');
						//	if(User::is_admin())
//                            {
//                                System::debug($account_replateds);
//                                exit();
//                            }
                            foreach($account_replateds as $key=>$value)
							{
								make_user_privilege_cache($value['account_id'],$value['portal_id']);
								$account_related_data = array(
									'child_id'=>$value['account_id'],
									'parent_id'=>$value['portal_id'],
									'join_date'=>$record['join_date'],
									'is_active'=>$is_active
								);
								if(!DB::select('account_related','parent_id=\''.$value['portal_id'].'\' and child_id=\''.$value['account_id'].'\''))
								{
									DB::insert('account_related',$account_related_data);	
								}
							}
/*							System::debug($account_replated);
							exit();
							$record['child_id'] = $user_id;
							if($record['id'] and !DB::select('account_related','portal_id=\''.$record['parent_id'].'\''))
							{
								DB::update('account_related',$record,'id=\''.$record['id'].'\'');
							}
							elseif(DB::select('account_related','portal_id=\''.$record['parent_id'].'\''))
							{
								unset($record['id']);
								DB::update('account_related',$record,'id=\''.$record['id'].'\'');
							}
							else
							{
								unset($record['id']);
								DB::insert('account_related',$record);
							}
*/						}
					}
				}
                else
                {
					$this->error('portal_id','miss_portal');
					return;
				}
			}
			Url::redirect_current(array('just_edited_id'=>$id));
		}
	}	
	function draw()
	{
		$languages = DB::select_all('language');
        //luu nguyen giap add column chang_room_status,change_checkin_book for account
		$row = @DB::fetch('
			select
				party.id,party.gender,
				party.email,party.full_name,
				party.address,to_char(party.birth_day,\'dd/mm/YYYY\') as birth_day,
				party.phone as phone_number,party.zone_id,
				account.home_page,
				party.description_1,
                account.change_room_status,
                account.change_checkin_book,
                account.restricted_access_ip as restricted_access_ip,
                account.portal_department_id
			from
				party
				inner join account on account.id = party.user_id
			where
				party.user_id=\''.URL::sget('id').'\' and account.type=\'USER\'
		');
        
        
		if(URL::get('cmd')=='edit' and $row and $account = DB::fetch('select account.id,to_char(account.create_date,\'dd/mm/YYYY\') as create_date,account.is_active,account.is_block,account.language_id,account.restricted_access_ip from account where id =\''.URL::sget('id').'\''))
		{
            $department_1 = $row['portal_department_id'];
            
			$row['id'] = $account['id'];
			$row['full_name'] = $row['full_name'];
			$row['join_date'] = $account['create_date'];
			$row['active'] = $account['is_active'];
			$row['block'] = $account['is_block'];
            $row['language_id'] = $account['language_id'];
            $row['restricted_access_ip'] = $account['restricted_access_ip'];
			unset($row['password']);
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

		if(!isset($_REQUEST['mi_portal_group']) and $edit_mode)
		{
			$mi_portal_group = DB::fetch_all('
				SELECT
					ACCOUNT_PRIVILEGE_GROUP.ID,
					ACCOUNT_PRIVILEGE_GROUP.group_privilege_id,
					ACCOUNT_RELATED.IS_ACTIVE,
					ACCOUNT_PRIVILEGE_GROUP.portal_id as parent_id
				FROM
					ACCOUNT_PRIVILEGE_GROUP
					INNER JOIN account_related on account_related.child_id = ACCOUNT_PRIVILEGE_GROUP.account_id
				WHERE
					ACCOUNT_RELATED.CHILD_ID=\''.URL::sget('id').'\'
			');
			$_REQUEST['mi_portal_group'] = $mi_portal_group;
            
		}
		$zone_id_list = String::get_list(DB::fetch_all('
			SELECT
				ID,
				ZONE.NAME_'.Portal::language().' AS name
			FROM
				ZONE
			WHERE
				'.IDStructure::direct_child_cond('1010000000000000000').' ORDER BY STRUCTURE_ID'
		));
		$db_items = Portal::get_portal_list();
		$group_options = '<option value="">'.Portal::language('select').'</option>';
		foreach($db_items as $item)
		{
			$group_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		$db_group_items = DB::fetch_all('select id,name_'.Portal::language().' as name from privilege_group');
		$group_privilege_options = '<option value="">'.Portal::language('select').'</option>';
		foreach($db_group_items as $item)
		{
			$group_privilege_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}		
		$home_page_list = array(
			'home'=>Portal::language('homepage'),
			'room_map'=>Portal::language('room_map'),
			'table_map'=>Portal::language('table_map'),
            'reservation&cmd=check_availability'=>strtolower(Portal::language('check_availability')),
            'monthly_room_report'=>Portal::language('monthly_room_report'),
            'massage_daily_summary'=>Portal::language('massage_daily_summary')
		);
		//----------------------------------------------------------------------------------------------------
		/* giap.ln comment thay doi: lay ra tat ca nhung bo phan trong department 
        $description_1_list = array(
			Portal::language('k_reception')=>Portal::language('reception'),
			Portal::language('k_sales')=>Portal::language('sales'),
			Portal::language('k_housekeeping')=>Portal::language('housekeeping'),
			Portal::language('k_restaurant')=>Portal::language('restaurant'),
			Portal::language('k_warehouse')=>Portal::language('warehouse'),
			//Portal::language('accounting_report')=>Portal::language('accounting_report'),
			Portal::language('k_director')=>Portal::language('director'),
            Portal::language('k_spa')=>Portal::language('spa'),
            Portal::language('k_shop')=>Portal::language('shop'),
            Portal::language('k_cashier')=>Portal::language('cashier'),
            Portal::language('k_accounting')=>Portal::language('accounting'),
            Portal::language('k_event')=>Portal::language('event')
            
		);
		if(HAVE_MASSAGE){
			$description_1_list += array(Portal::language('massage')=>Portal::language('massage'));
		}
		if(HAVE_TENNIS){
			$description_1_list += array(Portal::language('tennis')=>Portal::language('tennis'));
		}
		if(HAVE_SWIMMING){
			$description_1_list += array(Portal::language('swimming_pool')=>Portal::language('swimming_pool'));
		}
		if(HAVE_KARAOKE){
			$description_1_list += array(Portal::language('karaoke')=>Portal::language('karaoke'));
		}*/
        //giap.ln lay ra danh sach cac bo phan trong department
        $sql="Select 
                portal_department.department_code as id, 
                portal_department.id as portal_department_id,
                portal_department.department_code as code, 
                '--' || department.name_1 as name,
                department.parent_id,
                department.id as department_id
                
            from 
                portal_department
                inner join department on  department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
            where 
                department.parent_id=0
            order by 
                portal_department.id ";
        
        $departments = DB::fetch_all($sql);
        
        //System::debug($departments);
        $default = array('id'=>1000,'code'=>'CHOOSE_DEPARTMENT','name'=>Portal::language('select_department'));
        $result = array();
        
        array_push($result,$default);
        $parent_id = false;
        foreach($departments as $key=>$row)
        {
             array_push($result,array('id'=>$row['portal_department_id'],'code'=>$row['code'],'name'=>$row['name']));
             //2. tim nhung dong child $row['department_id']
             $sql = "select portal_department.department_code as id,
                        portal_department.id as portal_department_id,
                        portal_department.department_code as code,
                        '----' || department.name_1 as name,
                        department.parent_id,
                        department.id as department_id
                        from portal_department
                        inner join department on department.code  = portal_department.department_code
                    where department.parent_id=".$row['department_id'];
             $items = DB::fetch_all($sql);
             foreach($items as $k=>$v)
             {
                array_push($result,array('id'=>$v['portal_department_id'],'code'=>$v['code'],'name'=>$v['name']));
             }
        }
        
        $dep_user = DB::fetch("select description_1 from party where user_id = '".User::id()."'",'description_1');
        $is_develop = ((strtoupper($dep_user) == "DEVELOPMENT") and User::is_admin())?true:false;
        $is_deploy = (strtoupper($dep_user) == "DEPLOYMENT" and User::is_deploy())?true:false;
        
		if($is_develop){
            array_push($result,array('id'=>1001,'code'=>'DEVELOPMENT','name'=>'--'.Portal::language('development')));
            array_push($result,array('id'=>1002,'code'=>'DEPLOYMENT','name'=>'--'.Portal::language('deployment')));
		}
        if($is_deploy){
			array_push($result,array('id'=>1002,'code'=>'DEPLOYMENT','name'=>'--'.Portal::language('deployment')));
		}
        $department_list = '';
        
		foreach($result as $id => $value)
        {
			$department_list .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		//----------------------------------------------------------------------------------------------------
		$this->parse_layout('edit',
			($edit_mode?$row:array())+
			array(
				'description_1'=>isset($department_1)?$department_1:'',
				'home_page_list'=>$home_page_list,
				'users'=>ManageUserDB::get_users(),
				'portal_id_list'=>array(''=>Portal::language('select'))+String::get_list(Portal::get_portal_list()),
				'zone_id_list'=>$zone_id_list, 
				'group_options'=>$group_options,
				'group_privilege_options'=>$group_privilege_options,
                'language_id_list'=>String::get_list($languages),
                'department_list'=>$department_list
			)
		);
	}
}
?>
