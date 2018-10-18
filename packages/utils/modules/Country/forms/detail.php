<?php
class CountryForm extends Form
{
	function CountryForm()
	{
		Form::Form('CountryForm');
		$this->add('id',new IDType(true,'object_not_exists','country'));
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
				country.id
				,country.name_'.Portal::language().' as name
			from 
			 	country
			where
				country.id = \''.URL::sget('id').'\'');
		if($row = DB::fetch())
		{
		}
		$this->parse_layout('detail',$row);
	}
	function delete(&$form,$id)
	{
		$row = DB::select('country',$id);
		DB::delete_id('country', $id);
	}
}
?>