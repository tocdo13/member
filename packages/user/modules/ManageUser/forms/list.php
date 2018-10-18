<?php
class ListManageUserForm extends Form
{
	static $portal_id = PORTAL_ID;
	function ListManageUserForm()
	{
		Form::Form('ListManageUserForm');
		$this->link_css(Portal::template('core').'/css/admin.css');
		if(Url::get('logoff_user_id')){
			require 'packages/hotel/includes/php/hotel.php';
			Hotel::log_off_user(Url::sget('logoff_user_id'));
			Url::redirect_current(array('cmd'));
		}
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			foreach(URL::get('selected_ids') as $id)
			{
			}
			require_once 'detail.php';
			foreach(URL::get('selected_ids') as $id)
			{
				ManageUserForm::delete($this,$id);
				if($this->is_error())
				{
					return;
				}
			}
			require_once 'packages/core/includes/system/update_privilege.php';
			make_privilege_cache();
			Url::redirect_current(array(  
	     'join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:'', 
	));
		}
	}
	function draw()
	{
        //giap.ln cap nhat nhung tai khoan hien tai chua chon department
        $sql="SELECT account.id FROM account WHERE account.portal_department_id is not null AND account.portal_department_id!=1001 AND account.portal_department_id!=1002 AND  not exists(SELECT * FROM portal_department WHERE id=account.portal_department_id) "; 
        $items_lost_department = DB::fetch_all($sql);
        foreach($items_lost_department as $row)
        {
            DB::query("UPDATE account set portal_department_id=null WHERE id='".$row['id']."'");    
        }
        //end giap.ln
		$selected_ids="";
		if(URL::get('selected_ids'))
		{
			$selected_ids=URL::get('selected_ids');
			foreach($selected_ids as $key=>$selected_id)
			{
				$selected_ids[$key]='"'.$selected_id.'"';
			}
		}
		if(Url::get('portal_id')){
			ListManageUserForm::$portal_id = Url::get('portal_id');
		}else{
			ListManageUserForm::$portal_id = 'ALL';
		}
        $this->map = array();
        $this->map['account_status_list']=array(
                                                ''=>Portal::language('all'),
                                                '1'=>Portal::language('block'),
                                                '2'=>Portal::language('active')
                                              			
                                        		);
                                                
        //system::debug($_REQUEST);                                                
		$cond = ' 	'.(!User::is_admin()?'(account.id<>\'admin\' and account.id<>\'khoand\' and account.id<>\'tester\' and account.id<>\'developer\'  and account.id<>\'trienkhai\')':'1=1').'
					AND account.id <> \''.Session::get('user_id').'\''
					.(User::can_admin(false, ANY_CATEGORY)?((ListManageUserForm::$portal_id!='ALL')?'AND account_related.parent_id = \''.ListManageUserForm::$portal_id.'\'':''):' AND account_related.parent_id = \''.PORTAL_ID.'\'')
					.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' AND ACCOUNT.ID IN (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
					.(Url::get('user_name')?' AND ACCOUNT.ID LIKE \'%'.addslashes(Url::get('user_name')).'%\'':'')
		            //.(!User::is_admin()?' AND nvl(account.is_block,0) != 1 ':'')
        ;
        if(!isset($_REQUEST['account_status']))
        {
            $cond .=' AND nvl(account.is_active,0) = 1 ';
           $_REQUEST['account_status']=2;
        }
        else
        {
            if(Url::get('account_status')==1)
            {
                $cond .=' AND nvl(account.is_block,0) = 1 ';
            }
            if(Url::get('account_status')==2)
            {
                $cond .=' AND nvl(account.is_active,0) = 1 ';
            }
        }
        
		$item_per_page = 100;
		$count = DB::fetch('
			SELECT
				count(*) as acount
			FROM  
				ACCOUNT
				INNER JOIN party ON party.user_id = account.id
				LEFT OUTER JOIN zone ON zone.id = party.zone_id
				LEFT OUTER JOIN account_related ON account_related.child_id = account.id
				LEFT OUTER JOIN session_user ON session_user.user_id = account.id
			WHERE 
				'.$cond.'
				AND ACCOUNT.TYPE=\'USER\' AND PARTY.TYPE=\'USER\'
		');
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$items = DB::fetch_all('
			SELECT * FROM
			(
				SELECT 
					ACCOUNT.ID,ACCOUNT.PASSWORD,ACCOUNT.IS_ACTIVE AS ACTIVE
					,ACCOUNT.IS_BLOCK AS BLOCK,to_char(ACCOUNT.CREATE_DATE,\'dd/mm/YYYY\') as create_date,
					PARTY.TYPE,
					PARTY.EMAIL,PARTY.ADDRESS,PARTY.FULL_NAME,
					PARTY.PHONE AS phone_number,
					ZONE.NAME_'.Portal::language().' AS ZONE_ID,
					PARTY.description_1,
                    ACCOUNT.portal_department_id,
					DECODE(session_user.id,null,\'OFF\',\'<strong>ON</strong>\') as status,
					session_user.ip,
					row_number() OVER (ORDER BY PARTY.description_1,ACCOUNT.ID) AS rownumber
				FROM
					ACCOUNT
					INNER JOIN party ON party.user_id = account.id
					LEFT OUTER JOIN zone ON zone.id = party.zone_id
					LEFT OUTER JOIN account_related ON account_related.child_id = account.id
					LEFT OUTER JOIN session_user ON session_user.user_id = account.id
				WHERE 
					'.$cond.'
					AND ACCOUNT.TYPE=\'USER\' AND PARTY.TYPE=\'USER\'
				ORDER BY 
					ACCOUNT.portal_department_id,PARTY.description_1,ACCOUNT.ID
			)
			WHERE
				 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		',false);
		$i=1;
        $dep_user = DB::fetch("select description_1 from party where user_id = '".User::id()."'",'description_1');
        $is_develop = ((strtoupper($dep_user) == "DEVELOPMENT") and User::is_admin())?true:false;
        $is_deploy = (strtoupper($dep_user) == "DEPLOYMENT" and User::is_deploy())?true:false;
        
		foreach ($items as $key=>$value)
		{
            //giap.ln hien thi nhung tai khoan thuoc bo phan nao do
            if($value['portal_department_id']!='')
            {
                if($value['portal_department_id']!=1001 && $value['portal_department_id']!=1002)
                {
                    $sql ="SELECT department.id,
                                department.name_1 as name
                        FROM portal_department
                        INNER JOIN department ON department.code=portal_department.department_code
                        WHERE portal_department.id=".$value['portal_department_id'];
                    $department = DB::fetch($sql);
                    if(empty($department)==false)
                    {
                        $items[$key]['description_1'] = $department['name'];
                    }
                    else
                    {
                        $items[$key]['description_1'] ='Not Assign department';
                    }
                }
                else if($value['description_1']==1001)
                {
                    $items[$key]['description_1'] = 'Development';
                }
                else
                {
                    $items[$key]['description_1'] = 'Deployment';
                }
            }
            else
            {
                $items[$key]['description_1'] = 'Not Assign department';
            }
            //end giap.ln
            /** START checktk **/
            if(!$is_develop)
            {
                if(strtoupper($items[$key]['description_1']) == "DEVELOPMENT")
                {
                    unset($items[$key]); continue;
                }
                if(!$is_deploy)
                {
                    if(strtoupper($items[$key]['description_1']) == "DEPLOYMENT")
                    {
                        unset($items[$key]); continue;
                    }
                }
            }
            /** END checktk **/
			$items[$key]['i'] = $i++;
			//$sql = 'SELECT
//					ACCOUNT_RELATED.ID,
//					ACCOUNT_RELATED.IS_ACTIVE,
//					ACCOUNT_RELATED.PARENT_ID,
//					party.name_1 as name
//				FROM
//					ACCOUNT_RELATED
//					INNER JOIN party ON party.user_id = ACCOUNT_RELATED.PARENT_ID
//				WHERE
//					ACCOUNT_RELATED.CHILD_ID=\''.$value['id'].'\'';	
            $sql = 'SELECT
					ACCOUNT_PRIVILEGE_GROUP.ID,
					ACCOUNT_PRIVILEGE_GROUP.PORTAL_ID AS PARENT_ID,
					party.name_1 as name
				FROM
					ACCOUNT_PRIVILEGE_GROUP
					INNER JOIN party ON party.user_id = ACCOUNT_PRIVILEGE_GROUP.PORTAL_ID
				WHERE
					ACCOUNT_PRIVILEGE_GROUP.ACCOUNT_ID=\''.$value['id'].'\'';
			$items[$key]['portals'] = DB::fetch_all($sql);
			$sql_group_privilege = '
				SELECT
					ACCOUNT_PRIVILEGE_GROUP.ID,
					ACCOUNT_PRIVILEGE_GROUP.group_privilege_id,
					ACCOUNT_RELATED.IS_ACTIVE,
					ACCOUNT_RELATED.PARENT_ID,
					privilege_group.name_'.Portal::language().' as name
				FROM
					ACCOUNT_PRIVILEGE_GROUP
					INNER JOIN account_related on account_related.child_id = ACCOUNT_PRIVILEGE_GROUP.account_id
					INNER JOIN privilege_group ON privilege_group.id = ACCOUNT_PRIVILEGE_GROUP.group_privilege_id
				WHERE
					ACCOUNT_RELATED.CHILD_ID=\''.$value['id'].'\'
			';
			$items[$key]['group_privilege'] = DB::fetch_all($sql_group_privilege);			
		}
        
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']= explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		
		$this->parse_layout('list',$just_edited_id+$this->map+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list())
			)
		);
	}
}
?>