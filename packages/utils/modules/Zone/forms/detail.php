<?php
class ZoneForm extends Form
{
	function ZoneForm()
	{
		Form::Form('ZoneForm');
		$this->add('id',new IDType(true,'object_not_exists','zone'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm'))
		{
			$this->delete($this,$_REQUEST['id']);
			Url::redirect_current(array(
	 'name'=>isset($_GET['name'])?$_GET['name']:'', 
	));
		}
	}
	function draw()
	{
		DB::query('
			select 
				zone.id
				,zone.structure_id
				,zone.name_'.Portal::language().' as name
			from 
			 	zone
			where
				zone.id = \''.URL::sget('id').'\'');
		if($row = DB::fetch())
		{
		}
		$this->parse_layout('detail',$row);
	}
	function delete(&$form,$id)
	{
		$row = DB::select('zone',$id);
		DB::delete_id('zone', $id);
	}
}
?>