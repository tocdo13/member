<?php
class BankForm extends Form
{
	function BankForm()
	{
		Form::Form('BankForm');
		//$this->add('room.name',new TextType(true,'invalid_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
	   //System::debug($_REQUEST['bank']);
       //exit();
		if(URL::get('deleted_ids'))
		{
			$ids = explode(',',URL::get('deleted_ids'));
			require_once 'packages/hotel/includes/php/hotel.php';
           // System::debug($ids);
            //exit();
            
			foreach($ids as $id)
			{
				$this->delete_bank($id);
			}
		}
        if(isset($_REQUEST['bank']))
		{
			$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			foreach($_REQUEST['bank'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('bank',$record['id']))
				{
					$bank_id  = $record['id'];
					unset($record['id']);
					DB::update('bank',$record,'id=\''.$bank_id.'\'');
				}
				else
				{
//System::debug($record); //exit();
					unset($record['id']);
					$id = DB::insert('bank',$record);
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
		if(!isset($_REQUEST['bank']))
		{
			$sql = 'select * from bank order by id';
			$bank = DB::fetch_all($sql);
			//System::debug($room);
			$_REQUEST['bank'] = $bank;
		}
        
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_bank($bank_id){
		if($bank_id and DB::exists('select id from bank where id = '.$bank_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('bank','id=\''.$bank_id.'\'');	
		}
	}
}
?>