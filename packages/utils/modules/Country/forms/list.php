<?php
class ListCountryForm extends Form
{
	function ListCountryForm()
	{
		Form::Form('ListCountryForm');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'detail.php';
			foreach(URL::get('selected_ids') as $id)
			{
				CountryForm::delete($this,$id);
				if($this->is_error())
				{
					return;
				}
			}
			Url::redirect_current(array('name'=>isset($_GET['name'])?$_GET['name']:''));
		}
	}
	function draw()
	{
	    require_once 'packages/core/includes/utils/vn_code.php';  
		$cond = '1=1'
			.(URL::get('name')?' and lower(FN_CONVERT_TO_VN(country.name_'.Portal::language().')) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('name'),'utf-8')).'%\'':'') 
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and country.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
		DB::query('
			select 
				country.id
				,country.name_1
				,country.name_2				
				,country.code_1
				,country.code_2
                ,country.selected_report
			from 
			 	country
			where 
				'.$cond.'
			order by 
				country.name_2
		');
		$items = DB::fetch_all();
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
		}
        //System::debug($items);
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'total'=>sizeof($items)
			)
		);
	}
}
?>