<?php
class GroupTravellerForm extends Form
{
	function GroupTravellerForm()
	{
		Form::Form('GroupTravellerForm');
		//$this->add('room.name',new TextType(true,'invalid_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		if(URL::get('deleted_ids'))
		{
			$ids = explode(',',URL::get('deleted_ids'));
			require_once 'packages/hotel/includes/php/hotel.php';
			foreach($ids as $id)
			{
			     if(!DB::exists('select id from traveller where group_traveller_id='.$id))
                 {
                    $this->delete_group_traveller($id);
                 }
			}
		}
        if(isset($_REQUEST['group_traveller']))
		{
			$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			foreach($_REQUEST['group_traveller'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('group_traveller',$record['id']))
				{
					$group_traveller_id  = $record['id'];
					unset($record['id']);
					DB::update('group_traveller',$record,'id=\''.$group_traveller_id.'\'');
				}
				else
				{
					unset($record['id']);
					$id = DB::insert('group_traveller',$record);
				}
			}
		}
        if (isset($ids) and sizeof($ids))
		{
			$_REQUEST['selected_ids'].=','.join(',',$ids);
		}
		Url::redirect_current(array('portal_id'));
		
	}	
	function draw()
	{
		if(!isset($_REQUEST['portal_id'])){
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['group_traveller']))
		{
			$sql = 'select * from group_traveller order by id';
			$group_traveller = DB::fetch_all($sql);
			$_REQUEST['group_traveller'] = $group_traveller;
		}
        foreach($_REQUEST['group_traveller'] as $key=>$value)
        {
            if(DB::exists('select id from traveller where group_traveller_id='.$value['id']))
            {
                $_REQUEST['group_traveller'][$key]['is_delete'] = 0;
            }
            else
            {
                $_REQUEST['group_traveller'][$key]['is_delete'] = 1;
            }
        }
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_group_traveller($group_traveller_id)
    {
		if($group_traveller_id and DB::exists('select id from group_traveller where id = '.$group_traveller_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('group_traveller','id=\''.$group_traveller_id.'\'');	
		}
	}
}
?>