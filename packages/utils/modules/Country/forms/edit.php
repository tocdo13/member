<?php
class EditCountryForm extends Form
{
	function EditCountryForm()
	{
		Form::Form('EditCountryForm');
		$this->add('name_1',new TextType(true,'miss_name',0,255));
		$this->add('name_2',new TextType(true,'miss_name',0,255));
		$this->add('code_1',new TextType(true,'miss_code',0,255));
		$this->add('code_2',new TextType(true,'miss_code',0,255));
	}
	function on_submit()
	{
		if($this->check())
		{
			$array = array('name_1','name_2','code_1','code_2','continents_id'=>Url::get('continents_id'));
			if(URL::get('cmd')=='edit' and Url::get('id') and $row = DB::fetch('select id from country where id ='.Url::iget('id').''))
			{
				$id = Url::iget('id');
				DB::update_id('country', $array,$id);
			}
			else
			{
					DB::insert('country',$array);
			}
			Url::redirect_current(array('just_edited_id'=>$id));
		}
	}	
	function draw()
	{	
		if(URL::get('cmd')=='edit' and $row=DB::select('country',URL::sget('id')))
		{
			foreach($row as $key=>$value)
			{
				if(is_string($value) and !isset($_REQUEST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}
			$edit_mode = true;
		}
		else
		{
			$edit_mode = false;
		}
        //$arr = array(''=>Portal::language('select_contents'),1=>Portal::language('european'),2=>Portal::language('asia'),3=>Portal::language('the_other'));
        ///$arr_list = String::get_list($arr);
		$this->parse_layout('edit',($edit_mode?$row:array()));
	}
}
?>