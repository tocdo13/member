<?php
class DeleteLogForm extends Form
{
	function DeleteLogForm()
	{
		Form::Form("DeleteLogForm");
		$this->add('id',new IDType(true,'object_not_exists','log'));
	}
	function on_submit()
	{
		if($this->check())
		{
			$this->delete($_REQUEST['id']);
			Url::redirect_current();
		}
	}
	function draw()
	{
		DB::query('
			select 
				log.id,FROM_UNIXTIME(log.time) as time ,log.type ,log.description ,log.parameter,
				log.note ,log.title ,account.id as user_id ,module.name as module_id 
			 from 
			 	log
				left outer join account on account.id=log.user_id 
				left outer join module on module.id=log.module_id 
			where
				log.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$this->parse_layout('delete',$row);
		}
	}
	function permanent_delete($id)
	{
		DB::delete('log', 'id='.$id);
	}
	function delete($id)
	{
		$row = DB::select('log',$id);
		DeleteLogForm::permanent_delete($id);
	}
}
?>