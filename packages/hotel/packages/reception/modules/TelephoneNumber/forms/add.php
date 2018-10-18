<?php
class AddTelephoneNumberForm extends Form
{
	function AddTelephoneNumberForm()
	{
		Form::Form('AddTelephoneNumberForm');
		$this->add('telephone_number.phone_number',new TextType(true,'invalid_number',0,255)); 
		$this->add('telephone_number.room_id',new IDType(true,'invalid_room_id','room'));
	}
	function on_submit()
	{	
		if($this->check())
		{	
			if(isset($_REQUEST['mi_telephone_number']))
			{
				foreach($_REQUEST['mi_telephone_number'] as $key=>$record)
				{
					unset($record['id']);
					$ids[] = DB::insert('telephone_number',$record); // Huan sua? them $ids 
				}
				$_REQUEST['selected_ids']=join(',',$ids); //huan them
			}
			Url::redirect_current(array('selected_ids')+array());
		}	
	}
	function draw()
	{
		$db_items = DB::select_all('room',false,'id');
		$room_id_options = '';
		foreach($db_items as $item)
		{
			$room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		} 
		$this->parse_layout('add',
			array(
			'room_id_options' => $room_id_options, 
			)
		);
	}
}
?>