<?php
class DeleteSelectedTelephoneFeeForm extends Form
{
	function DeleteSelectedTelephoneFeeForm()
	{
		Form::Form("DeleteSelectedTelephoneFeeForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteTelephoneFeeForm::delete($id);
			}
			Url::redirect_current(array(
	));
		}
	}
	function draw()
	{
		DB::query('
			select 
				telephone_fee.id
				,telephone_fee.name ,telephone_fee.prefix ,telephone_fee.fee 
			from 
			 	telephone_fee
			where telephone_fee.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		foreach($items as $key=>$item)
		{
			  $items[$key]['fee']=System::display_number($item['fee']); 
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>