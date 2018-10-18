<?php
class MiceDepartmentSetupForm extends Form
{
	function MiceDepartmentSetupForm()
	{
		Form::Form('MiceDepartmentSetupForm');
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
				$this->delete_mice_department_setup($id);
			}
		}
        if(isset($_REQUEST['mice_department_setup']))
		{
			$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			foreach($_REQUEST['mice_department_setup'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('mice_department_setup',$record['id']))
				{
					$mice_department_setup_id  = $record['id'];
					unset($record['id']);
					DB::update('mice_department_setup',$record,'id=\''.$mice_department_setup_id.'\'');
				}
				else
				{
					unset($record['id']);
					$id = DB::insert('mice_department_setup',$record);
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
		if(!isset($_REQUEST['portal_id']))
        {
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['mice_department_setup']))
		{
			$sql = 'select * from mice_department_setup order by id';
			$mice_department_setup = DB::fetch_all($sql);
			$_REQUEST['mice_department_setup'] = $mice_department_setup;
		}
        
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_mice_department_setup($mice_department_setup_id)
    {
		if($mice_department_setup_id and DB::exists('select id from mice_department_setup where id = '.$mice_department_setup_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('mice_department_setup','id=\''.$mice_department_setup_id.'\'');	
		}
	}
}
?>