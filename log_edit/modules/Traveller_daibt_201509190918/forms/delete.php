<?php
class DeleteTravellerForm extends Form
{
	function DeleteTravellerForm()
	{
		Form::Form('DeleteTravellerForm');
		$this->add('id',new IDType(true,'object_not_exists','traveller'));
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
				traveller.id
				,traveller.first_name ,traveller.last_name ,traveller.gender 
				,to_char(traveller.birth_date,\'DD/MM/YYYY\') AS birth_date
				,traveller.passport ,traveller.visa ,traveller.address 
				,traveller.email ,traveller.phone ,traveller.fax 
				,traveller.note 
				,tour.name as tour_id 
				,country.name_'.Portal::language().' as nationality_id 
			from 
			 	traveller
				left outer join tour on tour.id=traveller.tour_id 
				left outer join country on country.id=traveller.nationality_id 
			where
				traveller.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$defintition = array('1'=>'male','0'=>'female');
			if(isset($defintition[$row['gender']]))
			{
				$row['gender'] = $defintition[$row['gender']];
			}
			else
			{
				$row['gender'] = '';
			}
			$row['gender']=$row['gender']?Portal::language('male'):Portal::language('female');         
		}
		$this->parse_layout('delete',$row);
	}
	function permanent_delete($id)
	{
		if($row = DB::select('traveller',$id)){
			DB::delete('traveller_comment','traveller_id = '.$id);
			DB::delete_id('traveller', $id);
		}
	}
	function delete($id)
	{
		DeleteTravellerForm::permanent_delete($id);
	}
}
?>