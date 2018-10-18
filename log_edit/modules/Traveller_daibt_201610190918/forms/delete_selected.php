<?php
class DeleteSelectedTravellerForm extends Form
{
	function DeleteSelectedTravellerForm()
	{
		Form::Form("DeleteSelectedTravellerForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteTravellerForm::delete($id);
			}
			Url::redirect_current(array( 'nationality_id','first_name',    'passport', 'visa'));
		}
	}
	function draw()
	{
		DB::query('
			select 
				traveller.id
				,traveller.first_name ,traveller.last_name ,traveller.gender ,DATE_FORMAT(traveller.birth_date,"%d/%m/%Y") as birth_date ,traveller.passport ,traveller.visa ,traveller.address ,traveller.email ,traveller.phone ,traveller.fax ,traveller.note 
				

				,tour.name as tour_id  
			from 
			 	traveller
				

				left outer join tour on tour.id=traveller.tour_id 

				left outer join country on country.id=traveller.nationality_id 
			where traveller.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		foreach($items as $key=>$item)
		{
			  

			$defintition = array('1'=>'male','0'=>'female');
			if(isset($defintition[$items[$key]['gender']]))
			{
				$items[$key]['gender'] = $defintition[$items[$key]['gender']];
			}
			else
			{
				$items[$key]['gender'] = '';
			}
			$items[$key]['gender']=$item['gender']?Portal::language('male'):Portal::language('female');         
		}
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>