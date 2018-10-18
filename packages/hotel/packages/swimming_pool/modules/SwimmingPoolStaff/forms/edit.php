<?php
class EditSwimmingPoolStaffForm extends Form
{
	function EditSwimmingPoolStaffForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('full_name',new TextType(true,'miss_full_name',0,255));
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function on_submit(){
		if($this->check()){
			$array = array(
				'full_name',
				'birth_date'=>Date_Time::to_orc_date(Url::get('birth_date')),
				'gender',
				'native',
				'address',
				'email',
				'phone',
				'date_in'=>Date_Time::to_orc_date(Url::get('date_in')),
				'date_out'=>Date_Time::to_orc_date(Url::get('date_out')),
				'marrital_status',
				'description'
			);
			if(Url::get('cmd')=='edit'){
				$id = Url::iget('id');
				DB::update('swimming_pool_staff',$array,'id='.Url::iget('id'));
			}else{
				$id = DB::insert('swimming_pool_staff',$array);
			} 
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$this->map['gender_list']=array(
			'1'=>Portal::language('female'),
			'0'=>Portal::language('male'),			
		);
		$this->map['marrital_status_list']=array(
			'0'=>Portal::language('single'),
			'1'=>Portal::language('marriage')
		);
		$item = SwimmingPoolStaff::$item;
		if($item){
			$item['date_in'] = Date_Time::convert_orc_date_to_date($item['date_in'],'/');
			$item['date_out'] = Date_Time::convert_orc_date_to_date($item['date_out'],'/');
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_SwimmingPoolStaff'):Portal::language('edit_SwimmingPoolStaff');
		$this->parse_layout('edit',$this->map);
	}	
}
?>