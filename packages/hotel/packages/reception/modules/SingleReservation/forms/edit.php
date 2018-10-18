<?php
class EditSingleReservationForm extends Form
{
	function EditSingleReservationForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('NAME',new TextType(true,'miss_name',0,255));
		$this->add('total_amount',new FloatType(false,'miss_total_amount'));
		$this->add('arrival_time',new DateType(true,'invalid_arrival_time')); 		
		$this->add('departure_time',new DateType(true,'invalid_departure_time')); 
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/picker.js');
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'COMPANY_ID',
				'NAME',
				'NUM_PEOPLE',
				'NOTE',
				'ROOM_QUANTITY',
				'total_amount'=>str_replace('','',Url::get('total_amount')),
				'extra_amount'=>str_replace('','',Url::get('extra_amount')),
				'tour_leader',
				'arrival_time'=>Date_Time::to_orc_date(Url::get('arrival_time')),
				'departure_time'=>Date_Time::to_orc_date(Url::get('departure_time'))
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('TOUR',$array+array('last_modified_user_id'=>Session::get('user_id'),'last_modified_time'=>time()),'ID='.Url::iget('id'));
			}else{
				$id = DB::insert('TOUR',$array+array('user_id'=>Session::get('user_id'),'time'=>time()));
			} 
			Url::redirect_current(array('action'));
		}
	}
	function draw()
	{
		$this->map = array();
		$item = SingleReservation::$item;
		if($item){
			$item['arrival_time'] = $item['arrival_time']?Date_Time::convert_orc_date_to_date($item['arrival_time'],'/'):'';
			$item['departure_time'] = $item['departure_time']?Date_Time::convert_orc_date_to_date($item['departure_time'],'/'):'';
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_tour'):Portal::language('edit_tour');
		$customers = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER WHERE GROUP_ID=\'TOURISM\'');
		$this->map['COMPANY_ID_list'] =  String::get_list($customers);
		$this->parse_layout('edit',$this->map);
	}	
}
?>