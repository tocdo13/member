<?php
class StatusTravellerForm extends Form
{
	function StatusTravellerForm()
	{
		Form::Form('StatusTravellerForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
	    $check_event_delete = 0;
		if(URL::get('deleted_ids'))
		{
			$ids = explode(',',URL::get('deleted_ids'));
			require_once 'packages/hotel/includes/php/hotel.php';
			foreach($ids as $id)
			{
			    if(!DB::exists('select id from traveller where status_traveller_id='.$id))
                {
				    $this->delete_status_traveller($id);
                }
                else
                {
                    $check_event_delete = 1;
                }
			}
		}
        if(isset($_REQUEST['status_traveller']))
		{
			$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			foreach($_REQUEST['status_traveller'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('status_traveller',$record['id']))
				{
					$status_traveller_id  = $record['id'];
					unset($record['id']);
					DB::update('status_traveller',$record,'id=\''.$status_traveller_id.'\'');
				}
				else
				{
					unset($record['id']);
					$id = DB::insert('status_traveller',$record);
				}
			}
		}
        if (isset($ids) and sizeof($ids))
		{
			$_REQUEST['selected_ids'].=','.join(',',$ids);
		}
        echo $check_event_delete;
        if($check_event_delete==1)
            Url::redirect('status_traveller',array('event_delete'=>1));
        else
		  Url::redirect_current(array('portal_id'));
	}	
	function draw()
	{
		if(!isset($_REQUEST['portal_id'])){
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['status_traveller']))
		{
			$sql = 'select * from status_traveller order by id';
			$status_traveller = DB::fetch_all($sql);
			$_REQUEST['status_traveller'] = $status_traveller;
		}
        
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_status_traveller($status_traveller_id){
		if($status_traveller_id and DB::exists('select id from status_traveller where id = '.$status_traveller_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('status_traveller','id=\''.$status_traveller_id.'\'');	
		}
	}
}
?>