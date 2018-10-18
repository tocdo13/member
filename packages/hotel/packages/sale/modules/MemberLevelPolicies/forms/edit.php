<?php
class MemberLevelPoliciesForm extends Form
{
	function MemberLevelPoliciesForm()
	{
		Form::Form('MemberLevelPoliciesForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
		if(URL::get('deleted_ids'))
		{
			$ids = explode(',',URL::get('deleted_ids'));
			require_once 'packages/hotel/includes/php/hotel.php';
			foreach($ids as $id)
			{
				$this->delete_member_level_policies($id);
			}
		}
        if(isset($_REQUEST['member_level_policies']))
		{
			$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			foreach($_REQUEST['member_level_policies'] as $key=>$record)
			{
			    if($record['start_date']!='')
                    $record['start_date'] = Date_Time::to_orc_date($record['start_date']);
                if($record['end_date']!='')
                    $record['end_date'] = Date_Time::to_orc_date($record['end_date']); 
                $record['member_level_id'] = Url::get('level_id');
				if($record['id'] and DB::exists_id('member_level_policies',$record['id']))
				{
					$member_level_policies_id  = $record['id'];
					unset($record['id']);
                    $record['last_editer'] = User::id();
                    $record['last_edit_time'] = time();
					DB::update('member_level_policies',$record,'id=\''.$member_level_policies_id.'\'');
				}
				else
				{
					unset($record['id']);
                    $record['creater'] = User::id();
                    $record['create_time'] = time();
					$id = DB::insert('member_level_policies',$record);
				}
			}
		}
        if (isset($ids) and sizeof($ids))
		{
			$_REQUEST['selected_ids'].=','.join(',',$ids);
		}
        Url::redirect('member_level_policies',array('level_id'));
		
	}	
	function draw()
	{
		if(!isset($_REQUEST['portal_id'])){
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['member_level_policies']))
		{
			$sql = 'select member_level_policies.*,to_char(member_level_policies.start_date,\'DD/MM/YYYY\') as start_date,to_char(member_level_policies.end_date,\'DD/MM/YYYY\') as end_date from member_level_policies WHERE member_level_policies.member_level_id='.Url::get('level_id').' order by id';
			$member_level_policies = DB::fetch_all($sql);
            
			$_REQUEST['member_level_policies'] = $member_level_policies;
		}
        $this->map['location_code_options'] = '';
        $pin_service_list = DB::fetch_all("
                                            SELECT 
                                                department.code as id,
                                                department.name_".Portal::language()." as name
                                            FROM 
                                                department
                                            WHERE
                                                department.check_access_control = 1
                                        ");
        foreach($pin_service_list as $k=>$v)
        {
                $this->map['location_code_options'] .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
        }
        $this->map['is_parent_options'] = '<option value="PARENT">'.Portal::language('parent_card').'</option><option value="SON">'.Portal::language('son_card').'</option>';
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_member_level_policies($member_level_policies_id){
		if($member_level_policies_id and DB::exists('select id from member_level_policies where id = '.$member_level_policies_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('member_level_policies','id=\''.$member_level_policies_id.'\'');	
		}
	}
}
?>