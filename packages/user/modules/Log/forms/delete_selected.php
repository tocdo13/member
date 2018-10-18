<?php
class DeleteSelectedLogForm extends Form
{
	function DeleteSelectedLogForm()
	{
		Form::Form('DeleteSelectedLogForm');
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		require_once 'delete.php';
		foreach(URL::get('selected_ids') as $id)
		{
			DeleteLogForm::delete($id);
		}
		Url::redirect_current();
	}
	function draw()
	{
		DB::query('
			select 
				log.id
				,FROM_UNIXTIME(log.time) as time ,log.type ,log.description ,log.parameter ,log.note ,log.title 
				,account.id as user_id 
				,module.name as module_id 
			from 
			 	log
				left outer join account on account.id=log.user_id 
				left outer join module on module.id=log.module_id 
			where log.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>