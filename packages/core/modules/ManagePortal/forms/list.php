<?php
class ListManagePortalForm extends Form
{
	function ListManagePortalForm()
	{
		Form::Form('ListManagePortalForm');
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
				ManagePortalForm::delete($this,$id);
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
		$selected_ids="";
		if(URL::get('selected_ids'))
		{
			$selected_ids=URL::get('selected_ids');
			foreach($selected_ids as $key=>$selected_id)
			{
				$selected_ids[$key]='"'.$selected_id.'"';
			}
		}
		$cond = 'AND account.id<>\'admin\''
				.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' AND ACCOUNT.ID IN (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
				.(Url::get('user_id')?' AND ACCOUNT.ID LIKE \'%'.addslashes(Url::get('user_id')).'%\'':'')
		;
		$item_per_page = 100;
		$count = DB::fetch('
			SELECT
				count(*) as acount
			FROM 
				ACCOUNT,PARTY,ZONE
			WHERE 
					ACCOUNT.TYPE=\'PORTAL\' AND PARTY.TYPE=\'PORTAL\' AND 
					ACCOUNT.ID = PARTY.USER_ID AND ZONE.ID = PARTY.ZONE_ID '.$cond.'
		');
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$items = DB::fetch_all('
			SELECT * FROM
			(
				SELECT 
					ACCOUNT.ID,party.name_1,
					ZONE.NAME_'.Portal::language().' AS ZONE_ID,
					ROWNUM AS rownumber
				FROM
					ACCOUNT
					LEFT OUTER JOIN PARTY ON PARTY.user_id = account.id
					LEFT OUTER JOIN ZONE ON ZONE.id = party.zone_id
				WHERE 
					ACCOUNT.TYPE=\'PORTAL\'
				ORDER BY 
					ACCOUNT.ID
			)
			WHERE
				 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		',false);
		$i=1;
        foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		//print_r($items);
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
			)
		);
	}
}
?>
