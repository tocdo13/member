<?php
class DeleteSelectedForgotObjectForm extends Form
{
	function DeleteSelectedForgotObjectForm()
	{
		Form::Form("DeleteSelectedForgotObjectForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		require_once 'delete.php';
		foreach(URL::get('selected_ids') as $id)
		{
			DeleteForgotObjectForm::delete($id);
		}
		Url::redirect_current(array(   
	      'forgot_object_time_start','forgot_object_time_end',  
	));
	}
	function draw()
	{
		DB::query('
			select 
				forgot_object.id
				,forgot_object.name ,forgot_object.object_type,forgot_object.quantity,forgot_object.unit ,forgot_object.last_name ,forgot_object.first_name ,forgot_object.check_in_date ,forgot_object.check_out_date ,FROM_UNIXTIME(forgot_object.time) as time ,forgot_object.status 
			from 
			 	forgot_object
			where forgot_object.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		foreach($items as $key=>$item)
		{
			    $items[$key]['check_in_date']=System::display_number($item['check_in_date']); $items[$key]['check_out_date']=System::display_number($item['check_out_date']);   
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>