<?php
class EditTourForm extends Form
{
	function EditTourForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('name',new TextType(true,'miss_name',0,255));
		$this->add('total_amount',new FloatType(false,'miss_total_amount'));
		$this->add('arrival_time',new DateType(false,'invalid_arrival_time')); 		
		$this->add('departure_time',new DateType(false,'invalid_departure_time')); 
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/picker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');		
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'COMPANY_ID',
				'name',
				'NUM_PEOPLE',
				'NOTE',
                'ROOM_QUANTITY',
                'name_set'=>Url::get('name_set'),
				'phone_set'=>Url::get('phone_set'),
				'total_amount'=>str_replace('','',Url::get('total_amount')),
				'extra_amount'=>str_replace('','',Url::get('extra_amount')),
				'tour_leader',
				'arrival_time'=>Date_Time::to_orc_date(Url::get('arrival_time')),
				'departure_time'=>Date_Time::to_orc_date(Url::get('departure_time')),
				'portal_id'=>PORTAL_ID,
				'is_vn'=>Url::get('is_vn'),
				'entry_date'=>Date_Time::to_orc_date(Url::get('entry_date')),
				'port_of_entry',
				'back_date'=>Date_Time::to_orc_date(Url::get('back_date')),
				'entry_target',
				'go_to_office',
				'come_from'
			);
            System::debug($array);
			$log_description = '';
			if(Url::get('cmd')=='edit'){
				$log_action = 'edit';// Edited in 28/02/2011
				$id = Url::iget('id');
				DB::update('TOUR',$array+array('last_modified_user_id'=>Session::get('user_id'),'last_modified_time'=>time()),'ID='.Url::iget('id'));
				$log_description .= 'Edit tour: '.Url::get('name').'';
			}else{
				$log_action = 'add';// Edited in 28/02/2011
				$id = DB::insert('TOUR',$array+array('user_id'=>Session::get('user_id'),'time'=>time()));
				$log_description .= 'Add tour: '.Url::get('name').'';
			} 
			$log_title = 'Tour: #'.$id.'';
			System::log($log_action,$log_title,$log_description,$id);// Edited in 28/02/2011
			Url::redirect_current(array('action'));
		}
	}
	function draw()
	{
		$this->map = array();
		$item = Tour::$item;
		if($item){
			$item['arrival_time'] = $item['arrival_time']?Date_Time::convert_orc_date_to_date($item['arrival_time'],'/'):'';
			$item['departure_time'] = $item['departure_time']?Date_Time::convert_orc_date_to_date($item['departure_time'],'/'):'';
			$item['entry_date'] = $item['entry_date']?Date_Time::convert_orc_date_to_date($item['entry_date'],'/'):'';
			$item['back_date'] = $item['back_date']?Date_Time::convert_orc_date_to_date($item['back_date'],'/'):'';
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_tour'):Portal::language('edit_tour');
		//dat-4-9-2013
        //$customers = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER WHERE GROUP_ID=\'TOURISM\' ORDER BY NAME');
        $customers = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER ORDER BY NAME');
		$this->map['COMPANY_ID_list'] =  String::get_list($customers);
		DB::query('
			select
				id, concat(country.code_2, concat(\' - \',country.name_'.Portal::language().')) as name
			from 
				country
			where 
				1=1
			order by 
				name_2
			'
		);
		$nationality_id_list =String::get_list(DB::fetch_all()); 
		$ports = array('XXX'=>Portal::language('not_update_yet'))+String::get_list(DB::fetch_all('select code as id,CONCAT(gate.code,CONCAT(\' - \',gate.name)) as name from gate'));
		$entry_target = String::get_list(DB::fetch_all('select code as id,CONCAT(entry_purposes.code,CONCAT(\' - \',entry_purposes.name)) as name from entry_purposes'));
		$this->map += array(
			'nationality_id_list'=>array(''=>Portal::language('select'))+$nationality_id_list,
			'come_from_list'=>array(''=>Portal::language('select'))+$nationality_id_list,
			'port_of_entry_list'=>array(''=>Portal::language('select'))+$ports,
			'entry_target_list'=>array(''=>Portal::language('select'))+$entry_target
		);
		$this->parse_layout('edit',$this->map);
	}	
}
?>