<?php
class SectorsForm extends Form
{
	function SectorsForm()
	{
		Form::Form('SectorsForm');
		//$this->add('room.name',new TextType(true,'invalid_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
	   //System::debug($_REQUEST['Sectors']);
       //exit();
		if(URL::get('deleted_ids'))
		{
			$ids = explode(',',URL::get('deleted_ids'));
			require_once 'packages/hotel/includes/php/hotel.php';
           // System::debug($ids);
            //exit();
            
			foreach($ids as $id)
			{
				$this->delete_sectors($id);
			}
		}
        if(isset($_REQUEST['sectors']))
		{
			$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
			foreach($_REQUEST['sectors'] as $key=>$record)
			{
				if($record['id'] and DB::exists_id('sectors',$record['id']))
				{
					$sectors_id  = $record['id'];
					unset($record['id']);
                    DB::update_id('sectors',$record,$sectors_id);
				}
				else
				{
//System::debug($record); //exit();
					unset($record['id']);
					$id = DB::insert('sectors',$record);
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
		if(!isset($_REQUEST['sectors']))
		{
			$sql1 = 'select id || name as id,name from sectors order by id';
			$sectors1 = DB::fetch_all($sql1);
            //System::debug($sectors1);
            $sql = 'select * from sectors order by id';
			$sectors = DB::fetch_all($sql);
			//System::debug($room);
			$_REQUEST['sectors'] = $sectors;
		}
        
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('edit',$this->map);
	}
	function delete_sectors($sectors_id){
		if($sectors_id and DB::exists('select id from sectors where id = '.$sectors_id.'') and User::can_delete(false,ANY_CATEGORY)){
			DB::delete('sectors','id=\''.$sectors_id.'\'');	
		}
	}
}
?>