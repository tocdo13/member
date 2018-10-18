<?php
class PersonalInformationForm extends Form
{
	function PersonalInformationForm()
	{
		Form::Form('personal');
		$this->add('full_name',new TextType(true,'invalid_full_name',0,50));
		$this->add('address',new TextType(false,'invalid_address',0,200));
		$this->add('birth_date',new DateType(false,false,0,32));
		$this->add('email',new EmailType(true,'email_invalid'));
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');	
		$this->link_css(Portal::template('hotel').'/css/setting.css');
	}
	function on_submit()
	{
		if($this->check())
		{
			$row = array(
				'full_name'=>Url::get('full_name')
				,'address'=>Url::get('address')
				,'birth_day'=>Date_Time::to_orc_date((URL::get('birth_date')))
				,'gender'=>Url::get('gender')
				,'phone'=>Url::get('phone')
				,'zone_id'=>Url::get('zone_id')
				,'email'=>Url::get('email')
			);
			DB::update('party',$row,' user_id = \''.Session::get('user_id').'\'');
			Url::redirect_current(array('action'=>'succesful'));
		}
	}
	function draw()
	{		
		$sql = '
			SELECT 
				party.*
				,to_char(party.birth_day,\'dd/mm/YYYY\') as birth_date
				,party.name_'.Portal::language().' as name
				,account.last_online_time
				,account.id as account_id
				,account.create_date as create_date
			FROM
				account
				inner join party on party.user_id=account.id
			WHERE 
				account.id=\''.Session::get('user_id').'\' 
				 and party.type=\'USER\'';
   	    $row = array();			
		if($row = DB::fetch($sql))
		{	
			foreach($row as $key=>$value)
			{
				if(is_string($value) and !isset($_REQUEST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}								 			
		}	
		require_once 'cache/hotel/zone.php';
		foreach($zone as $key=>$value)
		{
			unset($zone[$key]['structure_id']);
		}
		$this->parse_layout('information',array(
			'gender_list'=>array('1'=>Portal::language('male'),'0'=>Portal::language('female'))
			,'zone_id_list'=>String::get_list($zone)
		));
	}
}
?>
