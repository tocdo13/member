<?php
class MiceLocationSetupForm extends Form
{
	function MiceLocationSetupForm()
	{
		Form::Form('MiceLocationSetupForm');
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
				$this->delete_mice_location_setup($id);
			}
		}
        if(isset($_REQUEST['mice_location_setup']))
		{
			$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			foreach($_REQUEST['mice_location_setup'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('mice_location_setup',$record['id']))
				{
					$mice_location_setup_id  = $record['id'];
					unset($record['id']);
					DB::update('mice_location_setup',$record,'id=\''.$mice_location_setup_id.'\'');
				}
				else
				{
					unset($record['id']);
					$id = DB::insert('mice_location_setup',$record);
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
		if(!isset($_REQUEST['mice_location_setup']))
		{
			$sql = 'select * from mice_location_setup order by id';
			$mice_location_setup = DB::fetch_all($sql);
			$_REQUEST['mice_location_setup'] = $mice_location_setup;
		}
        
        //System::debug($_REQUEST['mice_location_setup']);
        foreach($_REQUEST['mice_location_setup'] as $key => $value){
            $_REQUEST['mice_location_setup'][$key]['check_delete'] = 0;
            if(DB::exists("SELECT 
                                        mice_setup_beo.*
                                    FROM 
                                        mice_setup_beo 
                                        inner join mice_location_setup on mice_location_setup.id=mice_setup_beo.mice_location_id
                                    WHERE
                                        mice_setup_beo.mice_location_id=".$value['id']."
                                    ")){
                $_REQUEST['mice_location_setup'][$key]['check_delete'] = 1;
            }
        }
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_mice_location_setup($mice_location_setup_id)
    {
		if($mice_location_setup_id and DB::exists('select id from mice_location_setup where id = '.$mice_location_setup_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('mice_location_setup','id=\''.$mice_location_setup_id.'\'');	
		}
	}
}
?>
