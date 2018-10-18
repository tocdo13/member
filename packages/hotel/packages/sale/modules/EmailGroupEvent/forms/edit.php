<?php
class EditEmailGroupEvent extends Form
{
	function EditEmailGroupEvent()
	{
		Form::Form('EditEmailGroupEvent');
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
	    //system::debug($_REQUEST);die();
		if(URL::get('deleted_ids'))
		{
		  //die('aa');
			$ids = explode(',',URL::get('deleted_ids'));
			foreach($ids as $id)
			{
				$this->delete_email($id);
			}
		}
        if(isset($_REQUEST['email']))
		{	
			foreach($_REQUEST['email'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('email_group_event',$record['id']))
				{
					$event_id  = $record['id'];
					unset($record['id']);
					DB::update('email_group_event',$record,'id=\''.$event_id.'\'');
				}
				else
				{
					unset($record['id']);
					$id = DB::insert('email_group_event',$record);
				}
			}
		}
        if(isset($ids) and sizeof($ids))
			$_REQUEST['selected_ids'].=','.join(',',$ids);
		Url::redirect_current(array('portal_id'));
		
	}	
	function draw()
	{
		if(!isset($_REQUEST['email']))
		{
			$sql = 'select * from email_group_event order by id';
			$event_name = DB::fetch_all($sql);
			$_REQUEST['email'] = $event_name;
		}
		$this->parse_layout('edit',array());
	}
	function delete_email($event_id)
    {
		if($event_id and User::can_delete(false,ANY_CATEGORY))
        {
            $data = DB::fetch_all('SELECT email_group_event.id AS id,
                              email_list.email_send_id as email_list_id 
                       FROM email_group_event
                       LEFT JOIN email_send  ON email_group_event.id = email_send.email_group_event_id
                       LEFT JOIN email_list ON email_send.id = email_list.email_send_id
                       WHERE email_group_event.id = '.$event_id);      
            
            foreach($data as $k => $v)
            {
                if(isset($v['email_list_id']))
                {
                    DB::delete('email_list','email_send_id='.$v['email_list_id']);
                }    
            }
            DB::delete('email_group_event_customer','email_group_event_id=\''.$event_id.'\'');                
			DB::delete('email_group_event','id=\''.$event_id.'\'');
            DB::delete('email_send','email_group_event_id=\''.$event_id.'\'');       
        }   
	}
}
?>